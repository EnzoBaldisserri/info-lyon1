import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import { AbsenceConsumer } from '../AbsenceContext';
import TimeSlider from './TimeSlider';


class Form extends PureComponent {
  static propTypes = {
    absence: PropTypes.shape({
      id: PropTypes.number,
      absenceType: PropTypes.shape({
        id: PropTypes.number,
      }),
      start_time: PropTypes.string,
      end_time: PropTypes.string,
      justified: PropTypes.bool,
    }).isRequired,
    update: PropTypes.func.isRequired,
  };

  updateTime = (values, handle) => {
    const prop = handle === 0 ? 'start_time' : 'end_time';
    this.props.update(prop, values[handle]);
  };

  updateType = type => () => {
    this.props.update('type', type);
  };

  updateJustified = (e) => {
    this.props.update('justified', e.target.checked);
  };

  render() {
    const { absence, update, ...otherProps } = this.props;

    return (
      <section {...otherProps}>
        <div className="card grey lighten-5">
          <div className="card-content">
            <div className="pb-3">
              <div className="h6">{ Translator.trans('absence.props.time') }</div>
              <TimeSlider
                times={[
                  absence.start_time.slice(11, 16),
                  absence.end_time.slice(11, 16),
                ]}
                onChange={this.updateTime}
              />
            </div>
            <div className="py-3">
              <div className="h6">{ Translator.trans('absence.props.type') }</div>
              <div className="flex-row">
                <AbsenceConsumer>
                  { ({ absenceTypes }) => absenceTypes.map(type => (
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
            <div className="pt-3">
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
