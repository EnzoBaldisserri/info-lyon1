import React from 'react';
import PropTypes from 'prop-types';

const TableStatic = props => (
  <div id="table-static">
    { props.groups.map(group => <div>{ group.name }</div>)}
  </div>
);

TableStatic.propTypes = {
  groups: PropTypes.arrayOf(PropTypes.any).isRequired,
};

export default TableStatic;
