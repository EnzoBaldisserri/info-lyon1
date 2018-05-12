import React from 'react';
import PropTypes from 'prop-types';

import AbsenceDay from './AbsenceDay';

const StudentRow = (props) => {
  const {
    i18n,
    student,
    ...restProps
  } = props;

  const absences = student.absences.map(day => (
    <AbsenceDay
      i18n={i18n}
      absences={day.absences}
      key={day.hash}
    />
  ));

  return (
    <tr {...restProps}>
      { absences }
    </tr>
  );
};

StudentRow.propTypes = {
  i18n: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  student: PropTypes.shape({
    absences: PropTypes.arrayOf(PropTypes.any),
  }).isRequired,
};

export default StudentRow;
