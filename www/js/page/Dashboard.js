
var Dashboard = {
    loading_applier: null,
    render: function() {
        var vars = {
            logout_url: '/logout',
            items: []
        };

        Dashboard.loading_applier = Waiter.NewElementInnerHtml('projects');

        Views.get('loading', Dashboard.loading_applier.fire, Dashboard.loading_applier);

        Views.get('dashboard', Dashboard.my_template_ready, null, vars);
    },
    my_template_ready: function(page, vars) {
        page = Views.fetch(page, vars);

        Layout.render(page, Dashboard.layout_ready);
    },
    layout_ready: function() {
        document.getElementById('projects').addEventListener('click', function (e) {
            if (e.target.nodeName != 'A') return;
            e.preventDefault();
            Router.go(e.target.getAttribute('href').substr(1));
        }, false);

        Transport.get_projects({}, Dashboard.get_projects_ready);
    },

    get_projects_ready: function(d) {
        if (d.page) Router.go(d.page);
        Dashboard.loading_applier.cancel();

        var p = [];
        d.projects.forEach(function(e) {
            p.push({name: e, url: '/project/' + e});
        });
        Views.block('dashboard', 'project', p, function(html) {
            document.getElementById('projects').innerHTML = html;
        });
    }
};
