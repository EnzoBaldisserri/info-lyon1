import React from 'react';
import PropTypes from 'prop-types';

import StudentRow from './StudentRow';

const TableBody = (props) => {
  const { groups, i18n } = props;

  const studentsRows = groups.map(group =>
    group.students.map((student, index) => (
      <StudentRow
        className={index === 0 ? 'new-group' : null}
        i18n={i18n}
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
  i18n: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  groups: PropTypes.arrayOf(PropTypes.any),
};

export default TableBody;
