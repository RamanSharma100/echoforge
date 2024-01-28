<?php

namespace Forge\core\Console\Commands\FabricationCommands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Forge\core\Fabrications\Migration as MigrationFabrication;

class MigrationFabricationCommand extends Command
{
    protected $commandName = "fabricate:migration";
    protected $commandDescription = "Create a migration file";

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command creates a migration file")
            ->addArgument(
                "name",
                InputArgument::REQUIRED,
                "The name of the model"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $output->writeln("<info>Creating Migration: $name</info>");

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

        return 0;
    }
}
