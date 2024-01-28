<?php

namespace Forge\core\Console\Commands\FabricationCommands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Forge\core\Fabrications\Model as ModelFabrication;
use Forge\core\Fabrications\Migration as MigrationFabrication;
use Symfony\Component\Console\Input\InputOption;

class ModelFabricationCommand extends Command
{
    protected $commandName = "fabricate:model";
    protected $commandDescription = "Create a model file";


    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command creates a controller, model and migration")
            ->addArgument(
                "name",
                InputArgument::REQUIRED,
                "The name of the model"
            )->addOption(
                "migration",
                "m",
                InputOption::VALUE_NONE,
                "Create a migration"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $option = $input->getOption("migration");
        $output->writeln("<info>Creating Model: $name</info>");

        $modelFabrication = new ModelFabrication($name);

        if ($option) {
            $migrationFabrication = new MigrationFabrication(
                $name,
                [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'length' => 11,
                        'auto_increment' => true,
                        'primary_key' => true
                    ]
                ]
            );
            $migrationFabrication->createMigration();
            $modelFabrication->createModel();
            return 0;
        }

        $modelFabrication->createModel();
        return 0;
    }
}
