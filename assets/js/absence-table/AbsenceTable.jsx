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
      months: [],
      groups: [],
    };
  }

  componentDidMount() {
    const { apis, i18n } = this.props;

    fetch(apis.load)
      .then((response) => {
        if (!response.ok) {
          throw new Error(i18n.load_error);
        }

        return response.json();
      })

      .then((data) => {
        if (data.error) {
          throw new Error(data.error);
        }

        this.setState({
          loaded: true,
          ...data,
        });
      })

      .catch(error => this.setState({ error }));
  }

  render() {
    const {
      loaded,
      error,
      months,
      groups,
    } = this.state;

    const { i18n } = this.props;

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
        <TableStatic groups={groups} i18n={i18n} />
        <div className="dynamic">
          <table>
            <TableHeader months={months} />
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
  i18n: PropTypes.shape({
    students: PropTypes.string,
    load_error: PropTypes.string,
  }).isRequired,
};

export default AbsenceTable;
