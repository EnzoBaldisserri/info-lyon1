import React from 'react';

import AbsenceContext from '../AbsenceContext';
import StudentRow from './StudentRow';

const TableBody = () => (
  <tbody>
    <AbsenceContext.Consumer>
      { ({ groups }) => groups.map(group =>
        group.students.map((student, index) => (
          <StudentRow
            className={index === 0 ? 'new-group' : null}
            student={student}
            key={student.id}
          />
        ))) }
    </AbsenceContext.Consumer>
  </tbody>
);

export default TableBody;
