import React from 'react';
import PropTypes from 'prop-types';

import style from './GroupsCol.scss';

const GroupsCol = (props) => {
  const groups = props.groups.map((group) => {
    const lineHeight = 26;
    const height = `${(group.students.length * lineHeight) + 1}px`;

    return <div className={style.group} style={{ height }} key={group.id}>{group.fullname}</div>;
  });

  return (
    <div>
      { groups }
    </div>
  );
};

GroupsCol.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number.isRequired,
    fullname: PropTypes.string.isRequired,
    students: PropTypes.arrayOf(PropTypes.any).isRequired,
  })).isRequired,
};

export default GroupsCol;
