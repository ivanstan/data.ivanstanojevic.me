/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single scss file (app.scss in this case)
require('../scss/app.scss');
let meter = require('./airport');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
let $ = require('jquery');

let temperature = $('.meter-temperature');

if (temperature.length > 0) {
  meter.createMeterTemperatureChart(temperature);
}
