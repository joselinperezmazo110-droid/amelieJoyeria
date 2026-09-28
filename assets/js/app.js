document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.notice').forEach((notice) => {
    window.setTimeout(() => notice.remove(), 5000);
  });
});