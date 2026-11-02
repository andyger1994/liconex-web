<?php
defined('ABSPATH') || exit;
function lx_ga_item($product, $quantity = 1, $price = null) {
    return array('item_id'=>(string)$product->get_id(), 'item_name'=>$product->get_name(), 'price'=>(float)($price ?? wc_get_price_to_display($product)), 'quantity'=>(int)$quantity);
}
add_action('woocommerce_add_to_cart', function ($cart_key, $product_id, $quantity, $variation_id) {
    if (wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST) || !WC()->session) return;
    $product = wc_get_product($variation_id ?: $product_id);
    if ($product) WC()->session->set('lx_added_items', array(lx_ga_item($product, $quantity)));
}, 20, 4);
add_action('wp_enqueue_scripts', function () {
    // Keep owner's maintenance visits out of customer metrics.
    if (current_user_can('manage_options')) return;
    wp_enqueue_script('lx-google', 'https://www.googletagmanager.com/gtag/js?id=G-SGQX234Y86', array(), null, false);
    wp_add_inline_script('lx-google', "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-SGQX234Y86',{page_location:location.origin+location.pathname,page_referrer:document.referrer.split('?')[0].split('#')[0],allow_google_signals:false,allow_ad_personalization_signals:false});gtag('config','AW-18537120072',{page_location:location.origin+location.pathname,page_referrer:document.referrer.split('?')[0].split('#')[0],allow_ad_personalization_signals:false});", 'before');
    wp_enqueue_script('lx-measurement', get_template_directory_uri().'/assets/measurement.js', array('jquery'), '0.3.0', true);
    $events = array();
    if (WC()->session && ($added = WC()->session->get('lx_added_items'))) {
        WC()->session->__unset('lx_added_items');
        $events[] = array('name'=>'add_to_cart','data'=>array('currency'=>get_woocommerce_currency(),'items'=>$added));
    }
    if (is_product()) {
        $p = wc_get_product(get_queried_object_id());
        if ($p) $events[] = array('name'=>'view_item','data'=>array('currency'=>get_woocommerce_currency(),'value'=>(float)wc_get_price_to_display($p),'items'=>array(lx_ga_item($p))));
    }
    if ((is_cart() || is_checkout()) && !is_order_received_page() && WC()->cart && !WC()->cart->is_empty()) {
        $items = array(); $value = 0;
        foreach (WC()->cart->get_cart() as $line) {
            $qty = (int)$line['quantity']; $total = (float)$line['line_total'];
            $items[] = lx_ga_item($line['data'], $qty, $qty ? $total / $qty : 0); $value += $total;
        }
        $events[] = array('name'=>is_cart()?'view_cart':'begin_checkout','data'=>array('currency'=>get_woocommerce_currency(),'value'=>round($value,2),'items'=>$items));
    }
    // Only report a paid order viewed with its valid order key. Never treat a
    // placed/pending order, checkout click or payment attempt as a purchase.
    if (is_order_received_page()) {
        $order = wc_get_order(absint(get_query_var('order-received')));
        $key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';
        if ($order && $key && hash_equals($order->get_order_key(), $key) && $order->is_paid()) {
            $items = array();
            foreach ($order->get_items() as $line) {
                $p = $line->get_product(); $qty = $line->get_quantity();
                if ($p && $qty > 0) $items[] = lx_ga_item($p, $qty, (float)$line->get_total()/$qty);
            }
            $events[] = array('name'=>'purchase','data'=>array('transaction_id'=>'LX-'.$order->get_id(),'currency'=>$order->get_currency(),'value'=>(float)$order->get_total()-(float)$order->get_total_tax()-(float)$order->get_shipping_total(),'tax'=>(float)$order->get_total_tax(),'shipping'=>(float)$order->get_shipping_total(),'items'=>$items));
        }
    }
    wp_add_inline_script('lx-measurement', 'window.lxCommerceEvents='.wp_json_encode($events).';', 'before');
});
