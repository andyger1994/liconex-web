(() => {
  const update = () => {
    document.querySelectorAll('.wc-block-components-radio-control__option, .wc-block-components-totals-item').forEach(row => {
      const label = row.querySelector('.wc-block-components-radio-control__label, .wc-block-components-totals-item__label');
      if (!label || !label.textContent.startsWith('Encomienda —')) return;
      const cost = row.querySelector('.wc-block-checkout__shipping-option--free, .wc-block-components-totals-item__value strong');
      if (cost && cost.textContent !== 'A pagar al recibir') cost.textContent = 'A pagar al recibir';
    });
  };
  update();
  new MutationObserver(update).observe(document.body, {subtree: true, childList: true, characterData: true});
})();
