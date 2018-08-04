import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Day from '../Model/Day';

class DaysRow extends Component {
  static propTypes = {
    days: PropTypes.arrayOf(PropTypes.instanceOf(Day)).isRequired,
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
        { days.map(day => (
          <th key={day.hash}>{number ? day.date.getDate() : day.name}</th>
        )) }
      </tr>
    );
  }
}

export default DaysRow;
