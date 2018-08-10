window.yii.app = (function() {
    "use strict";

    var instanceId = 0;

    function YiiWebAppUser(app, webUserId, webUserName, options) {
        this.app = app;
        this.id = null;
        this.name = '';
        this.settings = {
            minWarnThreshold: 60000,
            maxWarnThreshold: 600000,
            warnInterval: 60000,
            sessionTimeoutSeconds: 3600,
            loginUrl: ''
        };
        this.timers = {
            sessionTimeoutTimer: null,
            sessionTimeoutWarning: null,
            sessionTimeoutWarningStopper: null
        };
        this.lastActivityDateTime = null;
        this.init(options, webUserId, webUserName);
    }

    YiiWebAppUser.prototype.init = function(options, webUserId, webUserName) {
        var webUser = this;
        webUser.settings = window.extendSettings(
            webUser.settings,
            options
        );
        this.beginSession(webUserId, webUserName, false);
        $(document).on('sessionTimeout.webUser', function() {
            webUser.endSession(false);
        });
        webUser.refreshSessionTimeout();
    };

    /**
     * Load user
     */
    YiiWebAppUser.prototype.beginSession = function(webUserId, webUserName, triggerEvent) {
        this.id = webUserId;
        this.name = webUserName;
        if (typeof triggerEvent === 'undefined') {
            triggerEvent = true;
        }
        if (triggerEvent) {
            $(document).trigger('beginSession.webUser');
        }
    };

    /**
     * Load user
     */
    YiiWebAppUser.prototype.endSession = function(triggerEvent) {
        var webUser = this;
        webUser.id = null;
        webUser.name = null;

        webUser.stopTimer('sessionTimeoutWarning');
        webUser.stopTimer('sessionTimeoutWarningStopper');

        if (typeof triggerEvent === 'undefined') {
            triggerEvent = true;
        }
        if (triggerEvent) {
            $(document).trigger('endSession.webUser');
        }
    };

    /**
     * Wait for sessionTimeout in seconds to fire session timeout event
     */
    YiiWebAppUser.prototype.refreshSessionTimeout = function() {
        var webUser = this;

        webUser.lastActivityDateTime = new Date();

        webUser.stopTimer('sessionTimeoutTimer');
        webUser.timers.sessionTimeoutTimer = setTimeout(
            function() {
                webUser.sessionTimeout();
            },
            webUser.settings.sessionTimeoutSeconds * 1000
        );

        webUser.stopTimer('sessionTimeoutWarning');
        webUser.stopTimer('sessionTimeoutWarningStopper');
        if (webUser.settings.sessionTimeoutSeconds * 1000 > webUser.settings.minWarnThreshold) {
            webUser.timers.sessionTimeoutTimer = setTimeout(
                function() {
                    webUser.sessionTimeoutWarningLoop();
                },
                webUser.settings.sessionTimeoutSeconds * 1000 - webUser.settings.maxWarnThreshold
            );
            webUser.timers.sessionTimeoutWarningStopper = setTimeout(
                function() {
                    webUser.stopTimer('sessionTimeoutWarningStopper');
                },
                webUser.settings.sessionTimeoutSeconds * 1000 -
                webUser.settings.maxWarnThreshold +
                webUser.settings.minWarnThreshold
            );

        }
    };
    YiiWebAppUser.prototype.stopTimer = function(alias) {
        var webUser = this;
        if (webUser.timers[alias] !== null) {
            clearTimeout(webUser.timers[alias]);
            webUser.timers[alias] = null;
        }
    };

    /**
     * Deal with session timeout
     */
    YiiWebAppUser.prototype.sessionTimeoutWarningLoop = function() {
        var webUser = this;

        webUser.sessionTimeoutWarning();
        webUser.timers.sessionTimeoutWarning = setTimeout(
            function() {
                webUser.sessionTimeoutWarningLoop();
            },
            webUser.settings.warnInterval
        );
    };

    /**
     * Deal with session timeout
     */
    YiiWebAppUser.prototype.sessionTimeout = function() {
        $(document).trigger('sessionTimeout.webUser');
    };

    /**
     * Deal with session timeout warning
     */
    YiiWebAppUser.prototype.sessionTimeoutWarning = function() {
        var webUser = this;

        $(document).trigger('sessionTimeoutWarning.webUser', [webUser.lastActivityDateTime]);
    };

    /**
     * @returns {boolean}
     */
    YiiWebAppUser.prototype.isGuest = function() {
        return (this.id === null);
    };

    function YiiWebApp(id, name, baseUrl, currentLanguage, webUser, options) {
        this.isActive = true;
        this.id = id;
        this.name = name;
        this.baseUrl = baseUrl;
        this.currentLanguage = currentLanguage;
        this.webUser = new YiiWebAppUser(
            this,
            webUser.id,
            webUser.name,
            {
                sessionTimeoutSeconds: webUser.sessionTimeoutSeconds,
                loginUrl: webUser.loginUrl
            }
        );
        this.settings = {
            isDebugModeEnabled: false
        };
        this.settings = window.extendSettings(
            this.settings,
            options
        );
        this.widgets = {};
        this.init();
    }

    /**
     * Registers new instance of widget
     * @param {string} widgetTypeName
     * @param {object} [options]
     */
    YiiWebApp.prototype.registerWidget = function(widgetTypeName, options) {
        var app = this;
        if (typeof window.yii[widgetTypeName] === 'undefined') {
            throw 'Invalid widget type name ' + widgetTypeName;
        }
        if (typeof app.widgets[widgetTypeName] === 'undefined') {
            app.widgets[widgetTypeName] = {};
        }
        if (typeof options['widgetType'] !== 'undefined') {
            window.yii[widgetTypeName].settings = window.extendSettings(
                window.yii[widgetTypeName].settings,
                options['widgetType']
            );
            delete options['widgetType'];
        }
        var widgetId = null;
        if (typeof options['id'] !== 'undefined') {
            widgetId = options['id'];
            delete options['id'];
        } else {
            while (widgetId === null || typeof app.widgets[widgetTypeName][widgetId] !== 'undefined') {
                widgetId = instanceId;
                instanceId++;
            }
        }
        if (app.settings.isDebugModeEnabled) {
            console.time(
                window.yii[widgetTypeName].settings.name + ' register new instance (' + widgetId + ')'
            );
        }
        app.widgets[widgetTypeName][widgetId] = window.yii[widgetTypeName].register(widgetId, options);
        if (app.settings.isDebugModeEnabled) {
            console.timeEnd(
                window.yii[widgetTypeName].settings.name + ' register new instance (' + widgetId + ')'
            );
        }
    };

    YiiWebApp.prototype.init = function () {
        var app = this;
        if (app.settings.isDebugModeEnabled) {
            $(document).on('sessionTimeout.webUser sessionTimeoutWarning.webUser beginSession.webUser endSession.webUser', function(e) {
                console.info(e);
            });
        }
    };

    return new YiiWebApp(
        window['appInitData']['id'],
        window['appInitData']['name'],
        window['appInitData']['baseUrl'],
        window['appInitData']['currentLanguage'],
        window['appInitData']['webUser'],
        window['appInitData']['options']
    );
})();
