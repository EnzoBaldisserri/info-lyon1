import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class StudentSummary extends Component {
  static propTypes = {
    open: PropTypes.bool,
    student: PropTypes.shape({
      absences: PropTypes.arrayOf(PropTypes.shape({
        justified: PropTypes.bool,
      })).isRequired,
    }).isRequired,
    toggle: PropTypes.func.isRequired,
  };

  static defaultProps = {
    open: false,
  };

  getCount() {
    const { student: { absences } } = this.props;
    const total = absences.length;

    const days = absences.reduce(
      (dates, absence) => {
        const absenceDate = absence.start_time.slice(0, 10);

        return dates.includes(absenceDate)
          ? dates
          : [
            ...dates,
            absenceDate,
          ];
      },
      [],
    ).length;

    const justified = absences.reduce(
      (count, absence) => count + (absence.justified ? 1 : 0),
      0,
    );

    return {
      total,
      days,
      justified,
    };
  }

  render() {
    const { open, toggle } = this.props;

    if (!open) {
      return null;
    }

    const { total, days, justified } = this.getCount();

    return (
      <div className="popup z-depth-2">
        <span
          className="popup-close material-icons"
          onClick={toggle}
          onKeyPress={toggle}
          role="button"
          tabIndex={0}
        />
        <div className="center-align">
          { Translator.transChoice('absence.student.count.total', total) }<br />
          { total > 0
            ? (
              <Fragment>
                { Translator.transChoice('absence.student.count.days', days) }<br />
                { Translator.transChoice('absence.student.count.justified', justified) }
              </Fragment>
            )
            : ''
          }
        </div>
      </div>
    );
  }
}

export default StudentSummary;
