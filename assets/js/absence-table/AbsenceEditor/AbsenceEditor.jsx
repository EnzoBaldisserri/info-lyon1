import React, { Component } from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import { AbsenceConsumer } from '../AbsenceContext';
import Form from './Form';

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
    // eslint-disable-next-line react/no-unused-prop-types
    absences: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.number,
    })),
    student: PropTypes.shape({
      fullname: PropTypes.string,
    }),
    date: PropTypes.instanceOf(Date),
  };

  static defaultProps = {
    absences: [],
    student: null,
    date: null,
  };

  static getDerivedStateFromProps(props, state) {
    const isInState = absence => !!state.absences[absence.id];
    const isSame = props.absences.every(isInState);

    if (isSame) {
      return null;
    }

    const sorter = (a1, a2) => (a1.start_time > a2.start_time ? 1 : -1);
    const sorted = props.absences.slice(0).sort(sorter);

    return sorted.reduce(
      (obj, absence) => ({
        absences: {
          ...obj.absences,
          [absence.id]: absence,
        },
        order: [
          ...obj.order,
          absence.id,
        ],
      }),
      {
        absences: {},
        order: [],
      },
    );
  }

  state = {
    absences: {},
    order: [],
  };

  update = absence => (property, value) => {
    let finalValue;

    switch (property) {
      case 'start_time':
      case 'end_time':
        finalValue = absence[property].replace(/\d{2}:\d{2}:(\d{2})/, `${value}:$1`);
        break;
      default:
        finalValue = value;
    }

    if (absence[property] !== finalValue) {
      this.setState((prevState) => {
        absence[property] = finalValue;

        return prevState;
      });
    }
  };

  render() {
    const { student, date } = this.props;
    const { absences, order } = this.state;

    const sortedAbsences = order.map(id => absences[id]);

    return (
      <div className={classnames(['absence-editor', { closed: !student || !date }])}>
        <div className="modal-content">
          <div className="center-align h4 mb-4">
            {student ? `${student.firstname} ${student.surname.toUpperCase()}` : 'Error'}<br />
            {date ? date.toLocaleDateString() : 'Error'}
          </div>
          <div className="row">
            {sortedAbsences.map(absence => (
              <Form
                absence={absence}
                update={this.update(absence)}
                className="col s12 m10 l6"
                key={getAbsenceId(absence)}
              />
            ))}
          </div>
          <div className="modal-footer">
            <AbsenceConsumer>
              {({ actions: { closeEditor } }) => (
                <button
                  type="button"
                  className="btn waves-effect waves-light"
                  onClick={closeEditor}
                >
                  {Translator.trans('global.button.close')}
                </button>
              )}
            </AbsenceConsumer>
          </div>
        </div>
      </div>
    );
  }
}

export default AbsenceEditor;
