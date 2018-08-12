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

            this.init(options);
        }
        afterInit () {
            "use strict";
            const w = this;
            if (w.initWidgetElement()) {
                w.run();
            }
        }
<<<<<<< HEAD
        run () {
            const w = this;
            w.renderState();
        }
        renderState () {
            const w = this;
        }
=======
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
>>>>>>> events-cms
    }

    return pub;
})(window.jQuery);

