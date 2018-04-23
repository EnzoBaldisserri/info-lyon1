import React from 'react';
import PropTypes from 'prop-types';

const TableBody = props => (
  <tbody>
    { props.groups.map(group => <tr>{group.name}</tr>) }
  </tbody>
);

TableBody.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.any).isRequired,
  apis: PropTypes.arrayOf(PropTypes.string).isRequired,
};

export default TableBody;
