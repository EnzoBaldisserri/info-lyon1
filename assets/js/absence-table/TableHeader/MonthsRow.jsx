import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

class MonthsRow extends PureComponent {
  static propTypes = {
    months: PropTypes.arrayOf(PropTypes.shape({
      name: PropTypes.string.isRequired,
      days: PropTypes.object.isRequired,
      repr: PropTypes.string.isRequired,
    })).isRequired,
  };

  render() {
    const { months } = this.props;

    return (
      <tr>
        {months.map(month => (
          <th colSpan={Object.values(month.days).length} key={month.repr}>
            {month.name}
          </th>
        ))}
      </tr>
    );
  }
}

export default MonthsRow;
