<?php

namespace Forge\core\Frontend;

use Forge\core\Console\Console;
use Forge\core\Console\Kernel;
use Forge\core\Utilities\Methods;

class Scripts
{
    private const PACKAGE_MANAGERS = [
        'npm' => 'npm',
        'yarn' => 'yarn',
        'pnpm' => 'pnpm',
        'bun' => 'bun',
    ];
    private const FRONTEND_FRAMEWORKS = [
        'react' => 'react',
        'vue' => 'vue',
        'svelte' => 'svelte',
        'angular' => 'angular',
    ];
    private const FRONTEND_BUNDLERS = [
        'vite' => 'vite',
        'webpack' => 'webpack',
        'parcel' => 'parcel',
        'rollup' => 'rollup',
    ];
    private const FRONTEND_WAYS = [
        'mixins' => 'mixins',
        'independent' => 'independent',
    ];

    private static function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private static function isLinux(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'LINUX' || strtoupper(substr(PHP_OS, 0, 3)) === 'DARWIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'MAC' || strtoupper(substr(PHP_OS, 0, 3)) === 'UNIX';
    }

    private static function removeFile(string $file, bool $showError = true): void
    {
        if (file_exists($file)) {
            unlink($file);
        } else {
            if ($showError) {
                echo "<error>File $file does not exist</error>";
            }
        }
    }


    private static function removeDir(string $dir, bool $showError = true): void
    {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = "$dir/$file";
                if (is_dir($path)) {
                    self::removeDir($path);
                } else {
                    unlink($path);
                }
            }
            rmdir($dir);
        }
    }

    private static function moveFile(string $source, string $destination, bool $showError = true): void
    {
        if (file_exists($source)) {
            if (self::isWindows()) {
                passthru("move $source $destination");
            } elseif (self::isLinux()) {
                passthru("mv $source $destination");
            } else {
                passthru("mv $source $destination");
            }
        } else {
            if ($showError) {
                echo "<error>File $source does not exist</error>";
            }
        }
    }

    public static function createMixins(Console $console, string $dir, string $frontendFramework, string $frontendBundler, string $frontendWay, string $pkgManager, bool $typescript = true)
    {
        $console->log("<info>Scaffolding $frontendFramework with $frontendBundler using $frontendWay and $pkgManager as package manager</info>");

        switch ($frontendFramework) {
            case 'react':
                self::setupReact($dir, $frontendBundler, $frontendWay, $pkgManager, $typescript, true);
                break;
            default:
                # code...
                break;
        }
    }

    public static function setupReact(string $dir, string $frontendBundler, string $frontendWay, string $pkgManager, bool $typescript = true, bool $isMixins = true)
    {
        $console = new Console();
        $rootDir = Kernel::$rootDir;

        if ($pkgManager == "bunjs") {
            $pkgManager = 'bun';
        }
        if ($pkgManager == "npm") {
            $pkgManager = 'npx';
        }

        if ($frontendBundler === 'vite') {
            if ($isMixins) {
                $console->log("<info>Setting up react with vite</info>");
                exec("cd $rootDir");

                $console->log("Removing existing frontend directory");

                self::removeDir($dir);

                self::removeFile("$rootDir/bun.lockb", false);
                self::removeFile("$rootDir/yarn.lock", false);
                self::removeDir("$rootDir/node_modules", false);
                self::removeFile("$rootDir/package.json", false);
                self::removeFile("$rootDir/vite.config.ts", false);
                self::removeFile("$rootDir/vite.config.js", false);
                self::removeFile("$rootDir/pnpm-lock.yaml", false);
                self::removeFile("$rootDir/package-lock.json", false);

                $console->log("<info>Running: $pkgManager create vite@latest frontend --template react-ts</info>");
                passthru("$pkgManager create vite@latest frontend --template react-ts");

                // move package.json and vite.config.ts to root dir
                $console->log("<info>Moving package.json and vite config to root dir</info>");
                self::moveFile("$dir/package.json", "$rootDir/package.json", false);
                self::moveFile("$dir/vite.config.ts", "$rootDir/vite.config.ts", false);
                self::moveFile("$dir/vite.config.js", "$rootDir/vite.config.js", false);

                // separate the ts, tsx, js, jsx , css files 

            }
        } else {
            $console->log("<error>Bundler not supported</error>");
        }
    }
}
