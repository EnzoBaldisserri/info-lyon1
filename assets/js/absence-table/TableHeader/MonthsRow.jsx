import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import Month from '../Model/Month';

class MonthsRow extends PureComponent {
  static propTypes = {
    months: PropTypes.arrayOf(PropTypes.instanceOf(Month)).isRequired,
  };

  render() {
    const { months } = this.props;

    return (
      <tr>
        { months.map(month => (
          <th colSpan={month.days.size} key={month.hash}>
            {month.name}
          </th>
        )) }
      </tr>
    );
  }
}

export default MonthsRow;
