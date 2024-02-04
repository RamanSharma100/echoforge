<?php

namespace Forge\core\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends Command
{
    protected $commandName = "serve";
    protected $commandDescription = "Start the EchoForge server on the specified port";

    protected $commandOptions = [
        "open" => [
            "shortcut" => "o",
            "mode" => InputOption::VALUE_OPTIONAL,
            "description" => "Open the server in the browser"
        ],
        "port" => [
            "shortcut" => "p",
            "mode" => InputOption::VALUE_OPTIONAL,
            "description" => "The port to run the server on"
        ]
    ];

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command starts the EchoForge server on the specified port")
            ->addArgument(
                "port",
                InputArgument::OPTIONAL,
                "The port to run the server on"
            )
            ->addOption(
                "open",
                "o",
                InputOption::VALUE_OPTIONAL,
                "Open the server in the browser"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument("port") ?? $_ENV['APP_PORT'] ?? 9000;

        $open = $input->getOption("open");

        $host = $_ENV['APP_HOST'] ?? "localhost";

        $url = "http://{$host}:{$port}";


        $output->writeln("<info>Starting server on {$url}</info>");

        if (!$open) {
            $output->writeln("<info>Press Ctrl-C to quit</info>");
        } else {
            $output->writeln("<info>Opening {$url} in the browser</info>");
        }

        // $navigate to public folder
        // $path = $_ENV['APP_BASE_PATH'] ?? dirname(dirname(__DIR__));
        // passThru("cd " . $path . "/public");

        $command = "php -S {$host}:{$port} -t public -d display_errors=1 -d error_reporting=E_ALL";

        if ($open) {
            $command .= " && open {$url}";
        }

        passthru($command);
    }
}
