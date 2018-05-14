import React from 'react';
import PropTypes from 'prop-types';

import StudentRow from './StudentRow';

const TableBody = (props) => {
  const { groups } = props;

  const studentsRows = groups.map(group =>
    group.students.map((student, index) => (
      <StudentRow
        className={index === 0 ? 'new-group' : null}
        student={student}
        key={student.id}
      />
    )));

  return (
    <tbody>
      { studentsRows }
    </tbody>
  );
};

TableBody.defaultProps = {
  groups: [],
};

TableBody.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.any),
};

export default TableBody;
