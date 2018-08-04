import React, { Component } from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import Form from './Form';
import StudentDay from '../Model/StudentDay';

let lastCreatedId = 0;

const getAbsenceId = (absence) => {
  if (!absence.id) {
    // Create fake id for new absences
    lastCreatedId -= 1;
    absence.id = lastCreatedId;
  }

  return absence.id;
};

class AbsenceEditor extends Component {
  static propTypes = {
    studentDay: PropTypes.instanceOf(StudentDay), // eslint-disable-line react/no-unused-prop-types
    close: PropTypes.func.isRequired,
  };

  static defaultProps = {
    studentDay: null,
  };

  static getDerivedStateFromProps(props, state) {
    if (props.studentDay === state.studentDay) {
      return null;
    }

    return {
      studentDay: props.studentDay,
    };
  }

  state = {
    studentDay: null,
  };

  update = absence => (property, value) => {
    let finalValue;

    if (property === 'startTime' || property === 'endTime') {
      const [hours, minutes] = value.split(':');

      finalValue = new Date(absence[property]);
      finalValue.setUTCHours(hours, minutes);
    } else {
      finalValue = value;
    }

    if (absence[property] !== finalValue) {
      this.setState((prevState) => {
        absence[property] = finalValue;

        return prevState;
      });
    }
  };

  remove = absence => () => {
    this.setState((prevState) => {
      prevState.studentDay.removeAbsence(absence);

      return prevState;
    });
  };

  render() {
    const { close } = this.props;
    const { studentDay } = this.state;

    let absences = [];
    let student = null;
    let date = null;

    if (studentDay) {
      ({ absences, student, date } = studentDay);
    }

    return (
      <div className={classnames('absence-editor', { closed: !studentDay })}>
        <div className="modal-content">
          <div className="center-align h4">
            {student ? `${student.firstname} ${student.surname.toUpperCase()}` : 'Error'}<br />
            {date ? date.toLocaleDateString() : 'Error'}
          </div>
          <div className="row">
            { absences.map(absence => (
              <Form
                absence={absence}
                update={this.update(absence)}
                remove={this.remove(absence)}
                className="col s12 m10 l6"
                key={getAbsenceId(absence)}
              />
            )) }
            <section className="col s12 m10 l6 add-container">
              <button className="btn-floating btn-large">
                <i className="material-icons">add</i>
              </button>
            </section>
          </div>
          <div className="modal-footer">
            <button
              type="button"
              className="btn waves-effect waves-light"
              onClick={close}
            >
              {Translator.trans('global.button.close')}
            </button>
          </div>
        </div>
      </div>
    );
  }
}

export default AbsenceEditor;
