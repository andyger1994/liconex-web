<?php
defined('ABSPATH') || exit;
function liconex_featured_installed_kits() {
    if (!function_exists('is_shop') || !is_shop() || is_search() || is_paged()) return;
    // Featured offers must not displace the products a shopper explicitly filtered.
    foreach (array_keys($_GET) as $key) {
        if (in_array($key, array('orderby','min_price','max_price','rating_filter','filter_stock_status'), true) || strpos($key, 'filter_') === 0) return;
    }
    $cards = array();
    foreach (array('LX-KIT-8000-INST'=>'AMT 8000 PRO', 'LX-KIT-2018-INST'=>'AMT 2018 E SMART', 'LX-KIT-NET24-INST'=>'ANM 24 NET SMART') as $sku=>$label) {
        $product = wc_get_product(wc_get_product_id_by_sku($sku));
        if (!$product || 'publish' !== $product->get_status() || !$product->is_visible()) continue;
        $cards[] = array($product, $label);
    }
    if (!$cards) return;
    echo '<section class="wrap installed-kits" aria-labelledby="installed-kits-title"><div class="installed-kits-intro"><div><span class="eyebrow">Kits Liconex · Instalación incluida</span><h1 id="installed-kits-title">Tu alarma, instalada.</h1><p>Central, 4 sensores de movimiento y 2 de apertura.<br>Instalamos y configuramos en Montevideo y Canelones.</p></div><a class="installed-kits-all" href="'.esc_url(home_url('/product-category/kits-con-instalacion/')).'">Ver los tres kits ↗</a></div><div class="installed-kits-grid">';
    foreach ($cards as $index => [$product,$label]) {
        echo '<article class="installed-kit-card"><a href="'.esc_url($product->get_permalink()).'">'.wp_kses_post($product->get_image('woocommerce_single', array('loading'=>$index === 0 ? 'eager' : 'lazy','fetchpriority'=>$index === 0 ? 'high' : 'auto','alt'=>$product->get_name()))).'<div class="installed-kit-info"><span class="kit-badge">Instalación incluida</span><h2>'.esc_html($label).'</h2><p>Kit de alarma con instalación</p><strong class="installed-kit-price">'.wp_kses_post($product->get_price_html()).'</strong><small>Equipos + instalación · precio final</small><span class="installed-kit-cta">Ver kit e instalación ↗</span></div></a></article>';
    }
    echo '</div><p class="installed-kits-note">Para otras zonas, <a href="https://wa.me/59899204188">consultá al 099 204 188</a>. Monitoreo opcional según la central; abono no incluido.</p></section>';
}
