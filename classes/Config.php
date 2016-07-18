<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace GitPHP;

class Config {
    private $config;

    public function __construct($file) {
        $this->config = require $file;
    }

    public function get($key, $default = null) {
        return $this->config[$key] ?? $default;
    }
}
