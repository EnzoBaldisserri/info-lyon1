import React, { Component, Fragment } from 'react';
import axios from 'axios';
import Loader from '../react-utils/Loader';

import { AbsenceProvider } from './AbsenceContext';
import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';
// import AbsenceEditor from './AbsenceEditor/AbsenceEditor';

class AbsenceTable extends Component {
  constructor(props) {
    super(props);

    this.tableContainer = React.createRef();

    this.state = {
      loaded: false,
      error: null,
      tableScroll: null,
      // Loaded from server
      firstDay: null,
      months: [],
      groups: [],
      absenceTypes: [],
    };
  }

  componentDidMount() {
    axios.get(Routing.generate('api_absence_get_all'))
      .then(response => response.data)
      .then((data) => {
        if (data.error) {
          throw new Error(data.error);
        }

        const parsedData = {
          ...data,
          firstDay: new Date(data.firstDay),
        };

        this.setState({
          loaded: true,
          ...parsedData,
        });

        return parsedData;
      })

      .then((data) => {
        // Center current day
        const today = new Date();
        const timeDifference = today - data.firstDay;

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
            tbody td:nth-child(7n + ${8 - data.firstDay.getDay()}),
            tbody td:nth-child(7n + ${7 - data.firstDay.getDay()}) {
              background-color: rgba(255, 183, 77, .6);
            }
          </style>`,
        );
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
      error,
      loaded,
      firstDay,
      months,
      groups,
      absenceTypes,
    } = this.state;

    if (error) {
      return (
        <div className="alert alert-error center-block">
          {error.message}
        </div>
      );
    }

    if (!loaded) {
      return (
        <Loader />
      );
    }

    const provided = {
      firstDay,
      months,
      groups,
      absenceTypes,
    };

    return (
      <Fragment>
        <AbsenceProvider value={provided}>
          <TableStatic />
          <div className="dynamic" ref={this.tableContainer}>
            <table role="grid">
              <TableHeader />
              <TableBody />
            </table>
          </div>
        </AbsenceProvider>
      </Fragment>
    );
  }
}

export default AbsenceTable;
