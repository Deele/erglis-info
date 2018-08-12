window.yii.eventsMapWidget = ($ => {
  "use strict";

  /**
  * Widget type settings
  */
  let pub = {
    settings: {
      name: ''
    }
  };

  /**
  * Registers new instance of widget
  * @param {string} widgetId
  * @param {object} [options]
  */
  pub.register = (widgetId, options) => {
    return new EventsMapWidget(widgetId, pub, options);
  };

  /**
  * Company parameter type category
  *
  * @property {int} id
  * @property {string} title
  * @property-read {jQuery} element
  * @property {boolean} isSelected
  */
  class EventsMapWidget extends YiiWebAppWidget {

    /**
     * @param {string} id
     * @param {object} parent
     * @param {object} [options]
     */

    constructor(id, parent, options) {
      super(id, parent, options);

      this.settings.baseUrl = '';
      this.settings.mapStyles = [
        {
          "featureType": "all",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "saturation": 36
            },
            {
              "color": "#000000"
            },
            {
              "lightness": 40
            }
          ]
        },
        {
          "featureType": "all",
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#000000"
            },
            {
              "lightness": 16
            }
          ]
        },
        {
          "featureType": "all",
          "elementType": "labels.icon",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "administrative",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 20
            }
          ]
        },
        {
          "featureType": "administrative",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 17
            },
            {
              "weight": 1.2
            }
          ]
        },
        {
          "featureType": "landscape",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 20
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 21
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 17
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 29
            },
            {
              "weight": 0.2
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 18
            }
          ]
        },
        {
          "featureType": "road.local",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 16
            }
          ]
        },
        {
          "featureType": "transit",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 19
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#000000"
            },
            {
              "lightness": 17
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "saturation": "-100"
            },
            {
              "lightness": "-100"
            },
            {
              "gamma": "0.00"
            }
          ]
        }
      ];

      this.settings.locations = [];

      this.openInfoWindow = false;
      this.init(options);
    }

    closeInfoWindow() {
      if (this.openInfoWindow) {
        this.openInfoWindow.close();
        this.openInfoWindow = false;
      }
    }

    afterInit() {
      "use strict";
      if (this.initWidgetElement()) {
        this.run();
      }
    }

    run() {
      const w = this;
      let mapCenter = {lat: 57, lng: 24.5};

      const map = new google.maps.Map(document.getElementById('map'), {
        center: mapCenter,
        zoom: 7,
        styles: w.settings.mapStyles,
      });

      let icon = this.settings.baseUrl + '/aquila.png';

      $.each(this.settings.locations, (key, location) => {
        w.pointDraw(location, map, icon);
      });
    }

    pointDraw(location, map, icon) {
      let content = `<span class='lnk'>${location[0]}</span>` +
        location[2].map(event =>
          `<a class='lnk' href='${event.url}'>${event.title}</a>`
        ).join('');
      let infoWindow = new google.maps.InfoWindow({
        content,
      });
      let marker = new google.maps.Marker({
        map: map,
        position: location[1],
        title: location[0],
        icon,
      });
      marker.addListener('click', () => {
        this.closeInfoWindow();
        infoWindow.open(map, marker);
        this.openInfoWindow = infoWindow;
      });
      map.addListener('click', () => this.closeInfoWindow());
    }
  }

  return pub;
})(window.jQuery);
