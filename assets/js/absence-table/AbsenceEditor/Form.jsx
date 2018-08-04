import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import { AbsenceConsumer } from '../AbsenceContext';
import TimeSlider from './TimeSlider';
import Absence from '../Model/Absence';

class Form extends PureComponent {
  static propTypes = {
    absence: PropTypes.instanceOf(Absence).isRequired,
    update: PropTypes.func.isRequired,
    remove: PropTypes.func.isRequired,
  };

  static formatDate(date) {
    const hours = date.getUTCHours().toString().padStart(2, '0');
    const minutes = date.getUTCMinutes().toString().padStart(2, '0');

    return `${hours}:${minutes}`;
  }

  updateTime = (values, handle) => {
    const prop = handle === 0 ? 'startTime' : 'endTime';
    this.props.update(prop, values[handle]);
  };

  updateType = type => () => {
    this.props.update('type', type);
  };

  updateJustified = (e) => {
    this.props.update('justified', e.target.checked);
  };

  render() {
    const {
      absence,
      remove,
      update,
      ...otherProps
    } = this.props;

    return (
      <section {...otherProps}>
        <div className="card grey lighten-5">
          <div className="card-content">
            <div className="right-align">
              <button
                className="btn-flat waves-effect waves-red"
                onClick={remove}
              >
                <i className="material-icons action-icon">delete</i>
              </button>
            </div>
            <div className="time-container">
              <div className="h6">{ Translator.trans('absence.props.time') }</div>
              <TimeSlider
                times={[
                  Form.formatDate(absence.startTime),
                  Form.formatDate(absence.endTime),
                ]}
                onChange={this.updateTime}
              />
            </div>
            <div className="abs-type-container">
              <div className="h6">{ Translator.trans('absence.props.type') }</div>
              <div className="flex-row">
                <AbsenceConsumer>
                  { ({ dataHolder: { absenceTypes } }) => absenceTypes.map(type => (
                    <button
                      className={classnames('abs-type', `abs-${type.name}`, {
                        active: type.id === absence.type.id,
                      })}
                      onClick={this.updateType(type)}
                      onKeyDown={this.updateType(type)}
                      key={type.id}
                    >
                      { Translator.trans(`absence.type.${type.name}`) }
                    </button>
                  )) }
                </AbsenceConsumer>
              </div>
            </div>
            <div className="justify-container">
              <label>
                <input type="checkbox" checked={absence.justified} onChange={this.updateJustified} />
                <span>{ Translator.trans('absence.props.justified') }</span>
              </label>
            </div>
          </div>
        </div>
      </section>
    );
  }
}

export default Form;
