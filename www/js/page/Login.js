
var Login = {
    el: null,
    submit: function(e) {
        e.preventDefault && e.preventDefault();
        var name = document.getElementById('login_name').value;
        var password = document.getElementById('login_password').value;
        Transport.login(JSON.stringify({name: name, password: password}), function(resp) {
            if (resp.page) {
                Router.go(resp.page);
            } else if (resp.error) {
                alert(resp.error);
            }
        });
    },
    render: function() {
        Views.get('login', Login.my_template_ready);
    },
    my_template_ready: function(page) {
        page = Views.fetch(page, {});

        Layout.render(page, Login.layout_ready);
    },
    layout_ready: function() {
        document.getElementById('submit').addEventListener('click', Login.submit);
    }
};
