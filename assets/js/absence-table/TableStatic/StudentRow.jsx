import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

import Student from '../Model/Student';
import StudentSummary from './StudentSummary';

class StudentRow extends PureComponent {
  static propTypes = {
    student: PropTypes.instanceOf(Student).isRequired,
  };

  state = {
    open: false,
  };

  toggleSummary = () => {
    this.setState({
      open: !this.state.open,
    });
  };

  render() {
    const { open } = this.state;
    const { student } = this.props;
    const { id, firstname, surname } = student;

    return (
      <div className="student-row">
        <div className="content" key={id}>
          { `${firstname} ${surname.toUpperCase()}` }
        </div>
        <i
          role="button"
          className="material-icons action-icon"
          onClick={this.toggleSummary}
          onKeyUp={this.toggleSummary}
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
