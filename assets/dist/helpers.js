/**
 * Yii web application widget
 */
class YiiWebAppWidget {
    constructor(id, widgetType) {
        this.id = id;
        this.parent = widgetType;
        /**
         * @type {boolean}
         */
        this.isInitialized = false;
        this.settings = {
            isDebugModeEnabled: false,
            labels: {}
        };
        /**
         * Widget related DOM elements
         * @type {*}
         */
        this.elements = {};
        /**
         * @type {HTMLElement} widget DOM element
         */
        this.elements.widget = null;
    }

    /**
     * Updates widget settings with given options
     * @param options
     *
     * @returns {YiiWebAppWidget}
     */
    setSettings(options) {
        "use strict";
        this.settings = window.extendSettings(
            this.settings,
            options
        );
        return this;
    }

    /**
     * Initializes widget with given options
     * @param options
     *
     * @returns {YiiWebAppWidget}
     */
    init(options) {
        "use strict";
        this.setSettings(options);
        this.isInitialized = true;
        return this.afterInit();
    }

    /**
     * @param {string} className
     * @return {HTMLCollection}
     */
    getElementsByClassName (className) {
        return this.elements.widget.getElementsByClassName(className);
    }

    /**
     * Returns answer if widget wrapper element was successfully found
     *
     * @returns {boolean}
     */
    initWidgetElement() {
        "use strict";
        const w = this;
        w.elements.widget = document.getElementById(w.id);
        if (w.elements.widget === null) {
            w.consoleError('Widget container not found #' + w.id);
            return false;
        }

        return true;
    }

    /**
     * Called after initialization
     */
    afterInit() {
        "use strict";
        const w = this;
        w.initWidgetElement();
    }

    /**
     * Logs message to console if debug mode is enabled
     *
     * @param {*} data
     * @param {string} [type='log']
     *
     * @returns {YiiWebAppWidget}
     */
    consoleLog(data, type) {
        "use strict";
        if (this.settings.isDebugModeEnabled) {
            let message = [
                '[' + this.parent.settings.name + ']'
            ];
            if (typeof type === 'undefined') {
                type = 'log';
            }
            if (typeof data !== 'undefined' && data.constructor.name === 'Array') {
                message = message.concat(data);
            } else {
                message.push(data);
            }
            console[type].apply(this, message);
        }
        return this;
    }

    /**
     * Log informational message
     * @param {...*} data to log
     * @returns {YiiWebAppWidget}
     */
    consoleInfo(data) {
        "use strict";
        return this.consoleLog([].slice.call(arguments), 'info');
    }

    /**
     * Log error message
     * @param {...*} data to log
     * @returns {YiiWebAppWidget}
     */
    consoleError(data) {
        "use strict";
        return this.consoleLog([].slice.call(arguments), 'error');
    }

    /**
     * Log warning message
     * @param {...*} data to log
     * @returns {YiiWebAppWidget}
     */
    consoleWarn(data) {
        "use strict";
        return this.consoleLog([].slice.call(arguments), 'warn');
    }

    /**
     * Translates text using labels from settings
     * @param {string} text
     * @param {object} [replacePairs] of strings wrapped in curly braces `{}`
     * @returns {string}
     */
    t(text, replacePairs) {
        "use strict";
        text = text.toString();
        if (typeof this.settings.labels[text] !== 'undefined') {
            if (this.settings.labels[text] !== null) {
                text = this.settings.labels[text];
            }
        }
        if (typeof replacePairs !== 'undefined') {
            for (let key in replacePairs) {
                if (replacePairs.hasOwnProperty(key)) {
                    text = text.replace(
                        new RegExp('{' + key + '}', 'g'),
                        replacePairs[key]
                    );
                }
            }
        }

        return text;
    }

    /**
     * Loads data object into Map values stored in settings by key
     * @param {string} settingsKey
     * @param {Object} options
     * @return {boolean}
     */
    fillSettingsMapFromOptions(settingsKey, options) {
        "use strict";

        if (typeof this.settings[settingsKey] === 'undefined') {
            this.settings[settingsKey] = new Map();
        }
        if (typeof options[settingsKey] !== 'undefined') {
            Object.keys(options[settingsKey]).forEach(key => {
                this.settings[settingsKey].set(key, options[settingsKey][key]);
            });
            delete options[settingsKey];
        }
    }
}


/**
 * @param {object} settings
 * @param {object} options
 * @param {boolean} [skipUndefinedSettings=true]
 * @returns {object}
 */
function extendSettings(settings, options, skipUndefinedSettings=true) {
    let updatedSettings = {};
    if (typeof settings !== 'undefined') {
        Object.assign(updatedSettings, settings);
    }
    Object
        .keys(options)
        .forEach(settingsKey => {
            let recursivelyExtend = function() {
                "use strict";
                updatedSettings[settingsKey] = extendSettings(
                    updatedSettings[settingsKey],
                    options[settingsKey],
                    false
                );
            };
            if (typeof updatedSettings[settingsKey] === 'undefined') {
                if (skipUndefinedSettings === false) {
                    updatedSettings[settingsKey] = options[settingsKey];
                }
            } else {
                switch (Object.prototype.toString.call(updatedSettings[settingsKey])) {
                    case '[object Undefined]':
                        if (skipUndefinedSettings === false) {
                            updatedSettings[settingsKey] = options[settingsKey];
                        }
                        break;
                    case '[object Null]':
                        updatedSettings[settingsKey] = options[settingsKey];
                        break;
                    case '[object Array]':
                        recursivelyExtend();
                        break;
                    case '[object Object]':
                        if (options[settingsKey].constructor.name === 'Array') {
                            // Avoid breaking objects, when some JSON encoders parse empty associative arrays as empty numeric arrays
                            if (options[settingsKey].length > 0) {
                                recursivelyExtend();
                            }
                        } else if (options[settingsKey].constructor.name === 'Object') {
                            recursivelyExtend();
                        } else {
                            console.debug(
                                'Invalid option value type to fill object in settings:',
                                options[settingsKey].constructor.name
                            );
                        }
                        break;
                    case '[object Map]':
                        // Fill settings with data from object
                        if (options[settingsKey].constructor.name === 'Array') {
                            options[settingsKey].forEach((v, k) => {
                                updatedSettings[settingsKey].set(k, v);
                            });
                        } else if (options[settingsKey].constructor.name === 'Object') {
                            Object.keys(options[settingsKey]).forEach(k => {
                                updatedSettings[settingsKey].set(k, options[settingsKey][k]);
                            });
                        } else {
                            console.debug(
                                'Invalid option value type to fill Map in settings:',
                                options[settingsKey].constructor.name
                            );
                        }
                        break;
                    default:
                        updatedSettings[settingsKey] = options[settingsKey];
                }
            }
        });

    return updatedSettings;
}
