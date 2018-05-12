import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import Loader from '../react-utils/Loader';

import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';

class AbsenceTable extends Component {
  constructor(props) {
    super(props);

    this.tableContainer = React.createRef();

    this.state = {
      loaded: false,
      error: null,
      months: [],
      groups: [],
      tableScroll: null,
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

        return data;
      })

      .then((data) => {
        if (data.firstDay) {
          const firstDate = new Date(data.firstDay);

          // Center current day
          const today = new Date();
          const timeDifference = today.getTime() - firstDate.getTime();

          if (timeDifference > 0) {
            const cellWidth = 26;
            const dayDifference = timeDifference / (1000 * 3600 * 24);

            this.setState({
              tableScroll: Math.max((dayDifference * cellWidth) - (window.innerWidth / 2), 0),
            });
          }

          // Highlight week-ends
          document.head.insertAdjacentHTML(
            'beforeend',
            `<style>
              tbody td:nth-child(7n + ${8 - firstDate.getDay()}),
              tbody td:nth-child(7n + ${7 - firstDate.getDay()}) {
                background-color: rgba(255, 183, 77, .6);
              }
            </style>`,
          );
        }
      })

      .catch(error => this.setState({ error }));
  }

  componentDidUpdate(prevProps, prevState) {
    // Only change table scroll
    if (prevState.tableScroll === this.state.tableScroll) {
      return;
    }

    this.tableContainer.current.scrollLeft = this.state.tableScroll;
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
        <div className="dynamic" ref={this.tableContainer}>
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
