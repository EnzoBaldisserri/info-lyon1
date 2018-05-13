import React from 'react';
import ReactDOM from 'react-dom';

import AbsenceTable from './AbsenceTable';

import '../../scss/secretariat/absence.scss';

const $absenceTable = document.getElementById('absence-table');
const i18n = JSON.parse($absenceTable.getAttribute('data-i18n'));

ReactDOM.render(
  // eslint-disable-next-line react/jsx-filename-extension
  <AbsenceTable i18n={i18n} />,
  $absenceTable,
);
