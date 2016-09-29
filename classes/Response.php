<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Response {
    private $headers = [], $cookies = [], $body, $is_done = false;

    public function out() {
        foreach ($this->headers as $header) {
            header($header);
        }
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure']
            );
        }
        echo json_encode($this->body);
    }

    public function header($name, $value) {
        $this->headers[] = "$name: $value";
    }

    public function cookie($name, $value, $expire, $path) {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => null,
            'secure' => null,
        ];
    }

    public function redirect($location) {
        $this->headers[] = "Location: $location";
        $this->is_done = true;
    }

    public function isDone() {
        return $this->is_done;
    }

    public function setBodyItem($item, $value) {
        return $this->body[$item] = $value;
    }
}
