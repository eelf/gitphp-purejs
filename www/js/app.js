/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

var utils = {
    loaded_modules: {},
    loading: {},
    each: function (list, fun, args, ctx) {
        for (var i = 0; i < list.length; i++) {
            fun.apply(ctx, [list[i], i].concat(args));
        }
    },
    check_loading: function(fun) {
        if (Object.keys(utils.loading).length == 0) fun();
    },
    load_module: function (module, i, fun) {
        if (utils.loaded_modules[module]) {
            utils.check_loading(fun);
            return;
        }
        utils.async('/js/' + module.toLowerCase() + '.js', function() {
            delete utils.loading[module];
            utils.check_loading(fun);
        });
    },
    require: function (modules, fun) {
        modules.forEach(function(el) {utils.loading[el] = true});
        utils.each(modules, utils.load_module, fun);
    },
    async: function (u, c) {
        var d = document, t = 'script',
            o = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        o.src = u;
        o.addEventListener('load', c, false);
        s.parentNode.insertBefore(o, s);
    }
};


var App = {
    DOMContentLoadedHappened: false,
    AllModulesLoaded: false,
    ready: function() {
        if (!App.DOMContentLoadedHappened || !App.AllModulesLoaded) return;
        console.log('App.ready');
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

        utils.require([
            'Transport',
            'Views',
            'Router'
        ], function() {
            App.AllModulesLoaded = true;
            App.ready();
        });
    }
};

App.init();
