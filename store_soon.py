def create_store_soon(root, head, header, footer):
    header = header.replace('<a href="/#metodo">Cómo trabajamos</a>', '<a href="/soluciones/monitoreo-24-7/">Monitoreo 24/7</a>')
    page = root / 'tienda'
    page.mkdir(exist_ok=True)
    body = '''<main id="main"><section class="store-soon wrap"><div class="store-soon-copy"><span class="eyebrow">Liconex / Tienda online</span><span class="store-soon-label">Próximamente</span><h1>Tu próximo equipo.<br><em>Preparado para vos.</em></h1><p>Estamos preparando la tienda de Liconex: equipos de seguridad electrónica y kits armados y preconfigurados por nosotros, con una guía para acompañarte en la instalación.</p><div class="store-soon-features"><span>Equipos seleccionados</span><span>Kits preconfigurados</span><span>Guías de instalación</span></div><a class="btn" href="https://wa.me/59899204188" target="_blank" rel="noopener noreferrer" data-contact-placement="store_soon">Consultanos por WhatsApp <span aria-hidden="true">↗</span></a><a class="text-link" href="/">Volver a Liconex <span aria-hidden="true">↗</span></a></div><div class="store-soon-photo"><img src="/assets/residence.jpg" alt="Residencia contemporánea rodeada de vegetación" width="1400" height="930"><span>La seguridad también empieza con una buena elección.</span></div></section></main>'''
    page.joinpath('index.html').write_text(head('Tienda Liconex | Próximamente', 'Próximamente: equipos de seguridad y kits preconfigurados Liconex con guía de instalación. Uruguay.', '/tienda/') + header + body + footer, encoding='utf-8')
    for path in root.rglob('*.html'):
        html = path.read_text(encoding='utf-8')
        html = html.replace('<a href="/profesionales/">Profesionales</a></nav>', '<a href="/profesionales/">Profesionales</a><a href="https://tienda.liconex.uy/"' + (' aria-current="page"' if path == page / 'index.html' else '') + '>Tienda</a></nav>')
        html = html.replace('<a href="/privacidad/">Privacidad</a></nav>', '<a href="https://tienda.liconex.uy/">Tienda online</a><a href="/privacidad/">Privacidad</a></nav>')
        path.write_text(html, encoding='utf-8')
    return '/tienda/'
