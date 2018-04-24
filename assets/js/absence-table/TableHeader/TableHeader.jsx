import React from 'react';
import PropTypes from 'prop-types';

const TableHeader = props => (
  <thead>
    <tr>
      <td>I am the HEADER</td>
    </tr>
    <tr>
      <td>FOR SEMESTER { props.semester }</td>
    </tr>
  </thead>
);

TableHeader.defaultProps = {
  semester: null,
};

TableHeader.propTypes = {
  semester: PropTypes.shape({
    months: PropTypes.arrayOf().isRequired,
  }),
};

export default TableHeader;
