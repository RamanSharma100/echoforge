<?php

namespace Forge\core\Console;

use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;


class Kernel
{

    public $app;

    protected $commands = [
        Commands\ServerCommand::class,
        Commands\FabricateCommand::class,
        Commands\MigrationCommand::class,
    ];

    public function __construct()
    {
        $path = $_ENV['APP_BASE_PATH'] ?? dirname(dirname(__DIR__));
        $this->loadEnv($path);
        $this->commands = array_merge($this->commands, $this->getCommands());
    }

    public function loadEnv(string $path)
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    protected function getCommands()
    {
        return [];
    }

    protected function add(Command $command)
    {
        $this->commands[] = $command;
    }

    public function registerCommands()
    {
        $this->app = new \Symfony\Component\Console\Application();



        foreach ($this->commands as $command) {
            $this->app->add(new $command);

            if (method_exists($command, 'getSubCommands')) {
                foreach ($command::getSubCommands() as $subCommand) {
                    $this->app->add(new $subCommand);
                }
            }
        }
    }
}
