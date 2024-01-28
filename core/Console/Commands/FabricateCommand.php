<?php

namespace Forge\core\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FabricateCommand extends Command
{
    protected $mainCommandName = "fabricate";
    protected static $subCommands = [
        FabricationCommands\ControllerFabricationCommand::class,
        FabricationCommands\ModelFabricationCommand::class,
        FabricationCommands\MigrationFabricationCommand::class
    ];
    protected $mainCommandDescription = "Create a controller, model and migration";

    protected function configure()
    {
        $this->setName($this->mainCommandName)
            ->setDescription($this->mainCommandDescription)
            ->setHelp("This command creates a controller, model and migration")
            ->addArgument(
                "commandName",
                InputArgument::OPTIONAL,
                "The command to run"
            )
            ->addArgument(
                "name",
                InputArgument::OPTIONAL,
                "The name of the controller or model"
            )
            ->addOption(
                "migration",
                "m",
                InputOption::VALUE_OPTIONAL,
                "Create a migration"
            );
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getArgument("commandName");
        $name = $input->getArgument("name");

        if (!$command) {
            $output->writeln("<error>Please provide command which you want to fabricate</error>");
            $output->writeln("<info>Available commands are:</info>");
            foreach (static::$subCommands as $subCommand) {
                $output->writeln("<info>" .
                    strtolower(str_replace("FabricationCommand", "", explode("\\", $subCommand)[count(explode("\\", $subCommand)) - 1]))
                    . "</info>");
            }
            return 0;
        }

        if ($command != "migration" && !$name) {
            $output->writeln("<error>Please provide name for $command</error>");
            return 0;
        }

        $output->writeln("<info>Creating $command: $name</info>");

        return 0;
    }

    public static function getSubCommands()
    {
        return static::$subCommands;
    }
}
