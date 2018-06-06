import React from 'react';

import AbsenceContext from '../AbsenceContext';

const cellHeight = 26;
const GroupsCol = () => (
  <div className="groups">
    <AbsenceContext.Consumer>
      { ({ groups }) => groups.map(group => (
        <div
          key={group.id}
          className="group"
          style={{ height: `${(group.students.length * cellHeight) + 1}px` }}
        >
          {group.fullname}
        </div>
      )) }
    </AbsenceContext.Consumer>
  </div>
);

export default GroupsCol;
