import 'materialize-css/dist/js/materialize';
import axios from 'axios';
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
      cancel: Translator.trans('global.button.cancel'),
      clear: Translator.trans('global.button.clear'),
      done: Translator.trans('global.button.done'),
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
      cancel: Translator.trans('global.button.cancel'),
      clear: Translator.trans('global.button.clear'),
      done: Translator.trans('global.button.done'),
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

  // Flash notifications

  // Notifications are always defined directly in base.html.twig
  // eslint-disable-next-line no-undef
  notifications.forEach((notification) => {
    const contentHtml = `<i class="material-icons">${notification.icon}</i><span>${notification.content}</span>`;
    const html = notification.link ?
      `<a href="${notification.link}">${contentHtml}</a>`
      : contentHtml;

    const displayLength = notification.type === 'error' ? Infinity : 4000;

    const classes = `toast ${notification.type}`;

    M.toast({ html, displayLength, classes });
  });

  // Persistent notifications
  const $header = document.querySelector('header');

  const updateNotificationWrappers = () => {
    const $navbarNotif = document.getElementById('navbar-notifications');
    const $mobileNotif = document.getElementById('mobile-notifications');

    const nbNotif = $navbarNotif.children.length - 1; // remove .notif-clear
    const empty = nbNotif === 0;

    $header.querySelectorAll('.notif-badge')
      .forEach(($el) => {
        if (empty) {
          // sibling is notification icon
          $el.previousElementSibling.textContent = 'notifications_none';
          $el.remove();
        } else {
          $el.textContent = nbNotif.toString();
        }
      });

    if (empty) {
      $header.querySelectorAll('.notif-clear')
        .forEach($el => $el.remove());

      $navbarNotif.insertAdjacentHTML(
        'afterbegin',
        `<li class="valign-wrapper notif pointer-events-none">
            <i class="material-icons">done</i>
            ${Translator.trans('notification.empty')}
        </li>`,
      );
      $mobileNotif.querySelector('.notification-wrapper').insertAdjacentHTML(
        'afterbegin',
        `<div class="collection-item valign-wrapper notif pointer-events-none">
            <i class="material-icons">done</i>
            ${Translator.trans('notification.empty')}
        </div>`,
      );
    }
  };

  const catchError = error => M.toast({
    html: `<i class="material-icons">report</i> ${error.message}`,
    classes: 'error valign-wrapper',
  });

  $header.addEventListener('click', (e) => {
    const $notif = e.target.closest('.notif');
    if ($notif !== null) {
      // We are in a notification or a notif-clear
      const id = $notif.getAttribute('data-id');
      if (id !== null) {
        axios.delete(Routing.generate('api_notification_delete', { id }))
          .then(response => response.data)
          .then((response) => {
            if (response.error) {
              throw new Error(response.error);
            }

            document.querySelectorAll(`.notif[data-id="${id}"]`)
              .forEach($el => $el.remove());

            if ($notif.hasAttribute('data-link')) {
              window.location.href = $notif.getAttribute('data-link');
            }
          })
          .then(updateNotificationWrappers)
          .catch(catchError);
      } else if ($notif.classList.contains('notif-clear')) {
        axios.delete(Routing.generate('api_notification_clear'))
          .then(response => response.data)
          .then((response) => {
            if (response.error) {
              throw new Error(response.error);
            }

            document.querySelectorAll('.notif:not(.notif-clear)')
              .forEach($el => $el.remove());
          })
          .then(updateNotificationWrappers)
          .catch(catchError);
      }
    }
  });
});
