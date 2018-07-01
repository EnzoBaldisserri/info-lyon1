import React, { PureComponent } from 'react';

import GroupsCol from './GroupsCol';
import StudentsCol from './StudentsCol';

// eslint-disable-next-line react/prefer-stateless-function
class TableStatic extends PureComponent {
  render() {
    return (
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
  }
}

export default TableStatic;
