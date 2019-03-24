<?php

namespace App\Command;

use App\Entity\Firms;
use App\Entity\ImportSource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ImportFirms extends Command
{
    private const BATCH_SIZE = 300;

    private const DIR_ARGUMENT = 'dir';

    private const SOURCES = [
        'https://firms.modaps.eosdis.nasa.gov/data/active_fire/c6/csv/MODIS_C6_Europe_24h.csv',
        'https://firms.modaps.eosdis.nasa.gov/data/active_fire/viirs/csv/VNP14IMGTDL_NRT_Europe_24h.csv',
    ];

    /** @var EntityManagerInterface */
    private $em;

    /** @var OutputInterface */
    private $output;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setName('import:firms')
            ->addArgument(
                self::DIR_ARGUMENT,
                InputArgument::OPTIONAL,
                'Directory with historical FIRMS data in csv format.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;

        $dir = $input->getArgument(self::DIR_ARGUMENT);

        $files = [];
        if ($dir === null) {
            $files = $this->downloadFiles();
        }

        if ($dir) {
            $finder = new Finder();
            $finder->name('*.csv')->files()->in($dir);
            $files = $finder->getIterator();
        }

        foreach ($files as $uri => $file) {
            $sha = sha1_file($file->getRealPath());

            $imported = $this->em->getRepository(ImportSource::class)->findOneBy(
                ['sha1' => $sha, 'type' => ImportSource::TYPE_FIRMS]
            );

            if ($imported) {
                $this->output->writeln(\sprintf('<info>Skipping imported file: %s</info>', $uri));
                continue;
            }

            $this->output->writeln(\sprintf('<info>Importing FIRMS file: %s</info>', $uri));
            $this->importFile($file->getRealPath(), $this->isRemote($uri));

            $source = new ImportSource();
            $source->setType(ImportSource::TYPE_FIRMS);
            $source->setUri($uri);
            $source->setSha1($sha);
            $this->em->persist($source);
            $this->em->flush();
        }

        if ($dir === null) {
            $this->deleteFiles($files);
        }
    }

    private function importFile(string $filename, bool $remote): void
    {
        $file = fopen($filename, 'rb');
        $counter = 0;
        while (($line = fgetcsv($file)) !== false) {
            if ($counter === 0) {
                $counter++;
                continue;
            }

            $date = new \DateTime($line[5], new \DateTimeZone('UTC'));
            $date->setTime($this->getHour($line[6]), $this->getMinute($line[6]));

            $firms = new Firms();
            $firms->setLatitude($line[0]);
            $firms->setLongitude($line[1]);
            $firms->setBrightness1($line[2]);
            $firms->setScan($line[3]);
            $firms->setTrack($line[4]);
            $firms->setDate($date);

            if ($remote) {
                $firms->setSatellite($line[7]);
                $firms->setConfidence($line[8]);
                $firms->setVersion($line[9]);
                $firms->setBrightness2($line[10]);
                $firms->setPower($line[11]);
                $firms->setDaytime($line[12] === 'D');
            }

            if (!$remote) {
                $firms->setInstrument($line[8]);
                $firms->setConfidence($line[9]);
                $firms->setVersion($line[10]);
                $firms->setBrightness2($line[11]);
                $firms->setPower((float)$line[12]);
            }

            $this->em->persist($firms);

            if (($counter % self::BATCH_SIZE) === 0) {
                $this->em->flush();
            }

            $counter++;
        }

        $this->em->flush();
        $this->output->writeln(\sprintf('<info>Imported %s FIRMS records.</info>', $counter));

        fclose($file);
    }

    private function getHour(string $time): int
    {
        $time = $this->fixTime($time);

        return substr($time, 0, 2);
    }

    private function getMinute(string $time): int
    {
        $time = $this->fixTime($time);

        return substr($time, 2);
    }

    private function fixTime(string $time): string
    {
        if (\strlen($time) === 3) {
            $time = '0'.$time;
        }

        return $time;
    }

    /**
     * @param \SplFileInfo[] $files
     */
    private function deleteFiles(array $files): void
    {
        foreach ($files as $file) {
            unlink($file->getRealPath());
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    private function downloadFiles(): array
    {
        $files = [];

        foreach (self::SOURCES as $source) {
            $data = file_get_contents($source);
            $sha = sha1($data);

            $imported = $this->em->getRepository(ImportSource::class)->findOneBy(
                ['sha1' => $sha, 'type' => ImportSource::TYPE_FIRMS]
            );

            if ($imported) {
                $this->output->writeln(\sprintf('<info>Skipping imported file: %s</info>', $source));
                continue;
            }

            $filename = tempnam(sys_get_temp_dir(), ImportSource::TYPE_FIRMS);
            $files[$source] = new \SplFileInfo($filename);
            file_put_contents($filename, $data);
        }

        return $files;
    }

    private function isRemote(string $uri): bool
    {
        return strpos($uri, 'http') === 0;
    }
}
