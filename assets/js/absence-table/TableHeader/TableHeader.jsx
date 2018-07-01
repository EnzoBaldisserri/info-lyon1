import React, { PureComponent, Fragment } from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import MonthsRow from './MonthsRow';
import DaysRow from './DaysRow';

// eslint-disable-next-line react/prefer-stateless-function
class TableHeader extends PureComponent {
  render() {
    return (
      <thead>
        <AbsenceConsumer>
          {({ months }) => {
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
  }
}

export default TableHeader;
