

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
        console.log('loading template:' + name);
        var apply_cb = function(page) {
            cb(page);
        };
        if (!(name in this.pages)) {
            Views.load(name, apply_cb);
        } else {
            apply_cb(Views.pages[name]);
        }
    },
    fetch: function(page, vars) {
        page = page.replace(/{{(begin) (\w+)}}[\s\S]+{{end \2}}|{{(\w+)}}/g, function(match, begin, block_name, var_name, idx/*, self*/) {
            if (begin) {
                if (!vars[block_name]) {
                    return '';
                }

                var prefix = '{{begin ' + block_name + '}}',
                    suffix = '{{end ' + block_name + '}}',
                    result = '',
                    sub_page = match.substr(prefix.length, match.length - suffix.length - prefix.length);
                utils.each(vars[block_name], function(el) {
                    result += Views.fetch(sub_page, el);
                });
                return result;
            } else if (vars[var_name]) {
                return vars[var_name];
            } else {
                return '';
            }
        });
        return page;
    },
    block: function(page_name, block_name, vars, cb) {
        Views.get(page_name, function(page) {
            var prefix = '{{begin ' + block_name + '}}',
                start = page.indexOf(prefix),
                end = page.indexOf('{{end ' + block_name + '}}'),
                sub_page, result = '';

            if (start != -1 && end != -1) {
                sub_page = page.substr(start + prefix.length, end - start - prefix.length);

                utils.each(vars, function(el) {
                    result += Views.fetch(sub_page, el);
                });
            }

            cb(result);
        });
    }
};
