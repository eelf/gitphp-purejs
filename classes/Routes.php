<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Routes
{
    public static function url($controller, $action, $params) {

    }

    public static function action() {
        $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $action = $parts[0];
        if ($action == 'login') {
            $Controller = new Controller_Login();
        } else {
            $Controller = new Controller_Startup();
        }
        return $Controller;
    }
}