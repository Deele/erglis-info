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
        ['Majori', {lat: 56.9720962, lng: 23.7867654}, [{year: 1989, lnk: 'qwer'}]],
        ['Ergļi', {lat: 56.9026381, lng: 25.5727127}, [{year: 1990, lnk: 'qwer'}, {
          year: 1991,
          lnk: 'qwer'
        }, {year: 2009, lnk: 'qwer'}]],
        ['Sigulda', {lat: 57.1633299, lng: 24.8103712}, [{year: 1992, lnk: 'qwer'}, {
          year: 1993,
          lnk: 'qwer'
        }, {year: 1994, lnk: 'qwer'}]],
        ['Irbene', {lat: 57.5601348, lng: 21.8514777}, [{year: 1995, lnk: 'qwer'}]],
        ['Riekstukalns', {lat: 56.7733871, lng: 24.4019641}, [{year: 1996, lnk: 'qwer'}]],
        ['Rucava', {lat: 56.2167009, lng: 20.9549537}, [{year: 1997, lnk: 'qwer'}]],
        ['Zentene', {lat: 57.168644, lng: 22.8920529}, [{year: 1998, lnk: 'qwer'}]],
        ['Burtnieki', {lat: 57.6923114, lng: 25.2647041}, [{year: 1999, lnk: 'qwer'}]],
        ['Vabole', {lat: 56.0308864, lng: 26.4416316}, [{year: 2000, lnk: 'qwer'}]],
        ['Ļaudona', {lat: 56.689893, lng: 26.0055484}, [{year: 2001, lnk: 'qwer'}]],
        ['Zentene', {lat: 57.168644, lng: 22.8920529}, [{year: 2002, lnk: 'qwer'}]],
        ['Jūrkalne', {lat: 57.0075391, lng: 21.383548}, [{year: 2003, lnk: 'qwer'}]],
        ['Kocēni', {lat: 57.5284492, lng: 24.8702506}, [{year: 2004, lnk: 'qwer'}]],
        ['Korģene', {lat: 57.7690316, lng: 24.5329867}, [{year: 2005, lnk: 'qwer'}]],
        ['Augstkalne', {lat: 56.4063672, lng: 23.3291487}, [{year: 2006, lnk: 'qwer'}]],
        ['Viļķene', {lat: 57.6051234, lng: 24.4638718}, [{year: 2007, lnk: 'qwer'}, {year: 2008, lnk: 'qwer'}]],
        ['Nereta', {lat: 56.3232403, lng: 25.311714}, [{year: 2010, lnk: 'qwer'}]],
        ['Viesīte', {lat: 56.3449555, lng: 25.5429385}, [{year: 2011, lnk: 'qwer'}]],
        ['Suntaži', {lat: 56.9046595, lng: 24.9066061}, [{year: 2012, lnk: 'qwer'}]],
        ['Piltene', {lat: 57.2282828, lng: 21.690247}, [{year: 2013, lnk: 'qwer'}]],
        ['Valmiera', {lat: 57.5310966, lng: 25.4131496}, [{year: 2014, lnk: 'qwer'}]],
        ['Mālpils', {lat: 57.0069641, lng: 24.9153363}, [{year: 2015, lnk: 'qwer'}]],
        ['Mazsalaca', {lat: 57.8629072, lng: 25.0305959}, [{year: 2016, lnk: 'qwer'}]],
        ['Mērsrags', {lat: 57.3446637, lng: 23.1087438}, [{year: 2017, lnk: 'qwer'}]],
        ['Ķoņi', {lat: 57.9547515, lng: 25.3406828}, [{year: 2018, lnk: 'qwer'}]],
      ];

      this.init(options);
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

      this.drawPoints(map);

    }

    drawPoints(map) {
      let icon = this.settings.baseUrl + '/aquila.png';

      this.locations.forEach(location => {
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
        marker.addListener('click', function () {
          infoWindow.open(map, marker);
        });
      })
    }
  }

  return pub;
})(window.jQuery);
