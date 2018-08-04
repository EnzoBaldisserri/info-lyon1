import StudentDay from './StudentDay';

class Day {
  constructor(name, date, hash) {
    this.name = name;
    this.date = date;
    this.hash = hash;

    this.studentDays = new Map();
  }

  getAbsences(student) {
    if (this.studentDays.has(student)) {
      return this.studentDays.get(student);
    }

    const studentDay = new StudentDay(student, this.date, []);
    this.studentDays.set(student, studentDay);

    return studentDay;
  }

  addAbsence(student, ...absences) {
    if (this.studentDays.has(student)) {
      this.studentDays.get(student).addAbsence(...absences);
    } else {
      this.studentDays.set(student, new StudentDay(student, this.date, absences));
    }
  }

  static fromData(data) {
    const { name, hash } = data;
    const date = new Date(data.date);

    return [date.getTime(), new Day(name, date, hash)];
  }
}

export default Day;
