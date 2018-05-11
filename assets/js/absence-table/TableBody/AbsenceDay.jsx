import React from 'react';
import PropTypes from 'prop-types';

const isJustified = absences => absences.reduce(
  (carry, absence) => carry && absence.justified,
  true,
);

const getType = absences => absences.reduce(
  (carry, absence) => ((carry === null || carry === absence.type.name) ? absence.type.name : 'mixed'),
  null,
);

const getClass = (absences) => {
  if (absences.length === 0) {
    return null;
  }

  const typeClass = isJustified(absences) ?
    'abs-justified'
    : `abs-${getType(absences)}`;

  return `abs ${typeClass}`;
};

const AbsenceDay = props => (
  <td className={getClass(props.absences)} />
);

AbsenceDay.propTypes = {
  absences: PropTypes.arrayOf(PropTypes.shape({
    type: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }),
  })).isRequired,
};

export default AbsenceDay;
