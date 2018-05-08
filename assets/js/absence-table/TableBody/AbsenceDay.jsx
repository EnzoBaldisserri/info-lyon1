import React from 'react';
import PropTypes from 'prop-types';

const computeClass = abs1 => `abs abs-${abs1.type.class}`;

const AbsenceDay = props => (
  <td className={computeClass(props.absences[0])} />
);

AbsenceDay.propTypes = {
  absences: PropTypes.arrayOf(PropTypes.shape({
    type: PropTypes.any,
  })).isRequired,
};

export default AbsenceDay;
