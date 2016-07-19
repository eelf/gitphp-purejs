
var Router = {
    routes: {
        'dashboard': function () {
            utils.require(['DashboardWidget', 'Context'], function() {DashboardWidget.render();});
        },
        'login': function() {
            utils.require(['LoginWidget'], function() {LoginWidget.render();});
        },
        'logout': function() {
            Transport.logout({}, function(d) {
                Router.go(d.page);
            });
        },
        'project': function(params) {
            utils.require(['ProjectWidget', 'Context'], function() {ProjectWidget.render(params);});
        }
    },

    init: function () {
        window.addEventListener('popstate', Router.gone, false);
    },
    gone: function() {
        console.log('gone', location.pathname);
        var path = location.pathname;
        if (path.charAt(0) == '/') path = path.substr(1);

        var path_exp = path.split('/');

        console.log(path_exp);
        if (Router.routes[path_exp[0]]) {
            Router.routes[path_exp[0]](path_exp);
        } else {
            console.log('do not know where to go when ' + location.pathname);
        }
    },
    go: function(page) {
        console.log('go:' + page);
        history.pushState(null, 'Page ' + page, '/' + page);
        Router.gone();
    }
};

