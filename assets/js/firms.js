export default class Firms
{

  constructor()
  {
    window.renderFirms = this.renderFirms.bind(this);
  }

  renderFirms()
  {
    this.map = new google.maps.Map(document.getElementById('firms'), {
      center: {lat: 44.787197, lng: 20.457273},
      zoom: 6,
    });

    fetch('api/firms').
        then(response => response.json()).
        then(response => this.renderHeatMap(response));
  }

  renderHeatMap(response)
  {
    this.heatmap = new HeatmapOverlay(
        this.map,
        {
          // radius should be small ONLY if scaleRadius is true (or small radius is intended)
          'radius': 10,
          'maxOpacity': 0.5,
          // scales the radius based on map zoom
          'scaleRadius': false,
          // if set to false the heatmap uses the global maximum for colorization
          // if activated: uses the data maximum within the current map boundaries
          //   (there will always be a red spot with useLocalExtremas true)
          'useLocalExtrema': true,
          // which field name in your data represents the latitude - default "lat"
          latField: 'latitude',
          // which field name in your data represents the longitude - default "lng"
          lngField: 'longitude',
          // which field name in your data represents the data value - default "value"
          valueField: 'brightness',
        },
    );

    let data = response.map(item => {
      return {
        latitude: item.latitude,
        longitude: item.longitude,
        brightness: item.brightness1,
      };
    });

    console.log(data);

    var testData = {
      max: 600,
      data: data
    };

    this.heatmap.setData(testData);
  }
}
