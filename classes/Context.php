<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Context {
    private static
        $config,
        $session;

    /**
     * @param null|Config $config
     * @return Config
     */
    public static function config(Config $config = null) {
        if ($config === null) return self::$config;
        self::$config = $config;
    }

    /**
     * @param null|Session $session
     * @return Session
     */
    public static function session(Session $session = null) {
        if ($session === null) return self::$session;
        self::$session = $session;
    }
}
