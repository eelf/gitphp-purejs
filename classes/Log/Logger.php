<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Log_Logger {
    protected $lines = [];

    public function error($errno, $errstr) {
        $this->lines[] = "$errno:$errstr";
    }

    public function info($msg) {
        $this->lines[] = $msg;
    }

    public function getLines() {
        return $this->lines;
    }
}
