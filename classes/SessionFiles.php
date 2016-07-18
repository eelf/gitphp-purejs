<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class SessionFiles extends Session {
    const PREFIX = '/tmp/phpsess';
    const GC_DIVISOR = 1000;
    const GC_QUOTIENT = 1;
    const LIFETIME = 86400 * 7; // week
    const ATIME = 86400; // day

    protected $id;
    protected $cookie_name;
    protected $Resp;
    protected $data = [], $changed = [], $start_sent = false, $deleted = [];

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
        $rand = rand(1, self::GC_DIVISOR);
        if ($rand <= self::GC_QUOTIENT) self::garbageCollect();
    }

    public function __construct($cookie_name, Request $Req, Response $Resp) {
        $this->cookie_name = $cookie_name;
        $this->Resp = $Resp;

        $id = $Req->cookie($cookie_name);
        if (strlen($id) != 32) $id = null;

        if (!$id) {
            $this->id = self::generateId();
        } else {
            $this->id = $id;
            $filename = self::getFilename($this->id);
            $rows = file_exists($filename) ? file($filename, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES) : null;
            if ($rows) {
                foreach ($rows as $row) {
                    list ($k, $v) = explode('=', $row, 2);
                    $this->data[$k] = stripcslashes($v);
                }
            }
        }
    }

    public function finish() {
        if ($this->changed || $this->deleted) {
            if (!$this->start_sent) {
                $this->Resp->cookie($this->cookie_name, $this->id, time() + 86400, '/');
            }
            $rows = [];
            foreach ($this->data as $k => $v) {
                $rows[] = "$k=" . addslashes($v) . "\n";
            }
            $str = implode("\n", $rows);
            file_put_contents(self::getFilename($this->id), $str);
            self::gc();
            $this->changed = $this->deleted = [];
        } else {
            $filename = self::getFilename($this->id);
            if (file_exists($filename) && filemtime($filename) < time() - self::ATIME) {
                touch($filename);
            }
        }
    }

    private static function getFilename($id) {
        return self::PREFIX . $id;
    }

    public function destroy() {
        foreach ($this->data as $k => $v) {
            unset($this[$k]);
        }
        $this->changed = $this->deleted = [];
        $this->Resp->cookie($this->cookie_name, false, false, '/');
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value) {
        if (!isset($this->data[$offset]) || $this->data[$offset] !== $value) $this->changed[$offset] = true;
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        if (isset($this->data[$offset])) $this->deleted[$offset] = true;
        unset($this->data[$offset]);
    }
}

