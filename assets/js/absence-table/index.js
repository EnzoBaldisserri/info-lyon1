import React from 'react';
import ReactDOM from 'react-dom';

import AbsenceTable from './AbsenceTable';
import '../../scss/secretariat/absence.scss';

const $absenceTable = document.getElementById('absence-table');

ReactDOM.render(
  // eslint-disable-next-line react/jsx-filename-extension
  <AbsenceTable />,
  $absenceTable,
);
