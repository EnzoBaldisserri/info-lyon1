import React from 'react';

import { AbsenceConsumer } from '../AbsenceContext';

const cellHeight = 26;

const GroupsCol = () => (
  <div className="groups">
    <AbsenceConsumer>
      { ({ dataHolder: { groupContainer: { groups } } }) => groups.map(group => (
        <div
          className="group"
          style={{ height: `${(group.students.length * cellHeight) + 1}px` }}
          key={group.id}
        >
          {group.fullname}
        </div>
      )) }
    </AbsenceConsumer>
  </div>
);

export default GroupsCol;
