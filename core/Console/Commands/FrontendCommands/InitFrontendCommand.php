<?php

namespace Forge\core\Console\Commands\FrontendCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Helper\HelperInterface;

use Forge\core\Frontend\InitializeFrontend;
use Forge\core\Utilities\Methods;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class InitFrontendCommand extends Command
{
    protected $commandName = "frontend:init";
    protected $commandDescription = "Initialize frontend views specifically for frameworks works independently or as mixins";
    private HelperInterface $helper;

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp("This command initialize frontend views specifically for frameworks works independently or as mixins");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->helper = $this->getHelper("question");
        $output->writeln("<info>Initialzing frontend</info>\n");

        $question = new ChoiceQuestion('<info>Please choose Package Manager [default: npm]</info>', ["npm", "pnpm", "yarn", "bunjs"], "npm");

        $pkgManager = $this->helper->ask($input, $output, $question);

        $output->writeln("\nSelected '$pkgManager' as PackagrManager\n");

        $question = new ChoiceQuestion('<info>How do you wanna implement frontend? [default: mixins]</info>', ["independent", "mixins"], "mixins");

        $frontendWay = $this->helper->ask($input, $output, $question);

        $output->writeln("\nSelected '$frontendWay' as a way for Frontend\n");

        $question = new ConfirmationQuestion("<info>Typescript? [default: Yes]</info>");

        $typescript = $this->helper->ask($input, $output, $question);

        $typescriptString =  Methods::getTrueFalseString($typescript);

        $output->writeln("\nTypescript Enabled: $typescriptString \n");

        $init = new InitializeFrontend($pkgManager, $frontendWay, $typescript);

        $init->make();

        return 1;
    }
}
