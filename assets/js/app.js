import '../scss/app.scss';

window.addEventListener('load', () => {
  // Navigation
  M.Dropdown.init(
    document.getElementById('navbar-user-trigger'),
    {
      alignment: 'right',
      constrainWidth: false,
      coverTrigger: false,
    },
  );

  M.Sidenav.init(document.getElementById('mobile-sidenav'));

  // Form confirmation
  // eslint-disable-next-line no-alert
  const confirm = e => window.confirm(e.target.getAttribute('data-confirm'));

  document.querySelectorAll('a[data-confirm]')
    .forEach($anchor => $anchor.addEventListener('click', confirm));

  document.querySelectorAll('form[data-confirm]')
    .forEach($form => $form.addEventListener('submit', confirm));
});
