class StudentDay {
  constructor(student, date, absences) {
    this.student = student;
    this.date = date;
    this.absences = absences;
  }

  addAbsence(...absences) {
    this.absences = [
      ...this.absences,
      ...absences,
    ];
  }

  removeAbsence(absence) {
    this.absences = this.absences.filter(formerAbsence => formerAbsence !== absence);
  }

  getType() {
    return this.absences.reduce(
      (carry, absence) => ((carry === null || carry === absence.type.name) ? absence.type.name : 'several'),
      null,
    );
  }

  isJustified() {
    return !this.absences.some(absence => !absence.justified);
  }

  getClasses() {
    if (this.absences.length === 0) {
      return null;
    }

    const typeClass = this.isJustified() ?
      'abs-justified'
      : `abs-${this.getType()}`;

    return `abs ${typeClass}`;
  }
}

export default StudentDay;
