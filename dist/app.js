const menu = document.querySelector('.menu');
menu?.addEventListener('click', () => { const open = menu.getAttribute('aria-expanded') !== 'true'; menu.setAttribute('aria-expanded', String(open)); document.querySelector('.navlinks').classList.toggle('open', open); menu.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú'); });
document.querySelectorAll('.navlinks a').forEach(a => a.addEventListener('click', () => { document.querySelector('.navlinks').classList.remove('open'); menu?.setAttribute('aria-expanded', 'false'); menu?.setAttribute('aria-label', 'Abrir menú'); }));
const sectors = [
 ['El control que tu operación necesita.','Oficinas, locales y espacios de trabajo tienen horarios, usuarios y zonas con necesidades diferentes. El proyecto debe contemplarlos desde el inicio.',['Visibilidad de accesos y áreas sensibles','Permisos según personas, sectores y horarios','Grabación y consulta de eventos'],'Empresas y oficinas'],
 ['Seguridad que acompaña al edificio.','Una solución para administraciones que necesitan ordenar ingresos, revisar eventos y facilitar el uso diario de los espacios comunes.',['Accesos peatonales y vehiculares','Videoportería y espacios compartidos','Gestión de credenciales y permisos'],'Edificios y desarrollos'],
 ['Una mirada completa sobre el predio.','En depósitos e instalaciones industriales, las distancias, el tránsito y las condiciones de iluminación definen el diseño de la protección.',['Cobertura de perímetros y puntos críticos','Registro de circulación y operaciones','Infraestructura preparada para crecer'],'Industria y logística'],
 ['Protección que respeta tu espacio.','Residencias y propiedades con necesidades específicas requieren una instalación discreta, manejo claro y atención a los detalles de la arquitectura.',['Cámaras ubicadas según cada entorno','Detección de intrusión por zonas','Acceso remoto y entrega de uso'],'Residencias']
];
const tabs = [...document.querySelectorAll('[role=tab]')];
const sectorPages = ['empresas','edificios','industria-y-logistica','residencias'];
const sectorImages = ['interior.jpg','architecture.jpg','logistics.jpg','residence.jpg'];
const sectorAlts = ['Oficinas con espacios de trabajo y circulación','Fachada de edificios contemporáneos','Estanterías de almacenamiento en una instalación logística','Residencia contemporánea con jardín y acceso exterior'];
const sectorLabels = ['Ver soluciones para empresas','Ver soluciones para edificios','Ver soluciones para industria y logística','Ver soluciones para residencias'];
function selectSector(i, focus = false) { tabs.forEach((t,j) => {t.setAttribute('aria-selected',String(i===j));t.tabIndex=i===j?0:-1;}); const item=sectors[i];document.getElementById('sector-title').textContent=item[0];document.getElementById('sector-desc').textContent=item[1];document.getElementById('sector-list').replaceChildren(...item[2].map(text=>{const li=document.createElement('li');li.textContent=text;return li;}));const panel=document.getElementById('sector-panel');panel.setAttribute('aria-labelledby',tabs[i].id);panel.classList.remove('fade');void panel.offsetWidth;panel.classList.add('fade');if(focus)tabs[i].focus(); }
function updateSector(i, focus = false) { selectSector(i, focus); const link=document.getElementById('sector-detail');if(link){link.href='/sectores/'+sectorPages[i]+'/';link.textContent=sectorLabels[i]+' ↗';}const image=document.querySelector('#sector-panel img');if(image){image.src='/assets/'+sectorImages[i];image.alt=sectorAlts[i];}}
tabs.forEach((t,i)=>{t.addEventListener('click',()=>updateSector(i));t.addEventListener('keydown',e=>{let next;if(e.key==='ArrowRight')next=(i+1)%tabs.length;if(e.key==='ArrowLeft')next=(i+tabs.length-1)%tabs.length;if(e.key==='Home')next=0;if(e.key==='End')next=tabs.length-1;if(next!==undefined){e.preventDefault();updateSector(next,true);}});});
const segmentField = document.querySelector('select[name="segment"]');
const requestedSegment = new URLSearchParams(window.location.search).get('segment');
if(segmentField && [...segmentField.options].some(option=>option.value===requestedSegment)){segmentField.value=requestedSegment;}
const solutionField=document.querySelector('select[name="solution"]');
if(solutionField && new URLSearchParams(window.location.search).get('solution')==='monitoreo'){solutionField.value='Monitoreo 24/7';}
function trackContact(method, placement) {
  if (typeof window.gtag !== 'function') return;
  window.gtag('event', method === 'whatsapp' ? 'whatsapp_click' : 'phone_click', {
    send_to: 'G-SGQX234Y86',
    contact_method: method,
    contact_placement: placement,
    transport_type: 'beacon'
  });
}

document.addEventListener('click', event => {
  const link = event.target.closest?.('a[href]');
  if (!link || link.id === 'whatsapp-link') return;
  const href = link.getAttribute('href');
  if (href.startsWith('tel:')) {
    trackContact('phone', link.closest('.topbar') ? 'header' : 'contact');
  } else if (/^https:\/\/(wa\.me|api\.whatsapp\.com)\//.test(href)) {
    trackContact('whatsapp', link.dataset.contactPlacement || 'link');
  }
});

// Keep the message out of hyperlink URLs so automatic outbound-click measurement
// cannot collect names, email addresses or the form's free text.
let pendingWhatsAppUrl = '';
const whatsappFallback = document.getElementById('whatsapp-link');
whatsappFallback?.addEventListener('click', event => {
  event.preventDefault();
  if (pendingWhatsAppUrl) window.open(pendingWhatsAppUrl, '_blank', 'noopener,noreferrer');
});

document.getElementById('project-form')?.addEventListener('submit', event => {
  event.preventDefault();
  const form = event.currentTarget;
  if (!form.reportValidity()) return;
  const data = new FormData(form);
  const message = `Hola, soy ${data.get('name')} de ${data.get('company') || 'un proyecto particular'}.\nTipo de espacio: ${segmentField?.selectedOptions[0]?.textContent || 'A definir'}.\nEtapa: ${data.get('stage')}.\nQuiero consultar por ${data.get('solution')}.\nUbicación: ${data.get('location')}.\nContacto: ${data.get('email')}.\n${data.get('message') || ''}`;
  pendingWhatsAppUrl = 'https://wa.me/59899204188?text=' + encodeURIComponent(message);
  whatsappFallback.href = 'https://wa.me/59899204188';
  whatsappFallback.hidden = false;
  document.getElementById('form-status').textContent = 'Tu consulta está preparada. Abrí WhatsApp para revisarla y enviarla.';
  trackContact('whatsapp', 'project_form');
  window.open(pendingWhatsAppUrl, '_blank', 'noopener,noreferrer');
});
