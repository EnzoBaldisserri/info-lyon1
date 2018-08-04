import React, { Fragment } from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import MonthsRow from './MonthsRow';
import DaysRow from './DaysRow';

const TableHeader = () => (
  <thead>
    <AbsenceConsumer>
      {({ dataHolder: { period: { months, daysAsArray } } }) => (
        <Fragment>
          <MonthsRow months={months} />
          <DaysRow days={daysAsArray} number />
          <DaysRow days={daysAsArray} />
        </Fragment>
      )}
    </AbsenceConsumer>
  </thead>
);

export default TableHeader;
