<?php

namespace Forge\core\Frontend;

use Forge\core\Console\Console;
use Forge\core\Console\Kernel;
use Forge\core\Utilities\Methods;

class InitializeFrontend
{
    private $frontendFramework = 'react', $frontendBundler = 'vite', $frontendDirName = 'frontend', $pkgManager = 'npm', $frontendWay = 'mixins', $typescript = true;

    private Console | null $console = null;

    private function validate($pkgManager)
    {
        if ($pkgManager === "bunjs") {
            $pkgManager = 'bun';
        }

        return exec("$pkgManager --version");
    }

    public function __construct($pkgManager, $frontendWay, $typescript = true)
    {
        $this->console = new console();

        $this->pkgManager = $pkgManager;
        $this->typescript = $typescript;
        $this->frontendWay = $frontendWay;
        $this->frontendDirName = $_ENV['FRONTEND_DIR_NAME'] ?? 'frontend';
        $this->frontendBundler = $_ENV['FRONTEND_BUILD_TOOL'] ?? 'vite';
        $this->frontendFramework = $_ENV['FRONTEND_FRAMEWORK'] ?? 'react';

        Methods::doConsoleMessage(['FRONTEND_DIR_NAME' => $_ENV['FRONTEND_DIR_NAME'], 'FRONTEND_BUILD_TOOL' => $_ENV['FRONTEND_BUILD_TOOL'], 'FRONTEND_FRAMEWORK' => $_ENV['FRONTEND_FRAMEWORK']], $this->console, ['FRONTEND_DIR_NAME' => 'frontend', 'FRONTEND_BUILD_TOOL' => 'vite', 'FRONTEND_FRAMEWORK' => 'react']);

        if (!$this->validate($this->pkgManager)) {
            $string = ($pkgManager === "bunjs" ? "<error>bunjs is not available/installed.</error>\nPlease install bunjs first and re-run this command or change to any other package manager" : (
                $pkgManager === "npm" ? "<error>npm is not avaialable.</error> \nPlease make sure to install nodejs from https://nodejs.org and re-run this command" : "<error>$pkgManager is not available.</error>\nPlease make sure node js is installed,\n If Yes then please run <info>npm i -g $pkgManager</info> and re-run this command"
            ));
            $this->console->log($string . PHP_EOL);
            exit(0);
        }
    }

    public function make()
    {
        switch (strtolower($this->frontendFramework)) {
            case 'react':
                $this->console->log("<info>Setting up react as $this->frontendWay having typescript " . strtolower(Methods::getTrueFalseString($this->typescript, true)) . " with $this->pkgManager as package manager</info>");
                $this->setupReact();
                break;
            default:
                $this->console->log("<info>Setting up react as $this->frontendWay having typescript " . strtolower(Methods::getTrueFalseString($this->typescript, true)) . " with $this->pkgManager as package manager</info>");
                $this->setupReact();
                break;
        }
    }

    private function setupReact()
    {
        $root = Kernel::$rootDir;
        $frontendDir = "$root/$this->frontendDirName";

        $this->console->log("\n");

        if (is_dir($frontendDir) && (count(scandir($frontendDir)) === 2)) {
            if (!is_writable($frontendDir)) {
                $this->console->log("<error>Directory $frontendDir is not writable</error>");
                return;
            }
            $this->console->log("<info>Directory $frontendDir already exists</info>");
            return;
        }

        Scripts::setupReact($frontendDir, $this->frontendBundler, $this->frontendWay, $this->pkgManager, $this->typescript, true);
    }
}
