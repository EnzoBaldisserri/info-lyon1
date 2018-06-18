import React from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import StudentRow from './StudentRow';

const StudentsCol = () => (
  <div className="students">
    <AbsenceConsumer>
      { ({ groups }) => groups.map(group => (
        <div className="group" key={group.id}>
          { group.students.map(student => <StudentRow student={student} key={student.id} />) }
        </div>
      )) }
    </AbsenceConsumer>
  </div>
);

export default StudentsCol;
