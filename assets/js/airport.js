import Chart from 'chart.js';
import moment from 'moment';

let config = {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
        label: 'Air Temperature',
        backgroundColor: '#5DA5DA',
        borderColor: '#5DA5DA',
        data: [],
        fill: false,
      },
      {
        label: 'Dew Point Temperature',
        backgroundColor: '#60BD68',
        borderColor: '#60BD68',
        data: [],
        fill: false,
      },
      {
        label: 'Pressure',
        yAxisID: 'pressure',
        backgroundColor: '#F15854',
        borderColor: '#F15854',
        data: [],
        fill: false,
      },
    ],
  },
  options: {
    elements: {
      line: {
        tension: 0,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    title: {
      display: true,
      text: 'METAR',
    },
    tooltips: {
      mode: 'index',
      intersect: false,
    },
    hover: {
      mode: 'nearest',
      intersect: true,
    },
    scales: {
      xAxes: [
        {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'UTC',
          },
        },
      ],
      yAxes: [
        {
          ID: 'temperature',
          position: 'right',
          display: true,
          scaleLabel: {
            display: true,
            labelString: '°C',
          },
          ticks: {
            beginAtZero: true,
          },
        },
        {
          id: 'pressure',
          type: 'linear',
          scaleLabel: {
            display: true,
            labelString: 'hPa',
          },
          gridLines: {
            display: false
          },
        },
      ],
    },
  },
};

let createMeterTemperatureChart = function(canvas) {
  fetch(canvas.data('url')).
      then((response) => response.json()).
      then(function(response) {

        let labels = response.member.map(function(element) {
          return moment(element.date).format('HH:mm DD-MM-YYYY');
        });

        let temperature = response.member.map(function(element) {
          return element.temperature !== null
              ? element.temperature.value
              : null;
        });

        let dew = response.member.map(function(element) {
          return element.dewPoint !== null ? element.dewPoint.value : null;
        });

        let pressure = response.member.map(function(element) {
          return element.pressure !== null ? element.pressure.value : null;
        });

        config.data.labels = labels.reverse();
        config.data.datasets[0].data = temperature.reverse();
        config.data.datasets[1].data = dew.reverse();
        config.data.datasets[2].data = pressure.reverse();

        let ctx = canvas[0].getContext('2d');
        new Chart(ctx, config);
      });
};

export {createMeterTemperatureChart};
