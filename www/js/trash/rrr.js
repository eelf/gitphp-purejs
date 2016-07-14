
requirejs(
    [
        'js/transport',
        'js/views',
        'js/loginwidget',
        'js/dashboardwidget',
        'js/router'
    ],
    function() {
        Router.run();
    }
);
