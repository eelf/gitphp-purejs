
var Project = {
    loading_applier: null,
    project: null,
    render: function(project) {
        Project.project = project;
        Project.loading_applier = Waiter.NewElementInnerHtml('heads');

        Views.get('loading', Project.loading_applier.fire, Project.loading_applier);

        Views.get('project', Project.my_template_ready);
    },
    my_template_ready: function(page) {
        var html = Views.fetch(page, {project_name: Project.project});

        Layout.render(html, Project.layout_ready);
    },
    layout_ready: function() {

        Transport.get_heads({project: Project.project}, Project.get_heads_ready);
    },
    get_heads_ready: function(d) {
        Project.loading_applier.cancel();

        var heads = d.heads.map(function(el) {
            return {
                real_name: el.name,
                name: el.name.substr("refs/heads/".length),
                url: "/project/" + Project.project + "/head/" + encodeURIComponent(el.name),
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
