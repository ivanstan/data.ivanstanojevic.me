<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ImportMODIS extends Command
{
    protected function configure(): void
    {
        $this->setName('import:modis');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $finder = new Finder();
        $finder->name('*.json')->files()->in('/Users/ivanstan/Downloads/Active Fire Data');

        foreach ($finder as $file) {
            $json = json_decode($file->getContents(), true);



        }
    }
}
