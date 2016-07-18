
var DashboardWidget = {
    render: function() {
        var vars = {
            logout_url: '/logout',
            items: [
                {
                    id: 'hi',
                    name: 'Hia aa',
                    url: 'inna'
                }
            ]
        };
        Views.get('dashboard', function(page) {
            page = Views.fetch(page, vars);
            document.body.innerHTML = page;
            document.getElementById('dashboard_logout').addEventListener('click', function(e) {
                e.preventDefault();
                Router.go('logout');
            }, false);
            Transport.get_projects({}, function(d) {
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
