import React from 'react';
import PropTypes from 'prop-types';

import style from './TableStatic.scss';

import GroupsCol from './GroupsCol';
import StudentsCol from './StudentsCol';

const TableStatic = (props) => {
  const { groups, ...restProps } = props;

  return (
    <div {...restProps} className={style.main}>
      <div className={style.title}>
        Étudiants
      </div>
      <div className={`${style.flex} row`}>
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
