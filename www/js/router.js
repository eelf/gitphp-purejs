
var Router = {
    init: function () {
        window.addEventListener('popstate', Router.gone, false);
    },
    gone: function(e) {
        //console.trace();
        console.log('gone', location.pathname);
        if (location.pathname == '/dashboard') {
            utils.require(['DashboardWidget'], function() {DashboardWidget.render();});
        } else if (location.pathname == '/login') {
            utils.require(['LoginWidget'], function() {LoginWidget.render();});
        } else if (location.pathname == '/logout') {
            Transport.logout({}, function(d) {
                Router.go(d.page);
            });
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

