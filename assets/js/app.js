import Translator from 'bazinga-translator';
import Routing from '../../public/bundles/fosjsrouting/js/router.min';
import routes from '../../public/bundles/fos_js_routes.json';

import '../scss/app.scss';

Routing.setRoutingData(routes);
window.Routing = Routing;
window.Translator = Translator;

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

  M.FormSelect.init(document.querySelectorAll('select'));
  M.Datepicker.init(document.querySelectorAll('.datepicker'), {
    autoClose: true,
    format: Translator.trans('global.date.format'),
    i18n: {
      cancel: Translator.trans('global.message.cancel'),
      clear: Translator.trans('global.message.clear'),
      done: Translator.trans('global.message.done'),
      months: Translator.trans('global.time.months').split(','),
      monthsShort: Translator.trans('global.time.months_short').split(','),
      weekdays: Translator.trans('global.time.weekdays').split(','),
      weekdaysShort: Translator.trans('global.time.weekdays_short').split(','),
      weekdaysAbbrev: Translator.trans('global.time.weekdays_abbrev').split(','),
    },
  });
  M.Timepicker.init(document.querySelectorAll('.timepicker'), {
    autoClose: true,
    twelveHour: false,
    i18n: {
      cancel: Translator.trans('global.message.cancel'),
      clear: Translator.trans('global.message.clear'),
      done: Translator.trans('global.message.done'),
    },
  });

  // Form confirmation
  // eslint-disable-next-line no-alert
  const confirm = e => window.confirm(e.target.getAttribute('data-confirm'));

  document.querySelectorAll('a[data-confirm]')
    .forEach($anchor => $anchor.addEventListener('click', confirm));

  document.querySelectorAll('form[data-confirm]')
    .forEach($form => $form.addEventListener('submit', confirm));
});
