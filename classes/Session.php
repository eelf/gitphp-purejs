<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Session implements \ArrayAccess {
    const COOKIE_NAME = 's';
    const PREFIX = '/tmp/phpsess';
    const GC_DIVISOR = 1000;
    const GC_QUOTIENT = 1;
    const LIFETIME = 604800; // week

    protected $id;
    protected $Resp;
    protected $data = [], $changed = [], $start_send = false, $deleted = [];

    public static function startFromCookie(Request $Req, Response $Resp) {
        $id = $Req->cookie(self::COOKIE_NAME);
        if (strlen($id) != 32) $id = null;
        if (!$id) {
            $id = self::generateId();
        }
        $session = new self($id, $Resp);
        return $session;
    }

    public static function generateId() {
        for ($id = '', $i = 0; $i < 24; $i++) $id .= chr(rand(0, 255));
        $id = str_replace(['/', '+'], ['.', '_'], base64_encode($id));
        return $id;
    }

    public static function garbageCollect() {
        $files = glob(self::PREFIX . '*');
        $delete_before = time() - self::LIFETIME;
        foreach ($files as $file) {
            if (filemtime($file) < $delete_before) unlink($file);
        }
    }

    public static function gc() {
        $rand = rand(0, self::GC_DIVISOR);
        if ($rand <= self::GC_QUOTIENT) self::garbageCollect();
    }

    public function __construct($id, Response $Resp) {
        $this->id = $id;
        $this->Resp = $Resp;

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
            if (!$this->start_send) {
                $this->Resp->cookie(self::COOKIE_NAME, $this->id, time() + 86400);
            }
            $rows = [];
            foreach ($this->data as $k => $v) {
                $rows[] = "$k=" . addslashes($v) . "\n";
            }
            $str = implode("\n", $rows);
            file_put_contents(self::PREFIX . $this->id, $str);
            self::gc();
            $this->changed = $this->deleted = [];
        }
    }

    public function destroy() {
        foreach ($this->data as $k => $v) {
            unset($this[$k]);
        }
        $this->changed = $this->deleted = [];
        $this->Resp->cookie(self::COOKIE_NAME, false, false);
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
