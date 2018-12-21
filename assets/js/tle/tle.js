export default class Tle
{
  constructor (tleModel) {
    this.name = tleModel.name;
    this.line1 = tleModel.line1;
    this.line2 = tleModel.line2;
  }

  getDate () {
    let year = parseInt(this.line1.substring(18, 20));
    year = Tle.formatYear(year);

    let epoch = parseFloat(this.line1.substring(20, 32));
    let days = parseInt(epoch);

    let date = new Date(Date.UTC(year, 0, days));

    let faction = Math.round(epoch - days);

    faction *= 24; // hours
    let hours = Math.round(faction);
    faction -= hours;

    faction *= 60; // minutes
    let minutes = Math.round(faction);
    faction -= minutes;

    faction *= 60; // seconds
    let seconds = Math.round(faction);
    faction -= seconds;

    faction *= 1000; // milliseconds
    let milliseconds = Math.round(faction);

    date.setUTCHours(hours);
    date.setUTCMinutes(minutes);
    date.setUTCSeconds(seconds);
    date.setUTCMilliseconds(milliseconds);

    return date;
  }

  static formatYear (twoDigitYear) {
    if (twoDigitYear < 57) {
      twoDigitYear += 2000;
    } else {
      twoDigitYear += 1900;
    }

    return twoDigitYear;
  }
}
