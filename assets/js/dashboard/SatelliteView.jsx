import React from 'react';
import SatelliteSelect from './SatelliteSelect';
import MapView from './MapView';
import {Tle} from 'tle.js';
import Color from './Color';
import Propagator from './Propagator';

const interval = 1000;

export default class SatelliteView extends React.Component {

  constructor (props) {
    super(props);

    let satellites = JSON.parse(localStorage.getItem('satellites')) || [];
    satellites.map(satellite => {
      satellite.tle = new Tle(satellite.tle);
    });

    this.color = new Color();

    this.state = {};
    this.date = new Date();
    this.state.selected = satellites;
    this.state.orbits = 2;
    this.state.satellites = this.setupSatellites(satellites);
    this.state.increase = 1000;

    this.interval = setInterval(this.onInterval.bind(this), interval);
  }

  setupSatellites (satellites) {
    return satellites.map(satellite => {
      return this.setupSatellite(satellite);
    });
  }

  setupSatellite (satellite) {
    satellite.sgp4 = Propagator.getSGP4(satellite);
    satellite.tracks = Propagator.precalculate(satellite.sgp4, satellite, this.date, this.state.orbits);
    satellite.color = this.color.new();

    return satellite;
  }

  onSatelliteChange (value) {
    let satellites = [];

    value.map((satellite) => {
      satellites[satellite.tle.satelliteId] = this.setupSatellite(satellite);
    });

    this.setState({
      satellites: satellites,
      selected: value
    });

    localStorage.setItem('satellites', JSON.stringify(value));
  }

  onInterval () {
    this.date = new Date(this.date.getUTCMilliseconds() + this.state.increase);

    Object.keys(this.state.satellites).map((id) => {
      let satellite = this.state.satellites[id];

      this.mapUpdate(satellite.tle, satellite.sgp4.latlng(this.date));
    });
  }

  componentDidMount () {
    this.onInterval();
  }

  componentWillUnmount () {
    clearInterval(this.interval);
  }

  eachSatellite (func) {
    return this.state.satellites.map(satellite => {
      return func(satellite);
    });
  }

  render () {
    return <React.Fragment>
      <nav className="navbar bg-primary" id="nav-main">
        <div className="container-fluid">
          <div className="col-4">
            <SatelliteSelect url={this.props.url} multiple={true} onChange={this.onSatelliteChange.bind(this)} value={this.state.selected}/>
          </div>
        </div>
      </nav>
      <MapView setUpdate={update => this.mapUpdate = update}
               satellites={this.state.satellites}/>
    </React.Fragment>;
  }
}
