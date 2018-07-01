import React, { Component } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

function formatTime(time) {
  const date = new Date(time);

  return date.toLocaleString(undefined, {
    timeZone: 'UTC',
    hour: '2-digit',
    minute: '2-digit',
  });
}

class AbsenceDay extends Component {
  static propTypes = {
    absences: PropTypes.arrayOf(PropTypes.shape({
      student: PropTypes.shape({
        id: PropTypes.number.isRequired,
      }),
      type: PropTypes.shape({
        name: PropTypes.string.isRequired,
      }),
    })).isRequired,
    openEditor: PropTypes.func.isRequired,
  };

  state = {
    open: false,
  };

  shouldComponentUpdate(nextProps, nextState) {
    if (this.state.open !== nextState.open) {
      return true;
    }

    if (this.props.absences.length !== nextProps.absences.length) {
      return true;
    }

    return this.props.absences.some((absence, index) => nextProps.absences[index] !== absence);
  }

  getType = () => this.props.absences.reduce(
    (carry, absence) => ((carry === null || carry === absence.type.name) ? absence.type.name : 'several'),
    null,
  );

  getClasses = () => {
    const { absences } = this.props;

    if (absences.length === 0) {
      return null;
    }

    const typeClass = this.isJustified() ?
      'abs-justified'
      : `abs-${this.getType()}`;

    return `abs ${typeClass}`;
  };

  isJustified = () => {
    const { absences } = this.props;

    return !absences.some(absence => !absence.justified);
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

  openEditor = () => {
    this.close();
    this.props.openEditor();
  };

  render() {
    const { absences, openEditor, ...otherProps } = this.props;
    const { open } = this.state;

    if (absences.length === 0) {
      return (
        <td
          role="gridcell"
          onClick={openEditor}
          {...otherProps}
        />
      );
    }

    const classes = classNames(
      this.getClasses(),
      { open },
    );

    return (
      <td
        role="gridcell"
        className={classes}
        onMouseEnter={this.open}
        onMouseLeave={this.close}
        onClick={this.openEditor}
        {...otherProps}
      >
        { !open
          ? null
          : absences.map(absence => (
            <div className={`abs-${absence.type.name}`} key={absence.id}>
              <div>
                { Translator.trans('absence.props.time') } :&nbsp;
                { formatTime(absence.start_time) } - { formatTime(absence.end_time) }
              </div>
              <div>
                { Translator.trans('absence.props.justified') } :&nbsp;
                { Translator.trans(`global.message.${absence.justified ? 'yes' : 'no'}`) }
              </div>
              <div>{ Translator.trans(`absence.type.${absence.type.name}`) }</div>
            </div>
          ))
        }
      </td>
    );
  }
}

export default AbsenceDay;
