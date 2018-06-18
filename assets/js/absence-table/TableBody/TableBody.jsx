import React from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import StudentRow from './StudentRow';

const TableBody = () => (
  <tbody>
    <AbsenceConsumer>
      { ({ groups }) => groups.map(group =>
        group.students.map((student, index) => (
          <StudentRow
            className={index === 0 ? 'new-group' : null}
            student={student}
            key={student.id}
          />
        ))) }
    </AbsenceConsumer>
  </tbody>
);

export default TableBody;
