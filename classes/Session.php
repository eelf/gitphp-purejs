<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

abstract class Session implements \ArrayAccess {
    const COOKIE_NAME = 's';

    /**
     * @param Request $Req
     * @param Response $Resp
     * @return self
     */
    public static function startFromCookie(Request $Req, Response $Resp) {
        $driver_class = SessionFiles::class;
//        $driver_class = SessionNative::class;
        $Driver = new $driver_class(self::COOKIE_NAME, $Req, $Resp);
        return $Driver;
    }

    abstract public function __construct($cookie_name, Request $Req, Response $Resp);

    abstract public function finish();

    abstract public function destroy();
}
