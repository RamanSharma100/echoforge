<?php

namespace Forge\core\Console\Commands\FabricationCommands;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Forge\core\Fabrications\Controller as ControllerFabrication;

class ControllerFabricationCommand extends Command
{
    protected $commandName = "fabricate:controller";
    protected $commandDescription = "Create a controller";

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command creates a controller, model and migration")
            ->addArgument(
                "name",
                InputArgument::REQUIRED,
                "The name of the controller or model"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $output->writeln("<info>Creating Controller: $name</info>");



        $controllerFabrication = new ControllerFabrication($name);
        $controllerFabrication->createController();

        return 0;
    }
}
