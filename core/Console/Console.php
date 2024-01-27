<?php

namespace Forge\core\Console;

use Symfony\Component\Console\Output\OutputInterface;

class Console
{

    private OutputInterface $output;

    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    public function log($message)
    {
        $this->output->writeln($message);
    }
}
