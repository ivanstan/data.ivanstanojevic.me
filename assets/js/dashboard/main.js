import ReactDOM from "react-dom";
import React from "react";
import SatelliteView from "./SatelliteView";

let element = document.getElementById("satellite-view");

ReactDOM.render(React.createElement(SatelliteView, { url: element.getAttribute("data-tle-api") }), element);
