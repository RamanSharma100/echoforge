<?php

namespace Forge\core\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Forge\core\Fabrications;

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
        $option = $input->getOption("migration");

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

        if (
            !in_array(
                "Forge\\core\\Console\\Commands\\FabricationCommands\\" . ucfirst($command) . "FabricationCommand",
                static::$subCommands
            )
        ) {
            $output->writeln("<error>Command fabricate $command does not exist</error>" . PHP_EOL);
            $this->help($output);
            return 0;
        }

        if ($command != "migration" && !$name) {
            $output->writeln("<error>Please provide name for fabricate $command</error>");
            $this->help($output);
            return 0;
        }

        if ($command !== "migration"  && $option) {
            $output->writeln("<error>Migration option is only available for fabricate migration command</error>");
            $this->help($output);
            return 0;
        }

        switch ($command) {
            case "controller":
                $controller = new Fabrications\Controller($name);
                $controller->createController();
                break;
            case "model":
                $model = new Fabrications\Model($name);
                $model->createModel();
                break;
            case "migration":
                $migration = new Fabrications\Migration($name, []);
                $migration->createMigration();
                break;
            default:
                $this->help($output);
                break;
        }

        return 0;
    }

    private function help(OutputInterface $output)
    {
        $output->writeln("<info>Available fabrication commands are:</info>" . PHP_EOL);
        foreach (static::$subCommands as $subCommand) {
            if ($subCommand == "Forge\\core\\Console\\Commands\\FabricationCommands\\MigrationFabricationCommand") {
                $output->writeln("<info>php forge fabricate " .
                    strtolower(str_replace("FabricationCommand", "", explode("\\", $subCommand)[count(explode("\\", $subCommand)) - 1]))
                    . " <name> --migration </info>");
                continue;
            }

            $output->writeln("<info>php forge fabricate " .
                strtolower(str_replace("FabricationCommand", "", explode("\\", $subCommand)[count(explode("\\", $subCommand)) - 1]))
                . " <name> </info>");
        }

        $output->writeln(PHP_EOL . "OR" . PHP_EOL);

        $output->writeln("<info>php forge fabricate:<command> <name> <[--migration][-m]></info>");
    }

    public static function getSubCommands()
    {
        return static::$subCommands;
    }
}
