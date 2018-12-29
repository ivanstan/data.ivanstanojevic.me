import ReactDOM from 'react-dom';
import React from 'react';
import SatelliteView from './SatelliteView';

let element = document.getElementById('satellite-view');
let url = element.getAttribute('data-tle-api');

ReactDOM.render(<SatelliteView url={url}/>, element);
