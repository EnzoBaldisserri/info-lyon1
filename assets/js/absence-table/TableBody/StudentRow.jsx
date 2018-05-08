import React from 'react';
import PropTypes from 'prop-types';

import AbsenceDay from './AbsenceDay';

const StudentRow = (props) => {
  const { student, ...restProps } = props;

  const absences = student.absences.map(absence => (
    <AbsenceDay absences={[absence]} />
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
