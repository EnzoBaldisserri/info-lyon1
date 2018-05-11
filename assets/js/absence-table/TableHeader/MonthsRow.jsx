import React from 'react';
import PropTypes from 'prop-types';

const MonthsRow = props => (
  <tr>
    { props.months.map(month => (
      <th colSpan={Object.values(month.days).length} key={month.hash}>
        {month.name}
      </th>
    )) }
  </tr>
);

MonthsRow.propTypes = {
  months: PropTypes.arrayOf(PropTypes.shape({
    name: PropTypes.string.isRequired,
    days: PropTypes.object.isRequired,
    hash: PropTypes.any.isRequired,
  })).isRequired,
};

export default MonthsRow;
