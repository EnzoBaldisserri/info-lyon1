import React, { Component } from 'react';
import PropTypes from 'prop-types';

import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';

class AbsenceTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      semester: null,
      groups: [],
    };

    fetch(props.apis.load)
      .then((response) => {
        response.text().then(console.log); // eslint-disable-line
        if (true || !response.ok || response.redirected) { // eslint-disable-line
          throw new Error('Impossible de charger les données nécessaires');
        }

        return response.json();
      })
      .catch((error) => {
        console.log(error); // eslint-disable-line no-console
        this.setState({ error });
      });
  }

  render() {
    const {
      semester,
      groups,
      error,
    } = this.state;

    if (error) {
      return (
        <div className="section alert alert-error">
          {error.message}
        </div>
      );
    }

    return (
      <div className="section">
        <TableStatic groups={groups} />
        <table>
          <TableHeader semester={semester} />
          <TableBody groups={groups} />
        </table>
      </div>
    );
  }
}

/* eslint-disable react/no-unused-prop-types */
AbsenceTable.propTypes = {
  apis: PropTypes.shape({
    load: PropTypes.string,
    add: PropTypes.string,
    remove: PropTypes.string,
  }).isRequired,
};

export default AbsenceTable;
