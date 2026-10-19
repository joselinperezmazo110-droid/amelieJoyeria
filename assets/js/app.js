document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.notice').forEach((notice) => {
    window.setTimeout(() => notice.remove(), 5000);
  });

  const roleInputs = document.querySelectorAll('input[name="rol"]');
  const adminCodeField = document.querySelector('.admin-code-field');
  const adminCodeInput = adminCodeField?.querySelector('input');

  if (roleInputs.length && adminCodeField && adminCodeInput) {
    const updateAdminCodeField = () => {
      const isAdmin = document.querySelector('input[name="rol"]:checked')?.value === 'admin';
      adminCodeField.hidden = !isAdmin;
      adminCodeInput.required = isAdmin;
    };

    roleInputs.forEach((input) => input.addEventListener('change', updateAdminCodeField));
    updateAdminCodeField();
  }
});