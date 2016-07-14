
var LoginWidget = {
    el: null,
    init: function(el) {
        var button = el.querySelector('button[type=submit]');
        button.addEventListener('click', LoginWidget.submit);
    },
    submit: function(e) {
        e.preventDefault && e.preventDefault();
        var name = LoginWidget.el.querySelector('#login_name').value;
        var password = LoginWidget.el.querySelector('#login_password').value;
        Transport.login(JSON.stringify({name: name, password: password}), function(resp) {
            if (resp.page) {
                Router.go(resp.page);
            } else if (resp.error) {
                alert(resp.error);
            }
        });
    },
    render: function() {
        Views.get('login', function(page) {
            //LoginWidget.el = document.createElement('div');
            LoginWidget.el = document.body;
            LoginWidget.el.innerHTML = page;
            //document.body.appendChild(LoginWidget.el);
            LoginWidget.init(LoginWidget.el);
        });
    }
};
