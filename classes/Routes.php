<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Routes {
    private static $map = [
        'login' => Controller_Login::class,
        'logout' => Controller_Logout::class,
        'projects' => Controller_Projects::class,
        '' => Controller_Startup::class,
    ];

    public static function url($controller, $action, $params) {}

    /**
     * @param Request $Req
     * @return Controller_Startup
     */
    public static function action(Request $Req) {
        $action = $Req->comp(1);
        $controller_class = self::$map[$action] ?? self::$map[''];
        return new $controller_class;
    }
}
