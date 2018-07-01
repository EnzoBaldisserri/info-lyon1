import React, { Component } from 'react';
import PropTypes from 'prop-types';

class DaysRow extends Component {
  static propTypes = {
    days: PropTypes.arrayOf(PropTypes.arrayOf(PropTypes.oneOfType([
      PropTypes.string,
      PropTypes.shape({
        name: PropTypes.string,
        repr: PropTypes.string,
      }),
    ]))).isRequired,
    number: PropTypes.bool,
  };

  static defaultProps = {
    number: false,
  };

  shouldComponentUpdate(nextProps) {
    return this.props.days.length !== nextProps.days.length;
  }

  render() {
    const { days, number } = this.props;

    return (
      <tr>
        { days.map(([dayInMonth, day]) => (
          <th key={day.repr}>{number ? dayInMonth : day.name}</th>
        ))}
      </tr>
    );
  }
}

export default DaysRow;
