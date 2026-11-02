<?php
defined('ABSPATH') || exit;

function lx_readable_product_name($name) {
    // Preserve model numbers, standards, dimensions and technical acronyms.
    // This is presentation only: supplier names, SKUs and URLs stay unchanged.
    $words = array('ALARMA'=>'Alarma','CAMARA'=>'Cámara','CÁMARA'=>'Cámara','SENSOR'=>'Sensor','INALAMBRICO'=>'inalámbrico','INALÁMBRICO'=>'inalámbrico','SIRENA'=>'Sirena','BATERIA'=>'Batería','BATERÍA'=>'Batería','CONTROL'=>'Control','REMOTO'=>'remoto','CENTRAL'=>'Central','TECLADO'=>'Teclado','RECEPTOR'=>'Receptor','TRANSMISOR'=>'Transmisor','FUENTE'=>'Fuente','ALIMENTACION'=>'alimentación','ALIMENTACIÓN'=>'alimentación','CABLE'=>'Cable','CONECTOR'=>'Conector','ADAPTADOR'=>'Adaptador','EXTENSOR'=>'Extensor','SOPORTE'=>'Soporte','GABINETE'=>'Gabinete','CERRADURA'=>'Cerradura','LECTOR'=>'Lector','TARJETA'=>'Tarjeta','LLAVERO'=>'Llavero','PULSADOR'=>'Pulsador','DETECTOR'=>'Detector','APERTURA'=>'apertura','MOVIMIENTO'=>'movimiento','EXTERIOR'=>'exterior','INTERIOR'=>'interior','BLANCO'=>'blanco','BLANCA'=>'blanca','NEGRO'=>'negro','NEGRA'=>'negra','INTELBRAS'=>'Intelbras','MANHATTAN'=>'Manhattan','ANBYTE'=>'Anbyte','HIKVISION'=>'Hikvision','DAHUA'=>'Dahua','LOGITECH'=>'Logitech','TP-LINK'=>'TP-Link');
    $name = str_replace(' | ', ' · ', $name);
    return preg_replace_callback('/(?<![\pL\pN])('.implode('|',array_map(function ($word) { return preg_quote($word,'/'); },array_keys($words))).')(?![\pL\pN])/u', function ($match) use ($words) { return $words[$match[1]]; }, $name);
}
add_filter('the_title', function ($title, $id) {
    return !is_admin() && get_post_type($id) === 'product' ? lx_readable_product_name($title) : $title;
}, 20, 2);
add_filter('woocommerce_product_get_name', function ($name) { return is_admin() ? $name : lx_readable_product_name($name); });

// Informational pages stay editable in WordPress. Creation is an explicit,
// authenticated admin action, never a side effect of a public page request.
function lx_store_documents() {
    return array(
        'envios-e-instalaciones' => array('Envíos e instalaciones', '<h2>Entrega de productos en 48 horas</h2><p>Liconex.uy entrega los productos comprados dentro de las 48 horas. Coordinamos la recepción por el contacto informado en el pedido.</p><h2>Envíos en Uruguay</h2><p>En Montevideo, el envío es gratis en compras mayores a USD 100. En compras de USD 100 o menos cuesta UYU 250, convertido a USD al finalizar la compra. Para el resto del país enviamos por encomienda: el cliente paga el flete al recibir; ese importe no está incluido en el total de la tienda.</p><h2>Instalación incluida en los kits indicados</h2><p>Los kits cuyo título indica «con instalación incluida» incluyen instalación en Montevideo y Canelones. La coordinamos dentro de los 7 días hábiles, según demanda. La ficha de cada kit detalla los componentes y el alcance del trabajo. Para otras zonas, consultanos antes de comprar.</p><p>Los equipos vendidos por separado no incluyen instalación salvo indicación expresa en su ficha.</p>'),
        'garantia-y-devoluciones' => array('Garantía y devoluciones', '<h2>Garantía escrita de 2 años</h2><p>Los productos y las instalaciones de Liconex.uy cuentan con garantía firmada de 2 años. Entregamos la documentación de garantía correspondiente a tu compra e instalación.</p><p>Para solicitar asistencia, indicá el número de pedido, el equipo y lo que ocurre. Coordinaremos la revisión y te informaremos los pasos a seguir.</p><h2>Compras a distancia</h2><p>En las compras a distancia existe el derecho de retracto previsto por la normativa uruguaya: podés comunicar que dejás sin efecto la compra dentro de los 5 días hábiles desde la contratación o la recepción del producto, según corresponda. El producto debe estar sin uso; podés abrir el embalaje para inspeccionarlo. El costo de retorno es a cargo del consumidor y corresponde la restitución inmediata de lo pagado, conforme a las condiciones legales aplicables.</p><p>Comunicá tu solicitud por escrito a nuestro correo para que quede constancia. Estas condiciones no limitan los derechos legales del consumidor. Para servicios y situaciones particulares se aplica la normativa correspondiente.</p><p><a href="https://www.gub.uy/ministerio-economia-finanzas/comunicacion/publicaciones/consumidor-tiene-derecho-dejar-sin-efecto-compraventa-hecha-web-haciendo">Información oficial del Ministerio de Economía y Finanzas sobre compras por web</a>.</p>'),
        'condiciones-de-compra' => array('Condiciones de compra', '<h2>Comprar en Liconex.uy</h2><p>El catálogo muestra precios en USD. Antes de confirmar, revisá los productos, cantidades, dirección, método de envío y total del pedido. El pago se procesa mediante Mercado Pago; las alternativas disponibles y el importe a pagar se muestran durante el proceso de pago.</p><p>Un clic en el botón de pago no acredita por sí solo una compra: el pedido informa su estado y la confirmación del cobro. Conservá el número de pedido para cualquier consulta.</p><h2>Productos y kits</h2><p>El contenido de cada kit y si incluye instalación se detalla en su ficha. Verificá la compatibilidad de los accesorios con tu central antes de comprar; podemos asesorarte por WhatsApp. Un servicio de monitoreo no está incluido salvo que se indique expresamente.</p><p>Consultá las condiciones de <a href="/envios-e-instalaciones/">envío e instalación</a> y de <a href="/garantia-y-devoluciones/">garantía y devoluciones</a>.</p>'),
        'privacidad-tienda' => array('Privacidad de la tienda', '<h2>Tus datos en Liconex.uy</h2><p>Usamos los datos de contacto, facturación, dirección y productos del pedido para gestionar tu compra, entrega, instalación y atención posterior. No publiques contraseñas ni datos de tarjeta en mensajes o notas del pedido.</p><p>Mercado Pago procesa los datos del pago mediante su plataforma. Los datos necesarios para una entrega pueden compartirse con el distribuidor o transportista que la realiza. Conservamos la información del pedido necesaria para su seguimiento, garantía y obligaciones aplicables.</p><h2>Medición y navegación</h2><p>La tienda puede usar Google Analytics y la etiqueta de Google Ads para medir navegación e interacción comercial. No enviamos nombres, correos, teléfonos, direcciones ni notas del pedido a los eventos de medición. Las herramientas del navegador permiten gestionar las cookies. Los enlaces a WhatsApp y Mercado Pago abren servicios con sus propias políticas de privacidad.</p><h2>Consultas y derechos</h2><p>Podés consultar sobre el uso de tus datos y solicitar acceso, rectificación o supresión cuando corresponda, escribiendo al correo de Liconex indicado abajo.</p>')
    );
}
function lx_store_contact_html() {
    return '<h2>Hablemos de tu pedido</h2><p>Liconex.uy · Uruguay<br><a href="mailto:gerardopereira@liconex.uy">gerardopereira@liconex.uy</a><br><a href="https://wa.me/59899204188">WhatsApp: 099 204 188</a></p>';
}
add_action('admin_menu', function () {
    add_theme_page('Información de la tienda', 'Información de la tienda', 'manage_options', 'lx-store-info', function () {
        if (!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>Información comercial de Liconex.uy</h1><p>Publica páginas editables con los plazos y la garantía confirmados, y las vincula a las condiciones de compra y privacidad de WooCommerce. No modifica productos, precios, stock ni pedidos.</p>';
        if (isset($_GET['saved'])) echo '<div class="notice notice-success"><p>Páginas publicadas y vinculadas.</p></div>';
        echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
        wp_nonce_field('lx_store_publish');
        echo '<input type="hidden" name="action" value="lx_store_publish">';
        submit_button('Publicar información comercial');
        echo '</form></div>';
    });
});
add_action('admin_post_lx_store_publish', function () {
    if (!current_user_can('manage_options')) wp_die('Acceso restringido.', '', array('response'=>403));
    check_admin_referer('lx_store_publish');
    $ids = array();
    foreach (lx_store_documents() as $slug => $doc) {
        $existing = get_page_by_path($slug);
        $id = wp_insert_post(array('ID'=>$existing ? $existing->ID : 0, 'post_type'=>'page', 'post_status'=>'publish', 'post_name'=>$slug, 'post_title'=>$doc[0], 'post_content'=>$doc[1].lx_store_contact_html()), true);
        if (is_wp_error($id)) wp_die(esc_html($id->get_error_message()));
        $ids[$slug] = $id;
    }
    update_option('woocommerce_terms_page_id', $ids['condiciones-de-compra']);
    update_option('wp_page_for_privacy_policy', $ids['privacidad-tienda']);
    wp_safe_redirect(admin_url('themes.php?page=lx-store-info&saved=1')); exit;
});
function lx_store_service_note() {
    echo '<aside class="lx-service-note" aria-label="Entrega y garantía"><strong>Comprá con respaldo de Liconex.uy</strong><p>Entrega de productos dentro de 48 horas. Instalaciones dentro de 7 días hábiles, según demanda. Productos e instalaciones con garantía firmada de 2 años.</p><a href="'.esc_url(home_url('/envios-e-instalaciones/')).'">Envíos e instalaciones</a> · <a href="'.esc_url(home_url('/garantia-y-devoluciones/')).'">Garantía y devoluciones</a></aside>';
}
add_action('woocommerce_single_product_summary', 'lx_store_service_note', 35);
add_action('woocommerce_before_checkout_form', 'lx_store_service_note', 5);
add_filter('render_block', function ($html, $block) {
    if (($block['blockName'] ?? '') === 'woocommerce/checkout') {
        ob_start(); lx_store_service_note(); $html = ob_get_clean().$html;
    }
    return $html;
}, 20, 2);
// A single H1 on the unfiltered catalog landing page.
add_filter('woocommerce_show_page_title', function ($show) {
    if (is_shop() && !is_search() && !is_paged() && !$_GET) return false;
    return $show;
});
add_action('woocommerce_before_shop_loop', function () {
    if (is_shop() && !is_search() && !is_paged() && !$_GET) echo '<h2 class="lx-catalog-title">Todos los productos</h2>';
}, 1);

// Archive canonicals and descriptions complement WooCommerce's product schema.
add_action('wp_head', function () {
    if (is_admin() || is_404()) return;
    $description = '';
    if (is_product()) {
        $product = wc_get_product(get_queried_object_id());
        if ($product) $description = $product->get_short_description() ?: $product->get_name().' en Liconex.uy. Equipos de seguridad electrónica en Uruguay, entrega dentro de 48 horas y garantía escrita de 2 años.';
    } elseif (is_shop()) {
        $description = 'Equipos de seguridad electrónica y kits de alarma con instalación en Uruguay. Cámaras, alarmas y control de acceso. Garantía escrita de 2 años. Liconex.uy.';
    } elseif (is_product_category()) {
        $description = single_term_title('', false).' en Liconex.uy. Compará equipos de seguridad, consultá compatibilidad y comprá en USD con envío en Uruguay.';
    } elseif (is_page()) $description = get_post_field('post_content', get_queried_object_id());
    if ($description) echo '<meta name="description" content="'.esc_attr(wp_html_excerpt(wp_strip_all_tags(strip_shortcodes($description)), 160, '…')).'">' . "\n";
    if (is_shop() || is_product_category()) {
        $url = is_product_category() ? get_term_link(get_queried_object()) : home_url('/');
        if (!is_wp_error($url)) {
            $page = max(1, (int)get_query_var('paged'));
            if ($page > 1) $url = trailingslashit($url).'page/'.$page.'/';
            echo '<link rel="canonical" href="'.esc_url($url).'">' . "\n";
        }
    }
}, 2);
add_filter('wp_robots', function ($robots) {
    if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page() || is_search())) $robots['noindex'] = true;
    foreach (array_keys($_GET) as $key) {
        if (in_array($key, array('orderby','min_price','max_price','rating_filter'), true) || strpos($key,'filter_') === 0) $robots['noindex'] = true;
    }
    return $robots;
});

// Curated cross-sells use verified components from the existing installed kits.
// Never mix random alarm accessories just because they share a broad category.
add_filter('woocommerce_related_products', function ($ids, $product_id) {
    $product = wc_get_product($product_id);
    if (!$product) return $ids;
    $sku = $product->get_sku();
    $families = array(
        array('BAL430197','BAL430425','BAL430432','LX-KIT-8000-INST'),
        array('BAL541237','BAL320801','BAL860402','BAL312059','BAL508834','BAL160827','LX-KIT-2018-INST','LX-KIT-NET24-INST')
    );
    if (strpos($sku, 'LX-KIT-') === 0) {
        $skus = array('LX-KIT-8000-INST','LX-KIT-2018-INST','LX-KIT-NET24-INST');
    } else {
        $skus = array();
        foreach ($families as $family) if (in_array($sku, $family, true)) $skus = $family;
        if (!$skus) return has_term('alarmas-y-sensores','product_cat',$product_id) ? array() : $ids;
        // Centrals are alternative systems, not accessories for each other.
        $centrals = array('BAL430197','BAL541237','BAL320801');
        if (in_array($sku,$centrals,true)) $skus = array_diff($skus,$centrals);
        $skus = array_filter($skus, function ($s) { return strpos($s,'LX-KIT-') !== 0; });
    }
    $result = array();
    foreach ($skus as $related_sku) {
        $id = wc_get_product_id_by_sku($related_sku);
        if ($id && $id !== (int)$product_id) $result[] = $id;
    }
    return $result;
}, 20, 2);
