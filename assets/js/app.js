import M from '../../node_modules/materialize-css/dist/js/materialize';
import '../scss/app.scss';

window.addEventListener('load', () => {
  M.Dropdown.init(
    document.getElementById('navbar-user-trigger'),
    {
      alignment: 'right',
      constrainWidth: false,
      coverTrigger: false,
    },
  );

  M.Sidenav.init(document.getElementById('mobile-sidenav'));
});
