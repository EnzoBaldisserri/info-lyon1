import React from 'react';
import PropTypes from 'prop-types';

const TableBody = (props) => {
  const groups = props.groups.map(group => (
    <tr key={group.id}>
      <td>{group.name}</td>
    </tr>
  ));

  return (
    <tbody>
      { groups }
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
