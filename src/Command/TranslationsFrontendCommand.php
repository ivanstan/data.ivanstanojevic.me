<?php

namespace App\Command;

use App\Service\System\FileManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TranslationsFrontendCommand extends Command
{
    protected static $defaultName = 'translations:frontend';

    /** @var string */
    private $rootDir;

    /** @var FileManager */
    private $fileManager;

    public function __construct($rootDir, FileManager $fileManager)
    {
        parent::__construct();

        $this->rootDir = $rootDir;
        $this->fileManager = $fileManager;
    }


    protected function configure(): void
    {
        $this->setDescription('Exports translations to frontend.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in($this->rootDir.DIRECTORY_SEPARATOR.'translations');

        $translations = [];

        foreach ($finder as $file) {
            $fileParts = explode('.', $file->getFileInfo()->getFilename());

            $language = array_slice($fileParts, -2, 1)[0];
            $domain = array_slice($fileParts, -3, count($fileParts))[0];
            $data = Yaml::parseFile($file->getRealPath());

            $translations[$language][$domain] = $data;
        }

        $io = new SymfonyStyle($input, $output);

        foreach ($translations as $language => $data) {
            $javascript = "export const $language = {";
            $this->addSubTree($javascript, $data);
            $javascript .= '};' . "\n";

            $fileName = $this->rootDir."/assets/js/translations/translation.$language.js";

            $this->fileManager->save($fileName, $javascript);
            $io->success(\sprintf('Translations saved to %s', $fileName));
        }
    }

    protected function addSubTree(string &$contents, array $data): void
    {
        foreach ($data as $key => $item) {

            if (is_array($item)) {
                $contents .= "'$key': {";
                $this->addSubTree($contents, $item);
                $contents .= '},' . "\n";

                continue;
            }

            $item = str_replace("\n", '', $item);
            $item = str_replace('"', '\"', $item);

            $contents .= '"'.$key.'": "'.$item.'",' . "\n";
        }
    }
}
