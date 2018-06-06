import React from 'react';

import AbsenceContext from '../AbsenceContext';
import StudentRow from './StudentRow';

const StudentsCol = () => (
  <div className="students">
    <AbsenceContext.Consumer>
      { ({ groups }) => groups.map(group => (
        <div className="group" key={group.id}>
          { group.students.map(student => <StudentRow student={student} key={student.id} />) }
        </div>
      )) }
    </AbsenceContext.Consumer>
  </div>
);

export default StudentsCol;
