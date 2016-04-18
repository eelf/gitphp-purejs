<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Response {
    const TYPE_JSON = 'json';
    private $headers = [], $cookies = [], $body, $type, $is_done = false;

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
        if ($this->type == self::TYPE_JSON) {
            echo json_encode($this->body);
        } else {
            echo $this->body;
        }
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
        if (StatSlow::enabled()) {
            echo "<div style='font: 18px monospace;'><a href='$location'>$location</a></div><pre>";
            echo (new \Exception())->getTraceAsString();
            StatSlow::displayErrors();
            echo "</pre>";
        } else {
            $this->headers[] = "Location: $location";
        }
        $this->is_done = true;
    }

    public function isDone() {
        return $this->is_done;
    }

    public function setBody($body) {
        $this->body = $body;
    }
    public function setBodyItem($item, $value) {
        if ($this->type != self::TYPE_JSON) {
            $this->type = self::TYPE_JSON;
            $this->body = [];
        }
        $this->body[$item] = $value;
    }
}
