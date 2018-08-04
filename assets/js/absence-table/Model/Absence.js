class Absence {
  constructor(id, startTime, endTime, type, justified) {
    this.id = id;
    this.startTime = startTime;
    this.endTime = endTime;
    this.type = type;
    this.justified = justified;
  }

  static fromData(data) {
    const {
      id,
      type,
      justified,
    } = data;

    const startTime = new Date(data.startTime);
    const endTime = new Date(data.endTime);

    return new Absence(id, startTime, endTime, type, justified);
  }
}

export default Absence;
