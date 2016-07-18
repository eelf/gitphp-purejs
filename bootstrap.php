<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

class Bootstrap {
    private
        $ext,
        $root,
        $config,
        $default_path;

    public function __construct($root, $ext, $config, $default_path) {
        $this->root = $root;
        $this->ext = $ext;
        $this->config= $config;
        $this->default_path= $default_path;
    }

    public function buildPath($base, $parts, $filename) {
        $pathname = $this->root . $base . ($parts ? '/' . implode('/', $parts) : '') . '/' . $filename . '.' . $this->ext;
        return is_file($pathname) ? $pathname : false;
    }

    public function resolvePath($class) {
        $parts = preg_split('#\\\\|_#', $class);
        $filename = array_pop($parts);

        if ($parts && isset($this->config[$parts[0]])) {
            $ptr = $this->config;
            foreach ($parts as $idx => $part) {
                if (!isset($ptr[$part])) return false;
                if (!is_string($ptr[$part])) $ptr = $ptr[$part];
                else return $this->buildPath($ptr[$part], array_slice($parts, $idx + 1), $filename);
            }
        }
        return $this->buildPath($this->default_path, $parts, $filename);
    }

    public function autoload($class) {
        $path = $this->resolvePath($class);
        if ($path) require_once $path;
    }

    public function register() {
        spl_autoload_register([$this, 'autoload'], /* throw */true, /* prepend */true);
        return $this;
    }

    public function getRoot() {
        return $this->root;
    }
}

return (new Bootstrap(
    __DIR__,
    'php',
    [
        'Gitphp' => '/classes',
        'Ololo' => [
            'Some' => '/classes/omg',
            'Other' => '/classes/any',
        ],
    ],
    ''
))
    ->register();
