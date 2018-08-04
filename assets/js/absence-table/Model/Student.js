import Absence from './Absence';

class Student {
  constructor(id, username, firstname, surname, absences) {
    this.id = id;
    this.username = username;
    this.firstname = firstname;
    this.surname = surname;
    this.absences = absences;
  }

  static fromData(data) {
    const {
      id,
      username,
      firstname,
      surname,
    } = data;
    const absences = data.absences.map(Absence.fromData);

    return new Student(id, username, firstname, surname, absences);
  }
}

export default Student;
