import Chart from "chart.js";
import moment from "moment";
import $ from "jquery";
import AbstractMap from "./abstract-map";

let config = {
  type: "line",
  data: {
    labels: [],
    datasets: [
      {
        backgroundColor: "#5DA5DA",
        borderColor: "#5DA5DA",
        data: [],
        fill: false,
        label: "Air Temperature",
      },
      {
        backgroundColor: "#60BD68",
        borderColor: "#60BD68",
        data: [],
        fill: false,
        label: "Dew Point Temperature",
      },
      {
        backgroundColor: "#F15854",
        borderColor: "#F15854",
        data: [],
        fill: false,
        label: "Pressure",
        yAxisID: "pressure",
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
      display: false,
    },
    tooltips: {
      intersect: false,
      mode: "index",
    },
    hover: {
      intersect: true,
      mode: "nearest",
    },
    scales: {
      xAxes: [
        {
          display: true,
          scaleLabel: {
            display: true,
            labelString: "UTC",
          },
        },
      ],
      yAxes: [
        {
          ID: "temperature",
          position: "right",
          display: true,
          scaleLabel: {
            display: true,
            labelString: "°C",
          },
          ticks: {
            beginAtZero: true,
          },
        },
        {
          scaleLabel: {
            display: true,
            labelString: "hPa",
          },
          id: "pressure",
          type: "linear",
          gridLines: {
            display: false,
          },
        },
      ],
    },
  },
};

let renderChart = function (canvas, response) {
  let labels = response.member.map(function (element) {
    return moment(element.date).format("HH:mm DD-MM-YYYY");
  });

  let temperature = response.member.map(function (element) {
    return element.temperature !== null
      ? element.temperature.value
      : null;
  });

  let dew = response.member.map(function (element) {
    return element.dewPoint !== null ? element.dewPoint.value : null;
  });

  let pressure = response.member.map(function (element) {
    return element.pressure !== null ? element.pressure.value : null;
  });

  config.data.labels = labels.reverse();
  config.data.datasets[0].data = temperature.reverse();
  config.data.datasets[1].data = dew.reverse();
  config.data.datasets[2].data = pressure.reverse();

  let ctx = canvas[0].getContext("2d");
  new Chart(ctx, config);
};

let createMeterTemperatureChart = function (canvas) {
  fetch(canvas.data("metar-url"))
    .then((metar) => metar.json())
    .then((metar) => {
      renderChart(canvas, metar);
    });

  let map = new Map();
  map.render($("#airport-map"));
};

class Map extends AbstractMap {
  render (element) {
    let latitude = element.data("latitude");
    let longitude = element.data("longitude");

    if (!document.getElementById("airport-map")) {
      return;
    }

    this.map = new google.maps.Map(document.getElementById("airport-map"), {
      center: {lat: latitude, lng: longitude},
      styles: this.style,
      zoom: 10,
    });

    new google.maps.Marker({
      map: this.map,
      position: {lat: latitude, lng: longitude},
    });
  }
}

export {createMeterTemperatureChart};
