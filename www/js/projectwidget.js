
var ProjectWidget = {
    loading_applier: null,
    params: null,
    render: function(params) {
        ProjectWidget.params = params;
        ProjectWidget.loading_applier = NewWaiterElementInnerHtml('heads');

        Views.get('loading', ProjectWidget.loading_applier.fire, ProjectWidget.loading_applier);

        Views.get('project', ProjectWidget.my_template_ready);
    },
    my_template_ready: function(page) {
        var html = Views.fetch(page, {project_name: ProjectWidget.params[0]});

        Layout.render(html, ProjectWidget.layout_ready);
    },
    layout_ready: function() {

        Transport.get_heads({project: ProjectWidget.params[0]}, ProjectWidget.get_heads_ready);
    },
    get_heads_ready: function(d) {
        ProjectWidget.loading_applier.cancel();

        var heads = d.heads.map(function(el) {
            return {
                real_name: el.name,
                name: el.name.substr("refs/heads/".length),
                url: "/project/" + ProjectWidget.params[0] + "/head/" + encodeURIComponent(el.name),
            };
        });

        Views.block('project', 'heads', heads, function (html) {
            var el = document.getElementById('heads');
            el.innerHTML = html;
            el.addEventListener('click', function(e) {
                if (e.target.nodeName != 'A') return;
                e.preventDefault();
                Router.go(e.target.getAttribute('href').substr(1));
            }, false);
        });
    }
};
