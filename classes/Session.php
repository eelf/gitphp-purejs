<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Session implements \ArrayAccess {
    const PREFIX = '/tmp/phpsess';
    protected $id;
    protected $data = array();
    protected $changed = array();
    protected $deleted = array();


    public static function startFromCookie() {
        $id = isset($_COOKIE['s']) && strlen($_COOKIE['s']) == 32 ? $_COOKIE['s'] : null;
        if (!$id) {
            $id = self::generateId();
            setcookie('s', $id, time() + 86400);
        }
        $session = new self($id);
        return $session;
    }

    public static function removeCookie() {
        setcookie('s', 'deleted');
    }

    public static function generateId() {
        for ($id = '', $i = 0; $i < 24; $i++) $id .= chr(rand(0, 255));
        $id = str_replace(array('/', '+'), array('.', '_'), base64_encode($id));
        return $id;
    }

    public function __construct($id) {
        $this->id = $id;

        $filename = self::PREFIX . $id;
        $rows = file_exists($filename) ? file($filename, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES) : null;
        if (!$rows) {
            return;
        }
        foreach ($rows as $row) {
            list ($k, $v) = explode('=', $row, 2);
            $this->data[$k] = stripcslashes($v);
        }
    }

    public function finish() {
        $insertUpdate = array_keys($this->changed);
        $delete = array_keys($this->deleted);
        if ($insertUpdate || $delete) {
            $rows = [];
            foreach ($this->data as $k => $v) {
                $rows[] = "$k=" . addslashes($v) . "\n";
            }
            $str = implode("\n", $rows);
            file_put_contents(self::PREFIX . $this->id, $str);
            $this->changed = $this->deleted = [];
        }
    }

    public function destroy() {
        foreach ($this->data as $k => $v) {
            unset($this[$k]);
        }
        self::removeCookie();
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (!isset($this->data[$offset]) || $this->data[$offset] !== $value) $this->changed[$offset] = true;
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        if (isset($this->data[$offset])) $this->deleted[$offset] = true;
        unset($this->data[$offset]);
    }

    public function getData() {
        return $this->data;
    }
}
