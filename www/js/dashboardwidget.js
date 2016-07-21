
var DashboardWidget = {
    loading_applier: null,
    render: function() {
        var vars = {
            logout_url: '/logout',
            items: []
        };

        DashboardWidget.loading_applier = NewWaiterElementInnerHtml('projects');

        Views.get('loading', DashboardWidget.loading_applier.fire, DashboardWidget.loading_applier);

        Views.get('dashboard', DashboardWidget.my_template_ready, null, vars);
    },
    my_template_ready: function(page, vars) {
        page = Views.fetch(page, vars);

        Layout.render(page, DashboardWidget.layout_ready);
    },
    layout_ready: function() {
        document.getElementById('projects').addEventListener('click', function (e) {
            if (e.target.nodeName != 'A') return;
            e.preventDefault();
            Router.go(e.target.getAttribute('href').substr(1));
        }, false);

        Transport.get_projects({}, DashboardWidget.get_projects_ready);
    },

    get_projects_ready: function(d) {
        DashboardWidget.loading_applier.cancel();

        var p = [];
        utils.each(d.projects, function(e) {
            p.push({name: e, url: '/project/' + e});
        });
        Views.block('dashboard', 'project', p, function(html) {
            document.getElementById('projects').innerHTML = html;
        });
    }
};
