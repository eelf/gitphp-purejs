
var Layout = {
    items: [
        {
            id: 'logout',
            url: 'logout',
            name: 'Logout'
        }
    ],
    render: function(content, ready, ctx, params) {
        Views.get('layout', function(page) {
            var html = Views.fetch(page, {content: content, items: Layout.items});

            document.body.innerHTML = html;

            ready.apply(ctx, params);
            //document.getElementById('dashboard_logout').addEventListener('click', function(e) {
            //    e.preventDefault();
            //    Router.go('logout');
            //}, false);
        });
    }
};
