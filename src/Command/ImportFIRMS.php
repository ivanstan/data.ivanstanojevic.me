<?php

namespace App\Command;

use App\Entity\Firms;
use App\Field\LatLng;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ImportFIRMS extends Command
{
    private const BATCH_SIZE = 100;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setName('import:firms');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $finder = new Finder();
        $finder->name('*.csv')->files()->in('/Users/ivanstan/Downloads/Active Fire Data');

        foreach ($finder as $file) {
            $this->importFile($file->getRealPath());
        }
    }

    private function importFile(string $filename): void
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
            $firms->setPoint(new LatLng($line[0], $line[1]));
            $firms->setBrightness($line[2]);
            $firms->setBrightness31($line[11]);
            $firms->setPower($line[12]);
            $firms->setDate($date);
            $firms->setConfidence($line[9]);
            $firms->setInstrument($line[8]);
            $firms->setVersion($line[10]);
            $firms->setScan($line[3]);
            $firms->setTrack($line[4]);

            $this->em->persist($firms);

            if (($counter % self::BATCH_SIZE) === 0) {
                $this->em->flush();
            }

            $counter++;
        }

        $this->em->flush();

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
}
