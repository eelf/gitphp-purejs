<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

$root = __DIR__;

$ext = 'php';

$ns = [
    'Gitphp' => $root . '/classes',
    'Ololo' => [
        'Some' => $root . '/classes/omg',
        'Other' => $root . '/classes/any',
    ],
];
//$default_path = $root . '/classes';
$default_path = '';

$autoload_path_builder = function($base, $parts, $filename) use ($ext) {
    $pathname = $base . ($parts ? '/' . implode('/', $parts) : '') . '/' . $filename . '.' . $ext;
    return is_file($pathname) ? $pathname : false;
};

$autoload_resolver = function($class) use ($ns, $default_path, $autoload_path_builder) {
    $parts = preg_split('#\\\\|_#', $class);
    $filename = array_pop($parts);

    if ($parts && isset($ns[$parts[0]])) {
        $ptr = $ns;
        foreach ($parts as $idx => $part) {
            if (!isset($ptr[$part])) return false;
            if (!is_string($ptr[$part])) $ptr = $ptr[$part];
            else return $autoload_path_builder($ptr[$part], array_slice($parts, $idx + 1), $filename);
        }
    }
    return $autoload_path_builder($default_path, $parts, $filename);
};

$autoloader = function($class) use ($autoload_resolver) {
    $path = $autoload_resolver($class);
    if ($path) require_once $path;
};

spl_autoload_register($autoloader, /* throw */true, /* prepend */true);
