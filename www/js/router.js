
var Router = {
    run: function () {
        console.log('Router.run');
        //window.addEventListener('popstate', Router.gone, false);
        window.onpopstate = Router.gone;
        Transport.app_startup(Router.app_startup_done);
    },
    gone: function(e) {
        console.log('gone', location.pathname);
        if (location.pathname == '/dashboard') {
            Router.dashboard();
        } else if (location.pathname == '/login') {
            LoginWidget.render();
        } else {
            console.log('do not know where to go when ' + location.pathname);
        }
    },
    go: function(page) {
        console.log('go:' + page);
        history.pushState(null, 'Page ' + page, '/' + page);
        Router.gone();
    },
    app_startup_done: function (d) {
        Router.go(d.page);
    },
    dashboard: function() {
        Views.get('dashboard', function (page) {
            document.getElementById('app').innerHTML = page;
            document.getElementById('dashboard_logout').addEventListener('click', function(e) {
                e.preventDefault();
                Router.go('logout');
            }, false);
        });
    }
};

