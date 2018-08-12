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

      this.locations = [
        ['Majori', {lat: 56.9720962, lng: 23.7867654}, [{year: 1989, lnk: '#'}]],
        ['Ergļi', {lat: 56.9026381, lng: 25.5727127}, [{year: 1990, lnk: '#'}, {
          year: 1991,
          lnk: '#'
        }, {year: 2009, lnk: '#'}]],
        ['Sigulda', {lat: 57.1633299, lng: 24.8103712}, [{year: 1992, lnk: '#'}, {
          year: 1993,
          lnk: '#'
        }, {year: 1994, lnk: '#'}]],
        ['Irbene', {lat: 57.5601348, lng: 21.8514777}, [{year: 1995, lnk: '#'}]],
        ['Riekstukalns', {lat: 56.7733871, lng: 24.4019641}, [{year: 1996, lnk: '#'}]],
        ['Rucava', {lat: 56.2167009, lng: 20.9549537}, [{year: 1997, lnk: '#'}]],
        ['Zentene', {lat: 57.168644, lng: 22.8920529}, [{year: 1998, lnk: '#'}]],
        ['Burtnieki', {lat: 57.6923114, lng: 25.2647041}, [{year: 1999, lnk: '#'}]],
        ['Vabole', {lat: 56.0308864, lng: 26.4416316}, [{year: 2000, lnk: '#'}]],
        ['Ļaudona', {lat: 56.689893, lng: 26.0055484}, [{year: 2001, lnk: '#'}]],
        ['Zentene', {lat: 57.168644, lng: 22.8920529}, [{year: 2002, lnk: '#'}]],
        ['Jūrkalne', {lat: 57.0075391, lng: 21.383548}, [{year: 2003, lnk: '#'}]],
        ['Kocēni', {lat: 57.5284492, lng: 24.8702506}, [{year: 2004, lnk: '#'}]],
        ['Korģene', {lat: 57.7690316, lng: 24.5329867}, [{year: 2005, lnk: '#'}]],
        ['Augstkalne', {lat: 56.4063672, lng: 23.3291487}, [{year: 2006, lnk: '#'}]],
        ['Viļķene', {lat: 57.6051234, lng: 24.4638718}, [{year: 2007, lnk: '#'}, {year: 2008, lnk: '#'}]],
        ['Nereta', {lat: 56.3232403, lng: 25.311714}, [{year: 2010, lnk: '#'}]],
        ['Viesīte', {lat: 56.3449555, lng: 25.5429385}, [{year: 2011, lnk: '#'}]],
        ['Suntaži', {lat: 56.9046595, lng: 24.9066061}, [{year: 2012, lnk: '#'}]],
        ['Piltene', {lat: 57.2282828, lng: 21.690247}, [{year: 2013, lnk: '#'}]],
        ['Valmiera', {lat: 57.5310966, lng: 25.4131496}, [{year: 2014, lnk: '#'}]],
        ['Mālpils', {lat: 57.0069641, lng: 24.9153363}, [{year: 2015, lnk: '#'}]],
        ['Mazsalaca', {lat: 57.8629072, lng: 25.0305959}, [{year: 2016, lnk: '#'}]],
        ['Mērsrags', {lat: 57.3446637, lng: 23.1087438}, [{year: 2017, lnk: '#'}]],
        ['Ķoņi', {lat: 57.9547515, lng: 25.3406828}, [{year: 2018, lnk: '#'}]],
      ];

      this.openInfoWindow = false;
      this.init(options);
    }

    closeInfoWindow() {
      if (this.openInfoWindow) {
        this.openInfoWindow.close();
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

      this.locations.forEach(location => this.pointDraw(location, map, icon))

    }

    pointDraw(location, map, icon) {
      let content = `<span class='lnk'>${location[0]}</span>` +
        location[2].map(event =>
          `<a class='lnk' href='${event.lnk}'>${event.year}</a>`
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
        infoWindow.open(map, marker);
        this.closeInfoWindow();
        this.openInfoWindow = infoWindow;
      });
      map.addListener('click', () => this.closeInfoWindow());
    }
  }

  return pub;
})(window.jQuery);
