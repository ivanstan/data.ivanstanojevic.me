import React from 'react';
import {style} from '../abstract-map';

const mapStyle = {
  minHeight: parseInt(window.innerHeight - 80) + 'px'
};

export default class MapView extends React.Component {

  constructor (props) {
    super(props);

    this.element = React.createRef();
    this.markers = {};
  }

  componentDidMount () {
    this.props.setUpdate(this.update.bind(this));

    this.map = new google.maps.Map(this.element.current, {
      center: {lat: 44.787197, lng: 20.457273},
      streetViewControl: false,
      styles: style,
      zoom: 6
    });
  }

  update (tle, geodetic) {
    let position = new google.maps.LatLng(geodetic.latitude, geodetic.longitude);

//    if (this.markers.hasOwnProperty(tle.satelliteId)) {
//
//      console.log(this.markers[tle.satelliteId]);
//
//      this.markers[tle.satelliteId].setPosition(position);
//
//      return;
//    }

    if (!this.markers.hasOwnProperty(tle.satelliteId)) {

      console.log(geodetic);

      this.markers[tle.satelliteId] = new google.maps.Marker({
        map: this.map,
        position: position,
        title: tle.name,
        draggable: false
      });
    }
  }

  componentWillMount () {
    this.map = null;
  }

  render () {
    return <div style={mapStyle} ref={this.element}/>;
  }
}
