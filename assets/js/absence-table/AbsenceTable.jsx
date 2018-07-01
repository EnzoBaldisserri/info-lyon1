import React, { Component } from 'react';
import axios from 'axios';

import Loader from '../react-utils/Loader';
import { AbsenceProvider } from './AbsenceContext';
import TableStatic from './TableStatic/TableStatic';
import TableHeader from './TableHeader/TableHeader';
import TableBody from './TableBody/TableBody';
import AbsenceEditor from './AbsenceEditor/AbsenceEditor';

class AbsenceTable extends Component {
  constructor(props) {
    super(props);

    this.tableContainer = React.createRef();

    this.state = {
      loaded: false,
      error: null,
      tableScroll: null,
      editor: {
        absences: [],
        student: null,
        date: null,
      },
      // Loaded from server
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

        return {
          ...data,
          firstDay: new Date(data.firstDay),
        };
      })

      .then((nextState) => {
        // Center current day
        const today = new Date();
        const timeDifference = today - nextState.firstDay;

        if (timeDifference > 0) {
          const cellWidth = 26;
          const dayDifference = timeDifference / (1000 * 3600 * 24);

          nextState.tableScroll =
            Math.max((dayDifference * cellWidth) - (window.innerWidth / 2), 0);
        }

        // Highlight week-ends
        document.head.insertAdjacentHTML(
          'beforeend',
          `<style>
            tbody td:nth-child(7n + ${8 - nextState.firstDay.getDay()}),
            tbody td:nth-child(7n + ${7 - nextState.firstDay.getDay()}) {
              background-color: rgba(255, 183, 77, .6);
            }
          </style>`,
        );

        return nextState;
      })

      .then((nextState) => {
        nextState.loaded = true;

        this.setState(nextState);
      })

      .catch(error => this.setState({ error }));
  }

  componentDidUpdate(prevProps, prevState) {
    if (prevState.tableScroll !== this.state.tableScroll) {
      this.tableContainer.current.scrollLeft = this.state.tableScroll;
    }
  }

  openEditor = (student, date, absences) => () => {
    this.setState({
      editor: {
        absences,
        student,
        date,
      },
    });
  };

  closeEditor = () => {
    this.setState({
      editor: {
        absences: [],
        student: null,
        date: null,
      },
    });
  };

  render() {
    const {
      error,
      loaded,
      editor,
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
      actions: {
        openEditor: this.openEditor,
        closeEditor: this.closeEditor,
      },
      months,
      groups,
      absenceTypes,
    };

    return (
      <AbsenceProvider value={provided}>
        <TableStatic />
        <div className="dynamic" ref={this.tableContainer}>
          <table role="grid">
            <TableHeader />
            <TableBody />
          </table>
        </div>
        <AbsenceEditor {...editor} />
      </AbsenceProvider>
    );
  }
}

export default AbsenceTable;
