import React from 'react';
import PropTypes from 'prop-types';

import GroupsCol from './GroupsCol';
import StudentsCol from './StudentsCol';

const TableStatic = (props) => {
  const { groups } = props;

  return (
    <div className="static">
      <div className="title">
        Étudiants
      </div>
      <div className="flex">
        <GroupsCol groups={groups} />
        <StudentsCol groups={groups} />
      </div>
    </div>
  );
};

TableStatic.defaultProps = {
  groups: [],
};

TableStatic.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.any),
};

export default TableStatic;
