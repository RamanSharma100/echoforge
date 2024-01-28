<?php

namespace Forge\core\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{

    protected $commandName = "migrate";

    protected $commandDescription = "Command to run migrations";

    protected $commandHelp = "This command runs migrations";


    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command runs migrations");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Running migrations</info>");
        $this->runMigrations($output);
        $output->writeln("<info>Migrations completed</info>");
        return 1;
    }

    protected function runMigrations($output)
    {
        $migrations = scandir("database/migrations");
        foreach ($migrations as $migration) {
            if ($migration != "." && $migration != "..") {

                $migration = explode("\\", $migration)[count(explode("\\", $migration)) - 1];

                $path = "database/migrations/" . $migration;

                if (file_exists($path)) {
                    $migration = include_once($path);

                    if (
                        $migration instanceof \Forge\core\Migration
                    ) {
                        $migration->up();
                    } else {
                        $output->writeln("<error>Invalid Migration File!!" .
                            $path .
                            "</error>");
                    }
                } else {
                    $output->writeln("<error>Migration file " .
                        $path . " not found</error>");
                }
            }
        }

        return 0;
    }
}
