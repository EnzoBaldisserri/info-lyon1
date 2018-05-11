import React from 'react';
import PropTypes from 'prop-types';

import MonthsRow from './MonthsRow';
import DaysRow from './DaysRow';

const TableHeader = (props) => {
  const { months } = props;

  // day shape: [numberInMonth, { name, hash }]
  const days = months.reduce((carry, month) => ([
    ...carry,
    ...Object.entries(month.days),
  ]), []);

  return (
    <thead>
      <MonthsRow months={months} />
      <DaysRow days={days} number />
      <DaysRow days={days} />
    </thead>
  );
};

TableHeader.propTypes = {
  months: PropTypes.arrayOf(PropTypes.shape({
    name: PropTypes.string.isRequired,
  })).isRequired,
};

export default TableHeader;
