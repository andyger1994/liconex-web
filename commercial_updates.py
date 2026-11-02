"""Keep the main site's commercial improvements reproducible."""
import re


def apply_commercial_updates(root):
    for path in root.rglob('*.html'):
        html = path.read_text(encoding='utf-8')
        # WhatsApp is the reply channel; email and project stage are optional.
        html = re.sub(r'(<input\b[^>]*name="email"[^>]*?)\srequired', r'\1', html)
        html = re.sub(r'(<select\b[^>]*name="stage"[^>]*?)\srequired', r'\1', html)
        html = html.replace('Correo de contacto<input', 'Correo de contacto (opcional)<input')
        if path == root / 'index.html':
            block = '''<section class="section purchase-paths" aria-labelledby="purchase-paths-title"><div class="wrap"><span class="eyebrow">Elegí cómo empezar</span><h2 id="purchase-paths-title">Una solución para tu espacio.</h2><div class="purchase-paths-grid"><article><span class="eyebrow">Proyecto a medida</span><h3>Diseñamos alrededor de lo que necesitás proteger.</h3><p>Residencias, empresas, edificios e industria. Relevamiento, ingeniería e instalación con alcance acordado.</p><a class="btn" href="#contacto">Conversar sobre mi proyecto ↗</a></article><article><span class="eyebrow">Kits con instalación</span><h3>Tu alarma, lista para usar.</h3><p>Compará tres kits con 4 sensores de movimiento y 2 de apertura. Instalación incluida en Montevideo y Canelones, dentro de 7 días hábiles según demanda.</p><a class="btn" href="https://tienda.liconex.uy/product-category/kits-con-instalacion/">Comparar kits y precios ↗</a></article></div><p class="commercial-guarantee">Productos e instalaciones con garantía firmada de 2 años. <a href="https://tienda.liconex.uy/garantia-y-devoluciones/">Conocé el respaldo de Liconex ↗</a></p></div></section>'''
            html = html.replace('<section class="contact" id="contacto">', block+'<section class="contact" id="contacto">')
        if path.parent.name == 'privacidad':
            html = html.replace('<h2>Contacto</h2>', '<h2>Compras en la tienda</h2><p>La tienda gestiona datos de pedidos, entregas y pagos. Consultá su <a href="https://tienda.liconex.uy/privacidad-tienda/">política de privacidad</a> y las <a href="https://tienda.liconex.uy/condiciones-de-compra/">condiciones de compra</a>.</p><h2>Contacto</h2>')
        path.write_text(html, encoding='utf-8')
