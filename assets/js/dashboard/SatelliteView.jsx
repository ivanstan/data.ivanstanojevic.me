import React from 'react';
import SatelliteSelect from './SatelliteSelect';
import MapView from './MapView';
import {eciToGeodetic, propagate, twoline2satrec, gstime} from 'satellite.js';

const interval = 1000;

export default class SatelliteView extends React.Component {

  constructor (props) {
    super(props);

    let satellites = JSON.parse(localStorage.getItem('satellites')) || [];

    this.state = {
      selected: satellites,
      satellites: this.setupSatellites(satellites),
      date: new Date(),
      increase: 1000,
      orbits: 2
    };

    this.interval = setInterval(this.onInterval.bind(this), interval);
  }

  setupSatellites (value) {
    let satellites = [];

    value.map((tle) => {
      let satRec = twoline2satrec(tle.line1, tle.line2);

      console.log(satRec);

      let orbitalPerios = 1440.0/Number(orbital_elements["mean_motion"])

      satellites[tle.satelliteId] = {
        tle: tle,
        satRec: satRec
      };
    });

    return satellites;
  }

  onSatelliteChange (value) {
    this.setState({
      satellites: this.setupSatellites(value),
      selected: value
    });

    localStorage.setItem('satellites', JSON.stringify(value));
  }

  onInterval () {
    this.setState({
      date: new Date(this.state.date.getUTCMilliseconds() + this.state.increase)
    });

    Object.keys(this.state.satellites).map((id) => {
      let satellite = this.state.satellites[id];

      let eci = propagate(satellite.satRec, this.state.date);

      let gmst = gstime(new Date());
      let geodetic = eciToGeodetic(eci.position, gmst);

//      console.log(geodetic, gmst);

//      this.mapUpdate(satellite.tle, geodetic);
    });
  }

  componentDidMount () {
    this.onInterval();
  }

  componentWillUnmount () {
    clearInterval(this.interval);
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
      <MapView setUpdate={update => this.mapUpdate = update}/>
    </React.Fragment>;
  }
}
