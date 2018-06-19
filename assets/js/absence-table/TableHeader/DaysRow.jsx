import React from 'react';
import PropTypes from 'prop-types';

const DaysRow = props => (
  <tr>
    { props.days.map(([dayInMonth, day]) => (
      <th key={day.repr}>{ props.number ? dayInMonth : day.name }</th>
    )) }
  </tr>
);

DaysRow.defaultProps = {
  number: false,
};

DaysRow.propTypes = {
  days: PropTypes.arrayOf(PropTypes.arrayOf(PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.shape({
      name: PropTypes.string,
      repr: PropTypes.string,
    }),
  ]))).isRequired,
  number: PropTypes.bool,
};

export default DaysRow;
