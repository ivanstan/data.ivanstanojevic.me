import AbstractMap from './abstract-map';
import moment from 'moment';

export default class Firms extends AbstractMap {
  constructor () {
    super();
    window.renderFirms = this.renderFirms.bind(this);
  }

  renderFirms () {
    this.icon = $('#firms').data('icon');
    this.map = new google.maps.Map(document.getElementById('firms'), {
      center: {lat: 44.787197, lng: 20.457273},
      styles: this.style,
      zoom: 6
    });

    fetch('api/firms')
      .then(response => response.json())
      .then(response => this.renderMarkers(response));
  }

  renderMarkers (response) {
    let self = this;

    for (let i in response) {
      let data = response[i];

      let marker = new google.maps.Marker({
        map: this.map,
        position: {lat: data.latitude, lng: data.longitude},
        data: data,
        icon: this.icon
      });
      marker.addListener('mouseover', function (e) {
        self.markerClick(this, self);
      });

      this.infoBox = new InfoBox({
        disableAutoPan: false,
        pixelOffset: new google.maps.Size(-140, 0),
        zIndex: null,
        boxStyle: {
          padding: '5px',
          width: '200px',
          height: '40px'
        },
        closeBoxURL: '',
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: 'floatPane',
        enableEventPropagation: false
      });
    }
  }

  markerClick (marker, self) {
    let data = marker.data;
    let date = moment(data.date.timestamp * 1000).format('DD-MM-Y HH:mm');

    this.infoBox.setContent(
      `<div style="background: rgba(44, 62, 80, 0.75); color: #6589A8; padding: 5px">` +
        `Time: ${date}<br>` +
        `Temp. ${data.brightness1} K` +
        ` </div>`
    );
    this.infoBox.open(self.map, marker);
  }
}
