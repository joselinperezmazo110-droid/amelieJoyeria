document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-checkout]').forEach((button) => {
    button.addEventListener('click', () => {
      window.alert('La demostración está lista. Conecta aquí tu pasarela de pago.');
    });
  });
  document.querySelectorAll('.notice').forEach((notice) => {
    window.setTimeout(() => notice.remove(), 5000);
  });
});