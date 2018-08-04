import Day from './Day';

class Month {
  constructor(name, date, hash, days) {
    this.name = name;
    this.date = date;
    this.hash = hash;
    this.days = days;
  }

  static fromData(data) {
    const { name, hash } = data;
    const date = new Date(data.date);

    const days = data.days.map(Day.fromData) // Return [timestamp, Day]
      .sort(([t1], [t2]) => t1 - t2); // Sort by timestamp ASC

    return new Month(name, date, hash, new Map(days));
  }
}

export default Month;
