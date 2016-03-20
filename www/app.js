/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

var Transport = {
    request: function(method, url, data, cb) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                cb(xhr.responseText);
            }
        };
        xhr.send(data);
    },
    get_json: function(method, url, data, cb) {
        Transport.request(method, url, data, function(text) {
            var el;
            try {
                var json = JSON.parse(text);
                if (el = document.getElementById('log')) {
                    el.appendChild(document.createTextNode("\n" + text));
                }
                if (json.log) {
                    if (el = document.getElementById('log')) {
                        el.appendChild(document.createTextNode("\n" + json.log));
                    }
                }
                cb(json);
            } catch (e) {
                el = document.createElement('div');
                el.appendChild(document.createTextNode(text + e));
                el.classList.add('err');
                document.body.appendChild(el);
            }
        });
    },

    app_startup: function(cb) {
        Transport.get_json('GET', '/app/startup', null, cb);
    },
    login: function(data, cb) {
        Transport.get_json('POST', '/app/login', data, cb);
    }
};

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
        Transport.login(JSON.stringify({name: name, password: password}), LoginWidget.submit_done);
    },
    submit_done: function(e) {
        console.log(e);
    },
    render: function() {
        Views.get('login', function(page) {
            LoginWidget.el = document.createElement('div');
            LoginWidget.el.innerHTML = page;
            document.body.appendChild(LoginWidget.el);
            LoginWidget.init(LoginWidget.el);
        });
    }
};


var Views = {
    pages: {},
    load: function(name, apply_cb) {
        var load_cb = function(html) {
            Views.pages[name] = html;
            apply_cb(Views.pages[name]);
        };
        Transport.request('GET', '/' + name + '.html', null, load_cb);
    },
    get: function(name, cb) {
        var apply_cb = function(page) {
            cb(page);
        };
        if (!(name in this.pages)) {
            Views.load(name, apply_cb);
        } else {
            apply_cb(Views.pages[name]);
        }
    }
};

var App = {
    run: function () {
        Transport.app_startup(App.app_startup_done);
    },
    app_startup_done: function (d) {
        if (d.page == 'login') {
            LoginWidget.render();
        }
    }
};

window.addEventListener('DOMContentLoaded', App.run);
