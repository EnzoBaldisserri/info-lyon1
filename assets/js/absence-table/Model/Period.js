import Month from './Month';

class Period {
  months = [];
  days = [];

  constructor(months) {
    this.months = months;
    this.days = new Map(months.reduce((carry, month) => ([
      ...carry,
      ...month.days,
    ]), []));
  }

  getDay(date) {
    const myDate = new Date(date);
    myDate.setHours(0, 0, 0, 0);

    return this.days.get(myDate.getTime());
  }

  get firstDay() {
    const firstDay = this.days.values().next().value;

    if (!firstDay) {
      return null;
    }

    return firstDay.date;
  }

  get daysAsArray() {
    return Array.from(this.days.values());
  }

  static fromData(data) {
    const months = data.map(Month.fromData);
    return new Period(months);
  }
}

export default Period;
