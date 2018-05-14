import React from 'react';
import PropTypes from 'prop-types';

import AbsenceDay from './AbsenceDay';

const StudentRow = (props) => {
  const {
    student,
    ...restProps
  } = props;

  const absences = student.absences.map(day => (
    <AbsenceDay
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
  student: PropTypes.shape({
    absences: PropTypes.arrayOf(PropTypes.any),
  }).isRequired,
};

export default StudentRow;
