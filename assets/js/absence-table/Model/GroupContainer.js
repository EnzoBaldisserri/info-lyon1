import Group from './Group';

class GroupContainer {
  groups = [];
  students = [];

  constructor(groups) {
    this.groups = groups;
    this.students = groups.reduce((carry, group) => ([
      ...carry,
      ...group.students,
    ]), []);
  }

  static fromData(data) {
    const groups = data.map(Group.fromData);

    return new GroupContainer(groups);
  }
}

export default GroupContainer;
