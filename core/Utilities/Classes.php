<?php

namespace Forge\core\Utilities;

class Classes
{
    public static function getClassesWithNamespce($namespace)
    {
        $classes = [];
        $namespace = str_replace('\\', '/', $namespace);
        $files = scandir($namespace);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir($namespace . '/' . $file)) {
                $classes = array_merge($classes, self::getClasses($namespace . '/' . $file));
            } else {
                $classes[] = $namespace . '/' . $file;
            }
        }
        return $classes;
    }

    public static function getClasses($path)
    {
        $classes = [];
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $classes = array_merge($classes, self::getClasses($path . '/' . $file));
            } else {
                $classes[] = $path . '/' . $file;
            }
        }
        return $classes;
    }

    public static function getMethodInstructions($class, string $method)
    {
        $reflection = new \ReflectionMethod($class, $method);
        $filename = $reflection->getFileName();
        $start_line = $reflection->getStartLine();
        $end_line = $reflection->getEndLine();
        $length = $end_line - $start_line;
        $source = file($filename);
        $body = implode("", array_slice($source, $start_line, $length));
        return $body;
    }
}
