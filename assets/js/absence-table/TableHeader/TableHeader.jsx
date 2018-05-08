import React from 'react';
import PropTypes from 'prop-types';

const TableHeader = (props) => {
  const { semester: { months } } = props;

  const days = months.reduce((carry, month) => ([
    ...carry,
    ...Object.entries(month.days),
  ]), []);

  /* eslint-disable react/no-array-index-key */
  // The header shouldn't change, therefore using index as key isn't important

  return (
    <thead>
      <tr>
        { months.map((month, key) => (
          <th colSpan={Object.values(month.days).length} key={key}>
            {month.name}
          </th>
        )) }
      </tr>
      <tr>
        { days.map(([, dayInWeek], key) => (
          <th key={key}>{ dayInWeek }</th>
        )) }
      </tr>
      <tr>
        { days.map(([dayInMonth], key) => (
          <th key={key}>{ dayInMonth }</th>
        )) }
      </tr>
    </thead>
  );
};

TableHeader.defaultProps = {
  semester: null,
};

TableHeader.propTypes = {
  semester: PropTypes.shape({
    months: PropTypes.arrayOf(PropTypes.shape({
      name: PropTypes.string.isRequired,
    })).isRequired,
  }),
};

export default TableHeader;
