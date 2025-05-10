<?php

namespace Forge\core\Frontend;

use Forge\core\Console\Console;

class InitializeFrontend
{
    private $frontendFramework, $frontendBundler, $frontendDirName, $pkgManager, $frontendWay;

    private Console | null $console = null;

    public function __construct($pkgManager, $frontendWay)
    {
        $this->console = new console();

        $this->console->log("here isenv", print_r($_ENV));
    }
}
