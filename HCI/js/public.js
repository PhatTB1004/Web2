document.addEventListener('DOMContentLoaded', () => {
  const autoSubmit = document.querySelectorAll('[data-auto-submit]');
  autoSubmit.forEach((el) => {
    el.addEventListener('change', () => {
      const form = el.closest('form');
      if (form) form.submit();
    });
  });
});
