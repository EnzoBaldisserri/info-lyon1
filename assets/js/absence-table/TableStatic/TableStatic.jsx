import React from 'react';

import GroupsCol from './GroupsCol';
import StudentsCol from './StudentsCol';

const TableStatic = () => (
  <div className="static">
    <div className="title">
      { Translator.trans('absence.plain.students') }
    </div>
    <div className="flex">
      <GroupsCol />
      <StudentsCol />
    </div>
  </div>
);

export default TableStatic;
