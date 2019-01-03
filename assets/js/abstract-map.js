export const primary = '#2C3E4E';

export const style = [
  {
    'featureType': 'all',
    'elementType': 'labels.text.fill',
    'stylers': [
      {
        'saturation': 36
      },
      {
        'color': primary
      },
      {
        'lightness': 38
      }
    ]
  },
  {
    'featureType': 'all',
    'elementType': 'labels.text.stroke',
    'stylers': [
      {
        'visibility': 'on'
      },
      {
        'color': primary
      },
      {
        'lightness': 13
      }
    ]
  },
  {
    'featureType': 'all',
    'elementType': 'labels.icon',
    'stylers': [
      {
        'visibility': 'off'
      }
    ]
  },
  {
    'featureType': 'administrative',
    'elementType': 'geometry.fill',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 17
      }
    ]
  },
  {
    'featureType': 'administrative',
    'elementType': 'geometry.stroke',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 14
      },
      {
        'weight': 1.2
      }
    ]
  },
  {
    'featureType': 'landscape',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 18
      }
    ]
  },
  {
    'featureType': 'poi',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 15
      }
    ]
  },
  {
    'featureType': 'road.highway',
    'elementType': 'geometry.fill',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 14
      }
    ]
  },
  {
    'featureType': 'road.highway',
    'elementType': 'geometry.stroke',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 26
      },
      {
        'weight': 0.2
      }
    ]
  },
  {
    'featureType': 'road.arterial',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 14
      }
    ]
  },
  {
    'featureType': 'road.local',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 13
      }
    ]
  },
  {
    'featureType': 'transit',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 16
      }
    ]
  },
  {
    'featureType': 'water',
    'elementType': 'geometry',
    'stylers': [
      {
        'color': primary
      },
      {
        'lightness': 12
      }
    ]
  }
];

export default class AbstractMap {
  constructor () {
    this.style = style;
  }
}
