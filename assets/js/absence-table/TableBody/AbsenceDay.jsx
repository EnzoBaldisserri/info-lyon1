import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

const isNotJustified = absence => !absence.justified;

const areJustified = absences => !absences.some(isNotJustified);

const getType = absences => absences.reduce(
  (carry, absence) => ((carry === null || carry === absence.type.name) ? absence.type.name : 'several'),
  null,
);

const getClasses = (absences) => {
  if (absences.length === 0) {
    return null;
  }

  const typeClass = areJustified(absences) ?
    'abs-justified'
    : `abs-${getType(absences)}`;

  return `abs ${typeClass}`;
};

function formatTime(time) {
  const date = new Date(time);

  return date.toLocaleString(undefined, {
    timeZone: 'UTC',
    hour: '2-digit',
    minute: '2-digit',
  });
}

class AbsenceDay extends PureComponent {
  static propTypes = {
    absences: PropTypes.arrayOf(PropTypes.shape({
      student: PropTypes.shape({
        id: PropTypes.number.isRequired,
      }),
      type: PropTypes.shape({
        name: PropTypes.string.isRequired,
      }),
    })).isRequired,
  }

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
      getClasses(absences),
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
