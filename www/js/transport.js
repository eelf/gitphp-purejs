
var Transport = {
    request: function(method, url, data, cb) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url, true);

        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                cb(xhr.responseText);
            }
        };
        xhr.send(data);
    },
    log_first: true,
    log: function(text) {
        if (el = document.getElementById('log')) {
            el.appendChild(document.createTextNode(text + (Transport.log_first ? '' : "\n")));
            Transport.log_first = false;
        }
    },
    get_json: function(method, url, data, cb) {
        Transport.request(method, url, data, function(text) {
            var el;
            try {
                var json = JSON.parse(text);
                Transport.log(text);
                if (json.log) {
                    Transport.log(json.log);
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
    },
    logout: function(data, cb) {
        Transport.get_json('POST', '/app/logout', data, cb);
    },
};
