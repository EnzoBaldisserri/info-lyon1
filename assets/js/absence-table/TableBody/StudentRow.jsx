import React from 'react';
import PropTypes from 'prop-types';

import { AbsenceConsumer } from '../AbsenceContext';
import AbsenceDay from './AbsenceDay';

const StudentRow = (props) => {
  const {
    student,
    ...restProps
  } = props;

  return (
    <tr {...restProps}>
      <AbsenceConsumer>
        { ({ months, actions: { openEditor } }) =>
          months.map(({ days }) => Object.values(days).map((day) => {
            const absences = student.absences
              .filter(absence => absence.start_time.slice(0, 10) === day.repr);

            return (
              <AbsenceDay
                absences={absences}
                openEditor={openEditor(student, new Date(day.repr), absences)}
                key={day.repr}
              />
            );
          })) }
      </AbsenceConsumer>
    </tr>
  );
};

StudentRow.propTypes = {
  student: PropTypes.shape({
    absences: PropTypes.arrayOf(PropTypes.any),
  }).isRequired,
};

export default StudentRow;
