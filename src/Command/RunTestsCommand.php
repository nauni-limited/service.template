<?php

namespace App\Command;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function dd;

class RunTestsCommand extends Command
{

    protected static $defaultName = 'app:run-tests';

    protected function configure(): void
    {
        $this
            ->setDescription('Runs all tests for a suite')
            ->setHelp('This command allows run your tests for a given suite')
            ->addArgument('suite', InputArgument::REQUIRED, 'Suite');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('src'));

        $files = [];

        foreach ($rii as $file) {
            if ($file->isDir()){
                continue;
            }

            $files[] = $file->getPathname());

        }


        return Command::SUCCESS;
    }
}