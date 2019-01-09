export default class Color {
  // http://paletton.com/
  // http://www.hexcolortool.com/
  // http://www.color-hex.com/

  constructor () {
    this.nextSeries = 0;
    this.series = [
      '111F74',
      '515FB6',
      '7985CF',
      '074C6A',
      '0F6388',
      '287799',
      '428AA9'
    ];
  }

  new () {
    let color = this.series[this.nextSeries];
    this.nextSeries++;
    return '#' + color;
  }

  static get primaryLight () {
    return '4757bd';
  }

  static get primaryNormal () {
    return '3646a7';
  }

  static get primaryDark () {
    return '263174';
  }

  get maxSeries () {
    return this.series.length;
  }

  static hexToRGB (hex) {
    let shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
      return r + r + g + g + b + b;
    });

    let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }
}
