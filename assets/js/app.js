import Translator from 'bazinga-translator';
import Routing from '../../public/bundles/fosjsrouting/js/router.min';
import routes from '../../public/bundles/fos_js_routes.json';

import '../scss/app.scss';

Routing.setRoutingData(routes);
window.Routing = Routing;

window.addEventListener('DOMContentLoaded', () => {
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
