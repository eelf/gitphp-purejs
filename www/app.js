/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

var objs = [
    'LoginWidget',
    'Transport',
    'Views',
    'Router',
];

var version = 2;

for (var i = 0; i < objs.length; i++) {
    if (typeof window[objs[i]] == 'undefined') {
        var obj_file = '/js/' + objs[i].toLowerCase() + '.js';
        if (version == 1) {
            document.write('<script type="text/javascript" src="' + obj_file + '"></script>');
        } else {
            var el = document.createElement('script');
            el.src = obj_file;
            //el.onload = function() { console.log(this.src); }
            document.head.appendChild(el);
        }
    }
}


if (version == 2) {
    if (typeof window['Router'] != 'undefined') {
        window.addEventListener('DOMContentLoaded', function() {
            console.log('handling window.DOMContentLoaded');
            Router.run();
        });
    } else {
        window.addEventListener('load', function(e) {
            console.log('handling window.load', e);
            Router.run();
        });
    }

} else {
    if (typeof window['Router'] != 'undefined') {
        window.addEventListener('DOMContentLoaded', ready);
    } else {
        window.addEventListener('load', function() {
            ready();
        });
    }

    function ready() {
        setTimeout(function() {
            Router.run();
        }, 1000);
    }
}







