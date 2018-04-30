import React from 'react';
import PropTypes from 'prop-types';

import style from './StudentsCol.scss';
import StudentRow from './StudentRow';

const StudentsCol = (props) => {
  const groups = props.groups.map((group) => {
    const students = group.students.map(student =>
      <StudentRow student={student} key={student.id} />);

    return (
      <div className={style.group} key={group.id}>
        { students }
      </div>
    );
  });

  return (
    <div className={style.main}>
      { groups }
    </div>
  );
};

StudentsCol.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number.isRequired,
    students: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.number,
    })),
  })).isRequired,
};

export default StudentsCol;
