
var Layout = {
    items: [
        {
            id: 'logout',
            url: 'logout',
            name: 'Logout'
        }
    ],
    handleEvent: function(e) {
        e.preventDefault();
        if (e.target.id == 'top_projects') {
            Router.go('dashboard');
        } else if (e.target.id == 'logout') {
            Router.go('logout');
        }
    },
    render: function(content, ready, ctx, params) {
        Views.get('layout', function(page) {
            var html = Views.fetch(page, {content: content, items: Layout.items});

            document.body.innerHTML = html;

            ready.apply(ctx, params);
            document.getElementById('top_projects').addEventListener('click', Layout);
            document.getElementById('logout').addEventListener('click', Layout);
        });
    }
};
