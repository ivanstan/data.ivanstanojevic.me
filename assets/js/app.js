import Firms from './firms';

// any CSS you require will output into a single scss file (app.scss in this case)
require('../scss/app.scss');
require('./menu');

let meter = require('./airport');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
let $ = require('jquery');

let temperature = $('.meter-temperature');
if (temperature.length > 0) {
  meter.createMeterTemperatureChart(temperature);
}

let firmsEl = $('#firms');
if (firmsEl.length > 0) {
  let firms = new Firms(firmsEl);
}
