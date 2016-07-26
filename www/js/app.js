/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

var App = {
    loaded_modules: {},
    loading: {},
    check_loading: function(fun) {
        if (Object.keys(App.loading).length == 0) fun();
    },
    load_module: function (module, fun) {
        if (App.loaded_modules[module]) {
            App.check_loading(fun);
            return;
        }
        App.async('/js/' + module + '.js', function() {
            delete App.loading[module];
            App.check_loading(fun);
        });
    },
    require: function (modules, fun) {
        modules.forEach(function(el) {App.loading[el] = true});
        modules.forEach(function(module) {
            App.load_module(module, fun);
        });
    },
    async: function (url, fun) {
        var o = document.createElement('script'),
            s = document.getElementsByTagName('script')[0];
        o.src = url;
        o.addEventListener('load', fun, false);
        s.parentNode.insertBefore(o, s);
    },

    DOMContentLoadedHappened: false,
    AllModulesLoaded: false,
    ready: function() {
        if (!App.DOMContentLoadedHappened || !App.AllModulesLoaded) return;
        Router.init();
        Transport.app_startup({url: location.href}, App.app_startup_done);
    },
    app_startup_done: function (d) {
        Router.go(d.page);
    },
    init: function() {
        window.addEventListener('DOMContentLoaded', function() {
            App.DOMContentLoadedHappened = true;
            App.ready();
        });

        App.require([
            'Transport',
            'Views',
            'Router',
            'Layout',
            'Waiter',
        ], function() {
            App.AllModulesLoaded = true;
            App.ready();
        });
    }
};

App.init();
