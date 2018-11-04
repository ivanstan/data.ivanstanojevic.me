import AbstractMap from './abstract-map';

export default class Firms extends AbstractMap
{
  constructor()
  {
    super();
    window.renderFirms = this.renderFirms.bind(this);
  }

  renderFirms()
  {
    this.map = new google.maps.Map(document.getElementById('firms'), {
      center: {lat: 44.787197, lng: 20.457273},
      styles: this.style,
      zoom: 6,
    });

    fetch('api/firms').
        then(response => response.json()).
        then(response => this.renderMarkers(response));
  }

  renderMarkers(response)
  {
    let self = this;

    for (let i in response) {
      let data = response[i];

      let marker = new google.maps.Marker({
        map: this.map,
        position: {lat: data.latitude, lng: data.longitude},
        data: data,
      });
      marker.addListener('mouseover', function(e) {
        self.markerClick(this, self);
      });

      this.infoBox = new InfoBox({
        disableAutoPan: false,
        pixelOffset: new google.maps.Size(-140, 0),
        zIndex: null,
        boxStyle: {
          padding: '0px 0px 0px 0px',
          width: '252px',
          height: '40px',
        },
        closeBoxURL: '',
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: 'floatPane',
        enableEventPropagation: false,
      });
    }
  }

  markerClick(marker, self)
  {
    let data = marker.data;

    this.infoBox.setContent(data.latitude);
    this.infoBox.open(self.map, marker);
  }
}
