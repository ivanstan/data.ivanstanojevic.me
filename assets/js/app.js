import Firms from './firms';

let $ = require('jquery');

require('bootstrap');
require('./menu');

require('../scss/app.scss');

let temperature = $('.meter-temperature');
if (temperature.length > 0) {
  let meter = require('./airport');
  meter.createMeterTemperatureChart(temperature);
}

let firmsEl = $('#firms');
if (firmsEl.length > 0) {
  new Firms(firmsEl);
}
