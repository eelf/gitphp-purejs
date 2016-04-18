<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class SessionNative extends Session {

    public function __construct($cookie_name, Request $Req, Response $Resp) {
        session_name($cookie_name);
        $id = $Req->cookie($cookie_name);
        if ($id) {
            session_start();
        }
    }

    public function finish() {
        session_write_close();
    }

    public function destroy() {
        session_destroy();
    }

    public function offsetExists($offset) {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset) {
        return $_SESSION[$offset];
    }

    public function offsetSet($offset, $value) {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();
        $_SESSION[$offset] = $value;
    }

    public function offsetUnset($offset) {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();
        unset($_SESSION[$offset]);
    }
}



