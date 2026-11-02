(() => {
  const emit = (name, data) => { if (typeof window.gtag === 'function') window.gtag('event', name, data); };
  for (const event of window.lxCommerceEvents || []) {
    if (event.name === 'purchase') {
      const key = `lx-purchase-${event.data.transaction_id}`;
      try { if (sessionStorage.getItem(key)) continue; sessionStorage.setItem(key, '1'); } catch (_) { /* GA deduplicates transaction_id too. */ }
    }
    emit(event.name, event.data);
  }
  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]'); if (!link) return;
    const href = link.getAttribute('href');
    if (/^https:\/\/(wa\.me|api\.whatsapp\.com)\//i.test(href)) emit('whatsapp_click', {contact_method:'whatsapp', contact_placement:link.closest('footer')?'footer':'store'});
    if (/^tel:/i.test(href)) emit('phone_click', {contact_method:'phone', contact_placement:'store'});
  });
  // Classic Woo AJAX confirms success before this event; do not count request clicks.
  if (window.jQuery) window.jQuery(document.body).on('added_to_cart', (_event, _fragments, _hash, button) => {
    const id = button?.attr('data-product_id');
    if (id) emit('add_to_cart', {currency:'USD',items:[{item_id:String(id),quantity:Number(button.attr('data-quantity'))||1}]});
  });
})();
