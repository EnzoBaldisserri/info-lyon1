import React, { Component } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

import StudentDay from '../Model/StudentDay';

function formatDate(date) {
  return date.toLocaleString(undefined, {
    timeZone: 'UTC',
    hour: '2-digit',
    minute: '2-digit',
  });
}

class AbsenceDay extends Component {
  static propTypes = {
    day: PropTypes.instanceOf(StudentDay),
  };

  static defaultProps = {
    day: null,
  };

  state = {
    open: false,
  };

  open = () => {
    this.setState({
      open: true,
    });
  };

  close = () => {
    this.setState({
      open: false,
    });
  };

  render() {
    const { day, ...otherProps } = this.props;
    const { open } = this.state;

    if (!day || day.absences.length === 0) {
      return (
        <td
          role="gridcell"
          {...otherProps}
        />
      );
    }

    const classes = classNames(
      day.getClasses(),
      { open },
    );

    return (
      <td
        role="gridcell"
        className={classes}
        onMouseEnter={this.open}
        onMouseLeave={this.close}
        {...otherProps}
      >
        { day.absences.map(absence => (
          <div className={`abs-${absence.type.name}`} key={absence.id}>
            <div>
              { Translator.trans('absence.props.time') } :&nbsp;
              { formatDate(absence.startTime) } - { formatDate(absence.endTime) }
            </div>
            <div>
              { Translator.trans('absence.props.justified') } :&nbsp;
              { Translator.trans(`global.message.${absence.justified ? 'yes' : 'no'}`) }
            </div>
            <div>{ Translator.trans(`absence.type.${absence.type.name}`) }</div>
          </div>
        )) }
      </td>
    );
  }
}

export default AbsenceDay;
