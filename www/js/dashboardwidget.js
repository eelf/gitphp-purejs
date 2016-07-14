
var DashboardWidget = {
    render: function() {
        Views.get('dashboard', function (page) {
            page = page.replace('{{logout_url}}', '/logout');
            document.body.innerHTML = page;
            document.getElementById('dashboard_logout').addEventListener('click', function(e) {
                e.preventDefault();
                Router.go('logout');
            }, false);
        });
    }
};
