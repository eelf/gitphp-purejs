
var ProjectWidget = {
    render: function(params) {
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

        var loading_applier = new Context(
            function(page) {
                var el;
                if (el = document.getElementById('heads')) {
                    el.innerHTML = page;
                    return true;
                }
            }
        );

        Views.get('loading', loading_applier.fire, loading_applier);

        Views.get('project', function(page) {
            var html = Views.fetch(page, {project_name: params[1]});
            document.body.innerHTML = html;

            Transport.get_heads({project: params[1]}, function(d) {
                console.log('got heads');
                loading_applier.cancel();
                Views.block('project', 'heads', d.heads, function (html) {
                    document.getElementById('heads').innerHTML = html;
                });
            });

        });
    }
};
