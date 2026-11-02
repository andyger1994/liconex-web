def create_store_soon(root, head, header, footer):
    header = header.replace('<a href="/#metodo">Cómo trabajamos</a>', '<a href="/soluciones/monitoreo-24-7/">Monitoreo 24/7</a>')
    page = root / 'tienda'
    page.mkdir(exist_ok=True)
    page.joinpath('index.html').write_text('<!doctype html><html lang="es"><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=https://tienda.liconex.uy/"><title>Tienda Liconex</title><link rel="canonical" href="https://tienda.liconex.uy/"></head><body><p><a href="https://tienda.liconex.uy/">Entrar a la tienda Liconex</a></p></body></html>', encoding='utf-8')
    for path in root.rglob('*.html'):
        html = path.read_text(encoding='utf-8')
        html = html.replace('<a href="/profesionales/">Profesionales</a></nav>', '<a href="/profesionales/">Profesionales</a><a href="https://tienda.liconex.uy/"' + (' aria-current="page"' if path == page / 'index.html' else '') + '>Tienda</a></nav>')
        html = html.replace('<a href="/privacidad/">Privacidad</a></nav>', '<a href="https://tienda.liconex.uy/">Tienda online</a><a href="/privacidad/">Privacidad</a></nav>')
        path.write_text(html, encoding='utf-8')
    return '/tienda/'
