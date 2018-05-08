import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import Loader from '../react-utils/Loader';

import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';

class AbsenceTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      loaded: false,
      error: null,
      semester: null,
      groups: [],
    };
  }

  componentDidMount() {
    const { apis } = this.props;

    fetch(apis.load)
      .then((response) => {
        if (!response.ok) {
          throw new Error('Impossible de charger les données nécessaires');
        }

        return response.json();
      })

      .then(data => this.setState({
        loaded: !!data.semester,
        ...data,
      }))

      .catch(error => this.setState({ error }));
  }

  render() {
    const {
      loaded,
      error,
      semester,
      groups,
    } = this.state;

    if (error) {
      return (
        <div className="section alert alert-error">
          {error.message}
        </div>
      );
    }

    if (!loaded) {
      return (
        <Loader />
      );
    }

    return (
      <Fragment>
        <TableStatic groups={groups} />
        <div className="dynamic">
          <table>
            <TableHeader semester={semester} />
            <TableBody groups={groups} />
          </table>
        </div>
      </Fragment>
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
