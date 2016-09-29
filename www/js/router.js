
var Router = {
    routes: {
        'dashboard': function () {
            App.require(['page/Dashboard'], function() {Dashboard.render();});
        },
        'login': function() {
            App.require(['page/Login'], function() {Login.render();});
        },
        'logout': function() {
            Transport.logout({}, function(d) {
                Router.go(d.page);
            });
        },

        'project/head': function(project, params) {
            console.log('route project/head', project, params);
            if (params.length != 1) {
                return Router.go(Router.get_url('project', {project: project}));
            }
            App.require(['page/Head'], function() {
                Head.render(project, params);
            });
        },
        'project': function(params) {
            var project = params[0],
                sub_route = 'project/' + params[1],
                sub_route_params = params.slice(2);

            if (!project) {
                Router.go('dashboard');
            } else if (Router.routes[sub_route]) {
                Router.routes[sub_route](project, sub_route_params);
            } else {
                App.require(['page/Project'], function () {
                    Project.render(project);
                });
            }
        }
    },

    get_url: function(page, params) {
        if (page == 'project') {
            return '/project' + params.project;
        }
    },

    init: function () {
        window.addEventListener('popstate', Router.gone, false);
    },
    gone: function() {
        console.log('gone', location.pathname);
        var path = location.pathname;
        if (path.charAt(0) == '/') path = path.substr(1);

        var path_exp = path.split('/').map(decodeURIComponent);
        var route_name = path_exp.shift();

        console.log('gone:path_exp', route_name, path_exp);
        if (Router.routes[route_name]) {
            Router.routes[route_name](path_exp);
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

