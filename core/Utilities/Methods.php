<?php

namespace Forge\core\Utilities;

use Forge\core\Console\Console;

class Methods
{
    public static function doConsoleMessage(array $data, Console $console, array $defaults)
    {
        foreach ($data as $key => $value) {
            if (!$key) {
                $console->log('NO' . join(" ", explode("_", $key)) . ' FOUND, DEFAULTNG TO [' . $defaults[$key] . ']');
            }
        }
    }

    public static function getTrueFalseString($bool = true, $word = false)
    {
        if ($word) {
            return $bool ? "Enabled" : "Disabled";
        }

        return $bool ? "True" : "False";
    }
}
