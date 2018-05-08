import React from 'react';
import PropTypes from 'prop-types';

const GroupsCol = (props) => {
  const groups = props.groups.map((group) => {
    const lineHeight = 26;
    const height = `${(group.students.length * lineHeight) + 1}px`;

    return (
      <div
        key={group.id}
        className="group"
        style={{ height }}
      >
        {group.fullname}
      </div>
    );
  });

  return (
    <div className="groups">
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
