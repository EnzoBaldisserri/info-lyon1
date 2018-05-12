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
    const {
      i18n,
      absences,
    } = this.props;

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
                {i18n.props.time}&nbsp;:&nbsp;
                { formatTime(absence.start_time) } - { formatTime(absence.end_time) }
              </div>
              <div>
                {i18n.props.justified}&nbsp;:&nbsp;
                { absence.justified ? i18n.general.yes : i18n.general.no }
              </div>
              <div>{ i18n.absence_types[absence.type.name] || absence.type.name }</div>
            </div>
          ))
        }
      </td>
    );
  }
}

AbsenceDay.propTypes = {
  i18n: PropTypes.shape({
    general: PropTypes.shape({
      yes: PropTypes.string,
      no: PropTypes.string,
    }),
    props: PropTypes.shape({
      time: PropTypes.string,
      justified: PropTypes.string,
    }),
    absence_types: PropTypes.object.isRequired,
  }).isRequired,
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
