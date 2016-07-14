

var Views = {
    pages: {},
    load: function(name, apply_cb) {
        var load_cb = function(html) {
            Views.pages[name] = html;
            apply_cb(Views.pages[name]);
        };
        Transport.request('GET', '/templates/' + name + '.html', null, load_cb);
    },
    get: function(name, cb) {
        console.log('loading template' + name);
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
