
var DashboardWidget = {
    render: function() {
        var vars = {
            logout_url: '/logout',
            items: [
            ]
        };


        var loading_applier = new Context(
            function(page) {
                var el;
                if (el = document.getElementById('projects')) {
                    el.innerHTML = page;
                    return true;
                }
            }
        );

        Views.get('loading', loading_applier.fire, loading_applier);

        Views.get('dashboard', function(page) {
            page = Views.fetch(page, vars);
            document.body.innerHTML = page;
            document.getElementById('dashboard_logout').addEventListener('click', function(e) {
                e.preventDefault();
                Router.go('logout');
            }, false);
            document.getElementById('projects').addEventListener('click', function(e) {
                if (e.target.nodeName != 'A') return;
                e.preventDefault();
                Router.go(e.target.getAttribute('href').substr(1));
            }, false);

            Transport.get_projects({}, function(d) {

                loading_applier.cancel();

                var p = [];
                utils.each(d.projects, function(e) {
                    p.push({name: e, url: '/project/' + e});
                });
                Views.block('dashboard', 'project', p, function(html) {
                    document.getElementById('projects').innerHTML = html;
                });
            });
        });
    }
};
