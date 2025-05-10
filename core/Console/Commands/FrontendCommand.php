<?php

namespace Forge\core\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class FrontendCommand extends Command
{
    protected $mainCommandName = "frontend";
    protected static $subCommands = [
        FrontendCommands\InitFrontendCommand::class
    ];
    protected $mainCommandDescription = "Setup a frontend mixins";

    protected function configure()
    {
        $this->setName($this->mainCommandName)
            ->setDescription($this->mainCommandDescription)
            ->setHelp("This command setup frontend mixins")
            ->addArgument(
                "commandName",
                InputArgument::OPTIONAL,
                "The command to run"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getArgument("commandName");

        if (!$command) {
            $output->writeln("<error>Please provide command which you want to do for frontend</error>");
            $output->writeln("<info>Available commands are:</info>");
            foreach (static::$subCommands as $subCommand) {
                $output->writeln("<info>" .
                    strtolower(str_replace("FrontendCommand", "", explode("\\", $subCommand)[count(explode("\\", $subCommand)) - 1]))
                    . "</info>");
            }
            return 0;
        }

        if (
            !in_array(
                "Forge\\core\\Console\\Commands\\FrontendCommands\\" . ucfirst($command) . "FrontendCommand",
                static::$subCommands
            )
        ) {
            $output->writeln("<error>Command frontend:$command does not exist</error>" . PHP_EOL);
            $this->help($output);
            return 0;
        }

        switch ($command) {
            default:
                $this->help($output);
                break;
        }

        return 0;
    }

    private function help(OutputInterface $output)
    {
        $output->writeln("<info>Available frontend commands are:</info>" . PHP_EOL);
        foreach (static::$subCommands as $subCommand) {
            if ($subCommand == "Forge\\core\\Console\\Commands\\FrontendCommands\\MigrationFrontendCommand") {
                $output->writeln("<info>php forge fabricate " .
                    strtolower(str_replace("FrontendCommand", "", explode("\\", $subCommand)[count(explode("\\", $subCommand)) - 1]))
                    . "</info>");
                continue;
            }
        }

        $output->writeln(PHP_EOL . "OR" . PHP_EOL);

        $output->writeln("<info>php forge frontend:<command></info>");
    }

    public static function getSubCommands()
    {
        return static::$subCommands;
    }
}
