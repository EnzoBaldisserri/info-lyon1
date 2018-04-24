import React from 'react';
import PropTypes from 'prop-types';

const TableBody = props => (
  <tbody>
    { props.groups.map(group => <tr>{group.name}</tr>) }
  </tbody>
);

TableBody.defaultProps = {
  groups: [],
};

TableBody.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.any),
};

export default TableBody;
