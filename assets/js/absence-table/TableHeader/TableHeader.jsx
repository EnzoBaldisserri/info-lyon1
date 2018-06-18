import React, { Fragment } from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import MonthsRow from './MonthsRow';
import DaysRow from './DaysRow';

const TableHeader = () => (
  <thead>
    <AbsenceConsumer>
      { ({ months }) => {
        // day shape: [numberInMonth, { name, hash }]
        const days = months.reduce((carry, month) => ([
          ...carry,
          ...Object.entries(month.days),
        ]), []);

        return (
          <Fragment>
            <MonthsRow months={months} />
            <DaysRow days={days} number />
            <DaysRow days={days} />
          </Fragment>
        );
      }}
    </AbsenceConsumer>
  </thead>
);

export default TableHeader;
