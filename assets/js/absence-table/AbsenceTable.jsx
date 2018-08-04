import React, { Component, Fragment } from 'react';
import axios from 'axios';

import DataHolder from './Model/DataHolder';
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
      dataHolder: null,
      currentEdit: null,
    };
  }

  componentDidMount() {
    axios.get(Routing.generate('api_absence_get_all'))
      .then(response => DataHolder.fromData(response.data))

      .then((dataHolder) => {
        const nextState = {
          dataHolder,
        };

        // Center current day
        const today = new Date();
        const { firstDay } = dataHolder.period;

        const timeDifference = today - firstDay;

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
            tbody td:nth-child(7n + ${8 - firstDay.getDay()}),
            tbody td:nth-child(7n + ${7 - firstDay.getDay()}) {
              background-color: rgba(255, 183, 77, .6);
              pointer-events: none;
            }
          </style>`,
        );

        return nextState;
      })

      .then((nextState) => {
        nextState.loaded = true;

        this.setState(nextState);
      })

      .catch((error) => {
        // console.log(error);
        this.setState({ error });
      });
  }

  componentDidUpdate(prevProps, prevState) {
    if (prevState.tableScroll !== this.state.tableScroll && this.tableContainer.current) {
      this.tableContainer.current.scrollLeft = this.state.tableScroll;
    }
  }

  componentDidCatch(error) {
    this.setState({ error });
  }

  edit = newEdit => () => {
    this.setState({
      currentEdit: newEdit,
    });
  };

  render() {
    const {
      error,
      loaded,
      dataHolder,
      currentEdit,
    } = this.state;

    if (error) {
      return (
        <div className="alert error center-block">
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
      dataHolder,
      edit: this.edit,
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
          <AbsenceEditor studentDay={currentEdit} close={this.edit(null)} />
        </AbsenceProvider>
      </Fragment>
    );
  }
}

export default AbsenceTable;
