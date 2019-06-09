export const primary = "#2C3E4E";

export const style = [
  {
    "elementType": "labels.text.fill",
    "featureType": "all",
    "stylers": [
      {
        "saturation": 36,
      },
      {
        "color": primary,
      },
      {
        "lightness": 38,
      },
    ],
  },
  {
    "elementType": "labels.text.stroke",
    "featureType": "all",
    "stylers": [
      {
        "visibility": "on",
      },
      {
        "color": primary,
      },
      {
        "lightness": 13,
      },
    ],
  },
  {
    "elementType": "labels.icon",
    "featureType": "all",
    "stylers": [
      {
        "visibility": "off",
      },
    ],
  },
  {
    "elementType": "geometry.fill",
    "featureType": "administrative",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 17,
      },
    ],
  },
  {
    "elementType": "geometry.stroke",
    "featureType": "administrative",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 14,
      },
      {
        "weight": 1.2,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "landscape",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 18,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "poi",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 15,
      },
    ],
  },
  {
    "elementType": "geometry.fill",
    "featureType": "road.highway",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 14,
      },
    ],
  },
  {
    "elementType": "geometry.stroke",
    "featureType": "road.highway",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 26,
      },
      {
        "weight": 0.2,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "road.arterial",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 14,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "road.local",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 13,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "transit",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 16,
      },
    ],
  },
  {
    "elementType": "geometry",
    "featureType": "water",
    "stylers": [
      {
        "color": primary,
      },
      {
        "lightness": 12,
      },
    ],
  },
];

export default class AbstractMap {
  constructor () {
    this.style = style;
  }
}
