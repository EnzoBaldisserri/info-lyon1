import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

import { AbsenceConsumer } from '../AbsenceContext';
import AbsenceDay from './AbsenceDay';
import Student from '../Model/Student';

class StudentRow extends PureComponent {
  static propTypes = {
    student: PropTypes.instanceOf(Student).isRequired,
  };

  render() {
    const {
      student,
      ...restProps
    } = this.props;

    return (
      <tr {...restProps}>
        <AbsenceConsumer>
          { ({ dataHolder: { period: { daysAsArray } }, edit }) => daysAsArray.map((day) => {
              const studentDay = day.getAbsences(student);
              const dayOfWeek = studentDay.date.getDay();
              const onClick = dayOfWeek === 0 || dayOfWeek === 6
                ? null
                : edit(studentDay);

              return (
                <AbsenceDay
                  day={studentDay}
                  onClick={onClick}
                  key={day.hash}
                />
              );
            }) }
        </AbsenceConsumer>
      </tr>
    );
  }
}

export default StudentRow;
