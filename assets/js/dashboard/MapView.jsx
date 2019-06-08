import React from 'react';
import { primary, style } from '../abstract-map';

const mapStyle = {
  minHeight: parseInt(window.innerHeight - 80) + 'px'
};

export default class MapView extends React.Component {

  constructor(props) {
    super(props);

    this.satellites = {};
    this.element = React.createRef();
  }

  componentDidMount() {
    this.props.setUpdate(this.update.bind(this));

    this.setupMap();

    Object.keys(this.props.satellites).map(satelliteId => {
      this.addSatellite(this.props.satellites[satelliteId]);
    });
  }

  componentWillReceiveProps(nextProps, nextContext) {
    let removed = this.props.satellites.filter(index => {
      return nextProps.satellites.indexOf(index) < 0;
    });
    let added = nextProps.satellites.filter(index => {
      return this.props.satellites.indexOf(index) < 0;
    });

    removed.map(satellite => {
      this.removeSatellite(satellite);
    });

    added.map(satellite => {
      this.addSatellite(satellite);
    });
  }

  componentWillUnmount() {
    this.map = null;
  }

  setupMap() {
    this.map = new google.maps.Map(this.element.current, {
      center: new google.maps.LatLng(10, 0),
      zoom: 2,
      minZoom: 2,
      streetViewControl: false,
      styles: style,
      backgroundColor: primary
    });

//    google.maps.event.addListener(this.map, 'center_changed', () => {
//      let latNorth = this.map.getBounds().getNorthEast().lat();
//      let latSouth = this.map.getBounds().getSouthWest().lat();
//      let newLat;
//
//      /* too north, centering */
//      if (latNorth > 85) {
//        newLat = this.map.getCenter().lat() - (latNorth - 85);
//      }
//
//      /* too south, centering */
//      if (latSouth < -85) {
//        newLat = this.map.getCenter().lat() - (latSouth + 85);
//      }
//
//      if (newLat) {
//        let newCenter = new google.maps.LatLng(newLat, this.map.getCenter().lng());
//        this.map.setCenter(newCenter);
//      }
//    });
  }

  update(tle, geodetic) {
    let position = new google.maps.LatLng(geodetic.latitude, geodetic.longitude);

    if (this.satellites[tle.satelliteId].hasOwnProperty('marker')) {
      this.satellites[tle.satelliteId].marker.setPosition(position);
      return;
    }

    if (!this.satellites[tle.satelliteId].hasOwnProperty('marker')) {
      this.satellites[tle.satelliteId].marker = new google.maps.Marker({
        map: this.map,
        position: position,
        title: tle.name,
        draggable: false,
        icon: this.getMarkerImage(this.satellites[tle.satelliteId])
      });

      google.maps.event.addListener(this.satellites[tle.satelliteId].marker, 'click', () => {
//        this._propagator.setContext(marker);
      });
    }
  }

  getMarkerImage(satellite) {
    let color = satellite.color.substring(1, satellite.color.length);

    return new google.maps.MarkerImage(
      'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|' + color,
      new google.maps.Size(21, 34),
      new google.maps.Point(0, 0),
      new google.maps.Point(10, 34)
    );
  }

  addSatellite(satellite) {
    let satelliteId = satellite.tle.satelliteId;

    if (!this.satellites.hasOwnProperty(satelliteId)) {
      this.satellites[satellite.tle.satelliteId] = {};
    }

    // setup tracks

    if (satellite.hasOwnProperty('tracks')) {
      let tracks = [];
      Object.keys(satellite.tracks).map(time => {
        tracks.push(new google.maps.LatLng(satellite.tracks[time].latitude, satellite.tracks[time].longitude));
      });

      this.satellites[satelliteId].tracks = new google.maps.Polyline({
        path: tracks,
        geodesic: true,
        strokeColor: satellite.color,
        strokeOpacity: 0.5,
        strokeWeight: 2
      });

      this.satellites[satelliteId].tracks.setMap(this.map);

      this.satellites[satelliteId].color = satellite.color;
    }
  }

  removeSatellite(satellite) {
    let satelliteId = satellite.tle.satelliteId;

    if (this.satellites[satelliteId].hasOwnProperty('marker')) {
      this.satellites[satelliteId].marker.setMap(null);
    }

    if (this.satellites[satelliteId].hasOwnProperty('tracks')) {
      this.satellites[satelliteId].tracks.setMap(null);
    }

    delete this.satellites[satelliteId];
  }

  render() {
    return <div style={mapStyle} ref={this.element}/>;
  }
}
