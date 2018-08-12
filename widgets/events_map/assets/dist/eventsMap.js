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
    run () {
      const w = this;
      w.renderState();


    }
    renderState () {
      const w = this;
    }
  }
  return pub;
})(window.jQuery);
