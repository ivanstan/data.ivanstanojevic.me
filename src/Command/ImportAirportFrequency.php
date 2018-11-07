<?php

namespace App\Command;

use App\Entity\Airport;
use App\Entity\Frequency;
use App\Repository\AirportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see http://ourairports.com/data/
 */
class ImportAirportFrequency extends Command
{
    private const SOURCE = 'http://ourairports.com/data/airport-frequencies.csv';

    private const BATCH_SIZE = 100;

    /** @var EntityManagerInterface */
    private $em;

    /** @var AirportRepository */
    private $repository;

    public function __construct(EntityManagerInterface $em, AirportRepository $repository)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $repository;
    }

    protected function configure(): void
    {
        $this->setName('import:airport-freq');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $airports = $this->repository->getCollection(null);

        $data = file_get_contents(self::SOURCE);

        foreach (explode(PHP_EOL, $data) as $counter => $line) {
            if ($counter === 0) {
                continue;
            }

            $item = str_getcsv($line);
            /** @var Airport $airport */
            if (($item[5] ?? null) === null) {
                continue;
            }

            $airport = $airports[$item[2]] ?? null;

            if ($airport === null) {
                continue;
            }

            $entity = new Frequency();
            $entity->setType($item[3]);
            $entity->setDescription($item[4]);
            $entity->setFrequency($item[5]);
            $entity->setAirport($airport);

            $this->em->persist($entity);

            if (($counter % self::BATCH_SIZE) === 0) {
                $this->em->flush();
            }
        }

        $this->em->flush();
    }
}
