import Student from './Student';

class Group {
  constructor(id, name, fullname, number, students) {
    this.id = id;
    this.name = name;
    this.fullname = fullname;
    this.number = number;
    this.students = students;
  }

  static fromData(data) {
    const {
      id,
      name,
      fullname,
      number,
    } = data;
    const students = data.students.map(Student.fromData);

    return new Group(id, name, fullname, number, students);
  }
}

export default Group;
