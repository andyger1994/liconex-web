<?php defined('ABSPATH') || exit; ?>
<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head><body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#main">Ir al contenido</a>
<div class="topbar"><div class="wrap"><span>Envío gratis en Montevideo en compras mayores a USD 100</span><a href="tel:+59899204188">Asesoramiento: 099 204 188</a></div></div>
<header class="market-header"><div class="wrap market-top">
<a class="market-logo" href="<?php echo esc_url(home_url('/shop/')); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/liconex-logo.png'); ?>" alt="Liconex · Seguridad electrónica"></a>
<form class="market-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"><label class="screen-reader-text" for="market-search">Buscar equipos</label><input id="market-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Buscá cámaras, alarmas, modelos y más…"><input type="hidden" name="post_type" value="product"><button type="submit" aria-label="Buscar productos">Buscar ↗</button></form>
<?php if (function_exists('wc_get_cart_url')) : ?><a class="market-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>">Carrito <b><?php echo WC()->cart ? esc_html(WC()->cart->get_cart_contents_count()) : '0'; ?></b></a><?php endif; ?>
</div><nav class="wrap market-nav" aria-label="Principal"><a href="<?php echo esc_url(home_url('/product-category/kits-con-instalacion/')); ?>">Kits con instalación</a><a href="<?php echo esc_url(home_url('/shop/')); ?>">Todos los productos</a><a href="<?php echo esc_url(home_url('/product-category/videovigilancia/')); ?>">Cámaras y video</a><a href="<?php echo esc_url(home_url('/product-category/alarmas-y-sensores/')); ?>">Alarmas</a><a href="<?php echo esc_url(home_url('/product-category/control-de-acceso/')); ?>">Control de acceso</a><a href="https://liconex.uy/">Soluciones Liconex ↗</a></nav></header>
<?php if (function_exists('is_shop') && is_shop() && !is_search()) : ?>
<?php liconex_featured_installed_kits(); ?>
<?php endif; ?>
