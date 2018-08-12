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

      this.locations = [
        ['1989. Majori', 56.9720962, 23.7867654],
        ['1990. Ērgļi', 56.9026381, 56.9026381],
        ['1991. Ērgļi', 56.9026381, 56.9026381],
        ['1992. Sigulda', 57.1633299, 24.8103712],
        ['1993. Sigulda', 57.1633299, 24.8103712],
        ['1994. Sigulda', 57.1633299, 24.8103712],
        ['1995. Irbene', 57.5601348, 21.8514777],
        ['1996. Riekstukalns', 56.7733871, 24.4019641],
        ['1997. Rucava', 56.2167009, 20.9549537],
        ['1998. Zentene', 57.168644, 22.8920529],
        ['1999. Burtnieki', 57.6923114, 25.2647041],
        ['2000. Vabole', 56.0308864, 26.4416316],
        ['2001. Ļaudona', 56.689893, 4, 26.0055484],
        ['2002. Zentene', 57.168644, 22.8920529],
        ['2003. Jūrkalne', 57.0075391, 57.0075391],
        ['2004. Kocēni', 57.5284492, 24.8702506],
        ['2005. Korģene', 57.7690316, 24.5329867],
        ['2006. Augstkalne', 56.4063672, 23.3291487],
        ['2007. Viļķene', 57.6051234, 24.4638718],
        ['2008. Viļķene', 57.6051234, 24.4638718],
        ['2009. Ergļi', 56.9026381, 56.9026381],
        ['2010. Nereta', 56.3232403, 24.8730035],
        ['2011. Viesīte', 56.3449555, 25.5429385],
        ['2012. Suntaži', 56.9046595, 24.9066061],
        ['2013. Piltene', 57.2282828, 21.690247],
        ['2014. Valmiera', 57.5310966, 25.4131496],
        ['2015. Mālpils', 57.0069641, 24.9153363],
        ['2016. Mazsalaca', 57.8629072, 25.0305959],
        ['2017. Mērsrags', 57.3446637, 23.1087438],
        ['2018. Ķoņi', 57.9547515, 25.3406828],
      ];

      this.init(options);
    }

    afterInit() {
      "use strict";
      const w = this;
      if (w.initWidgetElement()) {
        w.run();
      }
    }

    run() {
      const w = this;
      let mapCenter = {lat: 57, lng: 24};

      const map = new google.maps.Map(document.getElementById('map'), {
        center: mapCenter,
        zoom: 7
      });

      this.drawPoints(map);

    }

    drawPoints(map) {
      this.locations.forEach(location => {
        new google.maps.Marker({
          map: map,
          position: {lat: location[1], lng: location[2]},
          title: location[0],
        });
      })
    }
  }

  return pub;
})(window.jQuery);
