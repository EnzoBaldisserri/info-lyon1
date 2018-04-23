import React, { Component } from 'react';
import PropTypes from 'prop-types';

import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';

class AbsenceTable extends Component {
  construct() {
    this.state = {
      semester: null,
      groups: [],
    };
  }

  render() {
    return (
      <div className="section">
        <TableStatic groups={this.state.groups} />
        <table>
          <TableHeader semester={this.state.semester} />
          <TableBody groups={this.state.groups} apis={this.props.apis} />
        </table>
      </div>
    );
  }
}

AbsenceTable.propTypes = {
  apis: PropTypes.arrayOf(PropTypes.string).isRequired,
};

export default AbsenceTable;
