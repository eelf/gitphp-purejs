<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Request {
    private $url_parts,
        $first;

    public function init() {
        $url = (isset($_SERVER['HTTPS']) ? 'https:' : 'http:') . '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $this->url_parts = parse_url($url);
        $this->url_parts['path_exp'] = explode('/', preg_replace("#^/+|/+$|(/)/*#", '$1', $this->url_parts['path']));

        $this->first = isset($this->url_parts['path_exp'][0]) ? $this->url_parts['path_exp'][0] : null;
    }

    public function first() {
        return $this->first;
    }

    public function get($name) {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }

    public function comp($idx) {
        return isset($this->url_parts['path_exp'][$idx]) ? $this->url_parts['path_exp'][$idx] : null;
    }
}
