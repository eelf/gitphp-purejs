
var Head = {
    project: null,
    head: null,
    render: function(project, params) {
        Head.project = project;
        Head.head = params[0];
        Views.get('head', Head.my_template_ready);
    },
    my_template_ready: function(page) {
        var html = Views.fetch(page, {
            project_name: Head.project,
            head: Head.head
        });

        Layout.render(html, Head.layout_ready);
    },
    layout_ready: function() {

        Transport.shortlog({project: Head.project, head: Head.head}, Head.shortlog_ready);
    },
    shortlog_ready: function(d) {
        var revs = d.revs.map(function(el) {
            return {
                name: el,
                url: "/project/" + Head.project + "/commit/" + encodeURIComponent(el),
            };
        });

        Views.block('head', 'revs', revs, function (html) {
            var el = document.getElementById('revs');
            el.innerHTML = html;
            el.addEventListener('click', function(e) {
                if (e.target.nodeName != 'A') return;
                e.preventDefault();
                Router.go(e.target.getAttribute('href').substr(1));
            }, false);
        });
    }
};
