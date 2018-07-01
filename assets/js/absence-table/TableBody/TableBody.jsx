import React, { PureComponent } from 'react';

import { AbsenceConsumer } from '../AbsenceContext';
import StudentRow from './StudentRow';

// eslint-disable-next-line react/prefer-stateless-function
class TableBody extends PureComponent {
  render() {
    return (
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
  }
}

export default TableBody;
