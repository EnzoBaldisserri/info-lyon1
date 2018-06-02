import Translator from 'bazinga-translator';
import Routing from '../../public/bundles/fosjsrouting/js/router.min';
import routes from '../../public/bundles/fos_js_routes.json';

import '../scss/app.scss';

Routing.setRoutingData(routes);
window.Routing = Routing;
window.Translator = Translator;

function parseDateString(dateString) {
  const format = Translator.trans('global.form.datetype.format');
  const rules = {
    d: (date, string) => date.setDate(+string),
    dd: (date, string) => date.setDate(+string),
    m: (date, string) => date.setMonth(+string - 1),
    mm: (date, string) => date.setMonth(+string - 1),
    yy: (date, string) => {
      const shortYear = +string;
      const currentYear = new Date().getFullYear();
      const shortCurrentYear = +(currentYear.toString().slice(-2));
      const currentCentury = Math.round(currentYear / 100) * 100;

      date.setFullYear(shortYear <= shortCurrentYear ?
        currentCentury + shortYear
        : (currentCentury - 100) + shortYear);
    },
    yyyy: (date, string) => date.setFullYear(+string),
  };

  const date = new Date();

  Object.entries(rules).forEach(([identifier, rule]) => {
    const regex = new RegExp(`(?<![dmy])${identifier}(?![dmy])`, 'g');

    let result = regex.exec(format);
    while (result !== null) {
      const value = dateString.substr(result.index, identifier.length);
      rule(date, value);

      result = regex.exec(format);
    }
  });

  return date;
}

function initializeDatePickers($pickers) {
  const defaults = {
    autoClose: true,
    showClearButton: true,
    firstDay: Translator.trans('global.time.first_day'),
    format: Translator.trans('global.form.datetype.format'),
    parse: dateString => parseDateString(dateString),
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
  };
  const options = [
    ['defaultDate', defaults.parse],
    ['setDefaultDate', string => string === 'true'],
    ['minDate', defaults.parse],
    ['maxDate', defaults.parse],
  ];

  $pickers.forEach(($picker) => {
    const pickerOptions = options
      // Get picker options value
      .map(([option, convert]) => [option, convert, $picker.getAttribute(`data-${option}`)])
      // Filter when there's no value
      .filter(([,, value]) => value !== null && value !== '')
      // Associate option with final value
      .map(([option, convert, value]) => [option, convert ? convert(value) : value])
      // Make an object of the remaining options
      .reduce((object, [option, value]) => ({
        [option]: value,
        ...object,
      }), {});

    M.Datepicker.init($picker, Object.assign(pickerOptions, defaults));
  });
}

function initializeTimePickers($pickers) {
  const defaults = {
    autoClose: true,
    twelveHour: Translator.trans('global.time.twelve_hour'),
    i18n: {
      cancel: Translator.trans('global.message.cancel'),
      clear: Translator.trans('global.message.clear'),
      done: Translator.trans('global.message.done'),
    },
  };
  const options = [
    ['defaultTime', string => string.split(':').slice(0, 2).join(':')],
  ];

  $pickers.forEach(($picker) => {
    const pickerOptions = options
      // Get picker options value
      .map(([option, convert]) => [option, convert, $picker.getAttribute(`data-${option}`)])
      // Filter when there's no value
      .filter(([,, value]) => value === null || value === '')
      // Associate option with final value
      .map(([option, convert, value]) => [option, convert ? convert(value) : value])
      // Make an object of the remaining options
      .reduce((object, [option, value]) => ({
        [option]: value,
        ...object,
      }), {});

    M.Timepicker.init($picker, Object.assign(pickerOptions, defaults));
  });
}

window.addEventListener('DOMContentLoaded', () => {
  // Initialize specific mobile sidenav
  M.Sidenav.init(document.getElementById('mobile-sidenav'));

  // Initialize components
  M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'), {
    constrainWidth: false,
    coverTrigger: false,
  });

  M.FormSelect.init(document.querySelectorAll('select'));
  initializeDatePickers(document.querySelectorAll('.datepicker'));
  initializeTimePickers(document.querySelectorAll('.timepicker'));

  M.Modal.init(document.querySelectorAll('.modal'));
});
