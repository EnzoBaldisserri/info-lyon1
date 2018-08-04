import Period from './Period';
import GroupContainer from './GroupContainer';

class DataHolder {
  constructor(groupContainer, period, absenceTypes) {
    this.groupContainer = groupContainer;
    this.period = period;
    this.absenceTypes = absenceTypes;

    groupContainer.students.forEach((student) => {
      student.absences.forEach((absence) => {
        const day = period.getDay(absence.startTime);
        day.addAbsence(student, absence);
      });
    });
  }

  static fromData(data) {
    if (data.error) {
      throw new Error(data.error);
    }

    const groupContainer = GroupContainer.fromData(data.groups);
    const period = Period.fromData(data.months);

    return new DataHolder(groupContainer, period, data.absenceTypes);
  }
}

export default DataHolder;
