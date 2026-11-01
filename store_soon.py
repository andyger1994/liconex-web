def create_store_soon(root, head, header, footer):
    header = header.replace('<a href="/#metodo">Cómo trabajamos</a>', '<a href="/soluciones/monitoreo-24-7/">Monitoreo 24/7</a>')
    page = root / 'tienda'
    page.mkdir(exist_ok=True)
    body = '<main id="main" class="wrap"><h1>Tienda Liconex</h1><p>La tienda ya está disponible.</p><a class="btn" href="https://tienda.liconex.uy/">Entrar a la tienda</a></main>'
    redirect = '<meta http-equiv="refresh" content="0;url=https://tienda.liconex.uy/">'
    page.joinpath('index.html').write_text(head('Tienda Liconex', 'Equipos de seguridad electrónica Liconex.', '/tienda/').replace('</head>', redirect + '</head>') + header + body + footer, encoding='utf-8')
    for path in root.rglob('*.html'):
        html = path.read_text(encoding='utf-8')
        html = html.replace('<a href="/profesionales/">Profesionales</a></nav>', '<a href="/profesionales/">Profesionales</a><a href="https://tienda.liconex.uy/"' + (' aria-current="page"' if path == page / 'index.html' else '') + '>Tienda</a></nav>')
        html = html.replace('<a href="/privacidad/">Privacidad</a></nav>', '<a href="https://tienda.liconex.uy/">Tienda online</a><a href="/privacidad/">Privacidad</a></nav>')
        path.write_text(html, encoding='utf-8')
    return '/tienda/'
