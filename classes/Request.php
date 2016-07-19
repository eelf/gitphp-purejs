<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Request {
    private $env, $get, $post, $cookie, $server;
    private $url_parts;
    private $body;
    private $body_err;

    public static function initFromServer() {
        $url = (isset($_SERVER['HTTPS']) ? 'https:' : 'http:') . '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $Request = new self($_ENV, $_GET, $_POST, $_COOKIE, $_SERVER);
        $Request->init($url);
        return $Request;
    }

    public function __construct($env, $get, $post, $cookie, $server) {
        $this->env = $env;
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->server = $server;
    }

    public function init($url) {
        $this->url_parts = parse_url($url);
        $this->url_parts['path_exp'] = explode('/', trim($this->url_parts['path'], '/'));

        $this->body = file_get_contents('php://input');

        if ($this->server('REQUEST_METHOD') == 'POST' && $this->server('HTTP_CONTENT_TYPE') == 'application/json') {
            $body = json_decode($this->body, true);
            if ($body === null && $this->body != 'null') {
                $this->body_err = json_last_error() . ':' . json_last_error_msg();
            } else {
                $this->body = $body;
            }
        }
    }

    public function get($name) {
        return $this->get[$name] ?? null;
    }

    public function post($name) {
        return $this->post[$name] ?? null;
    }

    public function cookie($name) {
        return $this->cookie[$name] ?? null;
    }

    public function server($name) {
        return $this->server[$name] ?? null;
    }

    public function comp($idx) {
        return $this->url_parts['path_exp'][$idx] ?? null;
    }

    public function body() {
        return $this->body;
    }

    public function body_err() {
        return $this->body_err;
    }
}
