
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
        Transport.login(JSON.stringify({name: name, password: password}), Router.gone);
    },
    render: function() {
        Views.get('login', function(page) {
            //LoginWidget.el = document.createElement('div');
            LoginWidget.el = document.getElementById('app');
            LoginWidget.el.innerHTML = page;
            //document.body.appendChild(LoginWidget.el);
            LoginWidget.init(LoginWidget.el);
        });
    }
};
