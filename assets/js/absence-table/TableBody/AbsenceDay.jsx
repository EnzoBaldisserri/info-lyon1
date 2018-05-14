import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

function isJustified(absences) {
  return absences.reduce(
    (carry, absence) => carry && absence.justified,
    true,
  );
}

function getType(absences) {
  return absences.reduce(
    (carry, absence) => ((carry === null || carry === absence.type.name) ? absence.type.name : 'several'),
    null,
  );
}

function getClass(absences) {
  if (absences.length === 0) {
    return null;
  }

  const typeClass = isJustified(absences) ?
    'abs-justified'
    : `abs-${getType(absences)}`;

  return `abs ${typeClass}`;
}

function formatTime(time) {
  const date = new Date(time);

  return date.toLocaleString(undefined, {
    timeZone: 'UTC',
    hour: '2-digit',
    minute: '2-digit',
  });
}

class AbsenceDay extends PureComponent {
  state = {
    open: false,
  }

  open = () => {
    this.setState({
      open: true,
    });
  }

  close = () => {
    this.setState({
      open: false,
    });
  }

  render() {
    const { absences } = this.props;
    const { open } = this.state;

    if (absences.length === 0) {
      return <td role="gridcell" />;
    }

    const classes = classNames(
      getClass(absences),
      { open },
    );

    return (
      <td
        role="gridcell"
        onMouseEnter={this.open}
        onMouseLeave={this.close}
        className={classes}
      >
        {
          absences.map(absence => (
            <div className={`abs-${absence.type.name}`} key={absence.id}>
              <div>
                { Translator.trans('absence.props.time') } :&nbsp;
                { formatTime(absence.start_time) } - { formatTime(absence.end_time) }
              </div>
              <div>
                { Translator.trans('absence.props.justified') } :&nbsp;
                { absence.justified ? Translator.trans('global.message.yes') : Translator.trans('global.message.no') }
              </div>
              <div>{ Translator.trans(`absence.type.${absence.type.name}`) }</div>
            </div>
          ))
        }
      </td>
    );
  }
}

AbsenceDay.propTypes = {
  absences: PropTypes.arrayOf(PropTypes.shape({
    student: PropTypes.shape({
      id: PropTypes.number.isRequired,
    }),
    type: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }),
  })).isRequired,
};

export default AbsenceDay;
