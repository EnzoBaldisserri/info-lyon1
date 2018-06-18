import React from 'react';

import { AbsenceConsumer } from '../AbsenceContext';

const cellHeight = 26;
const GroupsCol = () => (
  <div className="groups">
    <AbsenceConsumer>
      { ({ groups }) => groups.map(group => (
        <div
          key={group.id}
          className="group"
          style={{ height: `${(group.students.length * cellHeight) + 1}px` }}
        >
          {group.fullname}
        </div>
      )) }
    </AbsenceConsumer>
  </div>
);

export default GroupsCol;
