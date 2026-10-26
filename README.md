# Liconex — Web de seguridad electrónica

Sitio comercial para https://liconex.uy. Código estático independiente de la aplicación de gestión del repositorio `andyger1994/liconex`.

## Vercel

- Framework: Other.
- Output Directory: `dist`.
- No requiere instalación ni compilación en Vercel. Los HTML generados se incluyen en Git.
- `vercel.json` declara la configuración, las rutas con barra final y cabeceras básicas.
- Mantener el proyecto de Vercel y sus dominios actuales para reemplazar la versión pública sin borrar el historial de despliegues.
- Antes de promover a producción, comprobar inicio, las doce páginas, imágenes, menú móvil y enlaces de contacto en el despliegue de vista previa.

## Edición

1. Editar `build.py`, `segments.py`, `monitoring.py` o los recursos de `dist/`.
2. Ejecutar `python build.py` con Python 3.
3. Revisar el resultado con `python -m http.server 4173 --bind 127.0.0.1 --directory dist`.
4. Subir los cambios incluidos los HTML y recursos de `dist/`.

`ORIGIN` se fija en `https://liconex.uy`, utilizado por canonical, Open Graph, sitemap y datos estructurados. El sitio de revisión de Sites se conserva por separado.

## Alcance

Cinco soluciones: videovigilancia, control de acceso, alarmas/perímetros, integración/mantenimiento y monitoreo 24/7. Cuatro sectores: residencias, empresas, edificios e industria/logística. Incluye página para profesionales y privacidad.

La cobertura nacional, el servicio de monitoreo y la auditoría de instalaciones con los departamentos Comercial, Ingeniería e Instalaciones fueron confirmados por el propietario. No se afirman tiempos de respuesta, certificaciones ni marcas asociadas. Las fotografías son ilustrativas; no se presentan como instalaciones de Liconex.

El formulario prepara un mensaje para WhatsApp y el usuario confirma su envío allí. No hay base de datos del formulario. Google Analytics 4 integrado en todas las páginas con el ID G-SGQX234Y86; Google signals y personalización publicitaria deshabilitados en la etiqueta.

## Migración

La nueva web reemplaza los contenidos de la anterior. No incluye la calculadora de consumo ni la aplicación de gestión con usuarios. Conservar el despliegue anterior de Vercel como respaldo de recuperación.
`whatsapp_click` y `phone_click` miden intentos de contacto en GA4. No confirman mensajes enviados ni llamadas completadas. Para Google Ads falta vincular GA4 e importar las acciones correspondientes. Los eventos no incluyen contenido del formulario; el respaldo de WhatsApp no duplica el evento inicial.
