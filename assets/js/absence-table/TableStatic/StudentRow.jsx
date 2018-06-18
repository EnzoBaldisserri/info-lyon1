import React, { Component } from 'react';
import PropTypes from 'prop-types';

import StudentSummary from './StudentSummary';

class StudentRow extends Component {
  static propTypes = {
    student: PropTypes.shape({
      id: PropTypes.number.isRequired,
      firstname: PropTypes.string.isRequired,
      surname: PropTypes.string.isRequired,
    }).isRequired,
  }

  state = {
    open: false,
  }

  toggleSummary = () => {
    this.setState({
      open: !this.state.open,
    });
  }

  render() {
    const { open } = this.state;
    const { student } = this.props;
    const { id, firstname, surname } = student;

    return (
      <div className="student-row">
        <div className="content" key={id}>
          { `${firstname} ${surname}` }
        </div>
        <i
          className="material-icons"
          onClick={this.toggleSummary}
          onKeyPress={this.toggleSummary}
          role="button"
          tabIndex={0}
        >
          info
        </i>
        <StudentSummary open={open} student={student} toggle={this.toggleSummary} />
      </div>
    );
  }
}

export default StudentRow;
