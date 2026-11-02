<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/featured-kits.php';
require_once __DIR__ . '/store-improvements.php';
require_once __DIR__ . '/measurement.php';
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce', array('product_grid' => array('default_columns' => 3)));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('liconex-shop', get_stylesheet_uri(), array(), '0.3.2');
});
add_action('wp', function () {
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
});
add_action('woocommerce_before_main_content', function () { echo '<main id="main" class="wrap shop-main">'; }, 10);
add_action('woocommerce_after_main_content', function () { echo '</main>'; }, 10);
add_filter('loop_shop_columns', function () { return 3; });
add_filter('loop_shop_per_page', function () { return 24; });
add_action('woocommerce_before_shop_loop', 'liconex_market_open', 5);
add_action('woocommerce_after_shop_loop', 'liconex_market_close', 50);
function liconex_market_open() {
    echo '<div class="market-layout"><aside class="market-sidebar"><h2>Encontrá tu equipo</h2>';
    liconex_shop_categories();
    echo '<form class="price-filter" method="get" action="' . esc_url(is_product_category() ? get_term_link(get_queried_object()) : wc_get_page_permalink('shop')) . '"><h3>Precio en USD</h3>';
    if (is_search()) { echo '<input type="hidden" name="s" value="' . esc_attr(get_search_query()) . '"><input type="hidden" name="post_type" value="product">'; }
    echo '<div><label>Desde<input type="number" name="min_price" min="0" step="0.01" placeholder="0" value="' . esc_attr(isset($_GET['min_price']) ? wc_format_decimal(wp_unslash($_GET['min_price'])) : '') . '"></label><label>Hasta<input type="number" name="max_price" min="0" step="0.01" placeholder="Máximo" value="' . esc_attr(isset($_GET['max_price']) ? wc_format_decimal(wp_unslash($_GET['max_price'])) : '') . '"></label></div><button type="submit">Aplicar precio</button></form><div class="sidebar-help"><b>¿No sabés cuál elegir?</b><p>Contanos qué necesitás proteger.</p><a href="https://wa.me/59899204188">Hablar con Liconex ↗</a></div></aside><div class="market-results">';
}
function liconex_market_close() { echo '</div></div>'; }
add_action('woocommerce_after_shop_loop_item_title', function () { echo '<span class="product-currency">USD · IVA incluido</span>'; }, 11);
add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) { return $product->is_in_stock() ? $text : 'Ver producto'; }, 10, 2);
add_action('woocommerce_no_products_found', 'liconex_shop_categories', 5);
function liconex_shop_categories() {
    $terms = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0));
    if (is_wp_error($terms) || !$terms) { return; }
    $priority = array('kits-con-instalacion', 'videovigilancia', 'alarmas-y-sensores', 'control-de-acceso', 'proteccion-perimetral', 'porteria', 'porteria-ip', 'hogar-inteligente', 'redes');
    usort($terms, function ($a, $b) use ($priority) {
        $pa = array_search($a->slug, $priority, true);
        $pb = array_search($b->slug, $priority, true);
        return (($pa === false ? 99 : $pa) <=> ($pb === false ? 99 : $pb)) ?: strcasecmp($a->name, $b->name);
    });
    echo '<details class="category-panel" open><summary>Categorías</summary><nav class="shop-categories" aria-label="Categorías de productos">';
    $extra_open = false;
    $labels = array('Cable Alim'=>'Cables de alimentación','Cable Datos'=>'Cables de datos','Cable Video'=>'Cables de video','Cable Audio'=>'Cables de audio','Cable Utp'=>'Cables UTP','Cable Utp/Ftp'=>'Cables UTP / FTP','Telefonia'=>'Telefonía','Bateria'=>'Baterías','Pilas'=>'Pilas','Pc'=>'Computación','Conector'=>'Conectores','Adaptador'=>'Adaptadores','Ups'=>'UPS','Luz Emergencia'=>'Luces de emergencia','Luz De Emergencia'=>'Luces de emergencia');
    foreach ($terms as $term) {
        if (!$extra_open && !in_array($term->slug, $priority, true)) {
            echo '</nav><details class="category-panel"><summary>Más categorías y accesorios</summary><nav class="shop-categories" aria-label="Más categorías">';
            $extra_open = true;
        }
        $term_label = $labels[$term->name] ?? $term->name;
        $url = get_term_link($term);
        if (!is_wp_error($url)) { echo '<a href="' . esc_url($url) . '"' . (is_product_category($term->term_id) ? ' aria-current="page"' : '') . '><span>' . esc_html($term_label) . '</span><small>' . esc_html($term->count) . '</small></a>'; }
    }
    echo '</nav></details>' . ($extra_open ? '</details>' : '');
}
add_filter('woocommerce_page_title', function ($title) {
    return is_search() ? 'Resultados para: “' . get_search_query() . '”' : (is_shop() ? 'Todos los productos' : $title);
});
add_filter('woocommerce_breadcrumb_defaults', function ($args) { $args['home'] = 'Inicio'; return $args; });
add_filter('woocommerce_catalog_orderby', function ($options) {
    $labels = array('menu_order' => 'Orden recomendado', 'popularity' => 'Más populares', 'rating' => 'Mejor valorados', 'date' => 'Más recientes', 'price' => 'Menor precio', 'price-desc' => 'Mayor precio', 'relevance' => 'Más relevantes');
    foreach ($options as $key => $label) { if (isset($labels[$key])) { $options[$key] = $labels[$key]; } }
    return $options;
});
add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain !== 'woocommerce') { return $translated; }
    $labels = array('Showing all %d results' => 'Mostrando los %d productos', 'Showing the single result' => 'Mostrando un producto', 'No products were found matching your selection.' => 'No encontramos productos con esos filtros.', 'Default sorting' => 'Orden recomendado', 'Relevance' => 'Más relevantes', 'Search results for &ldquo;%s&rdquo;' => 'Resultados para &ldquo;%s&rdquo;');
    return isset($labels[$text]) ? $labels[$text] : $translated;
}, 10, 3);
add_filter('ngettext', function ($translated, $single, $plural, $number, $domain) {
    if ($domain === 'woocommerce' && $single === 'Showing all %d results') { return 'Mostrando los %d productos'; }
    if ($domain === 'woocommerce' && strpos($single, 'Showing %1$d') === 0) { return 'Mostrando %1$d–%2$d de %3$d productos'; }
    return $translated;
}, 10, 5);

// Keep the currency unambiguous in the catalog and WooCommerce Store API.
add_filter('woocommerce_currency_symbol', function ($symbol, $currency) {
    return $currency === 'USD' ? 'USD' : $symbol;
}, 10, 2);
add_filter('render_block', function ($content, $block) {
    if (in_array($block['blockName'] ?? '', array('woocommerce/checkout-shipping-methods-block', 'woocommerce/cart-shipping-block'), true)) {
        $content .= '<p class="liconex-shipping-policy">Montevideo: envío gratis en compras mayores a USD 100. Para compras de USD 100 o menos: UYU 250, convertido a USD según Mercado Pago (sujeto a redondeo). Resto de Uruguay: encomienda a pagar al recibir; el flete no está incluido en el total de la compra.</p>';
    }
    return $content;
}, 10, 2);
add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_checkout') && (is_checkout() || is_cart())) {
        wp_enqueue_script('liconex-shipping-labels', get_template_directory_uri() . '/assets/shipping-labels.js', array(), '0.2.2', true);
    }
});

