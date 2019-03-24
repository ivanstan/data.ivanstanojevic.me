<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection HtmlUnknownTag */

namespace App\Command;

use App\Entity\Metar;
use App\Repository\MetarRepository;
use Doctrine\ORM\EntityManagerInterface;
use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\MetarDecoder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use TafDecoder\TafDecoder;

class ImportMetarCommand extends Command
{
    private const BATCH_SIZE = 50;

    private const URL = 'https://www.aviationweather.gov/metar/data';

    private const IMPORT_AIRPORT_ICAO = [
        'LYBE', // Belgrade Nikola Tesla Airport
        'LYNI', // Niš Constantine the Great Airport
        'LYVR', // Vršac
        'LYBJ', // Lisičiji Jarak
        'LYUZ', // Ponikve
        'LYSO', // Sombor
        'LYKT', // Kostolac
        'LYNS', // Novi Sad
        'LYKV', // Lađevci
    ];

    /** @var EntityManagerInterface */
    private $em;

    /** @var MetarRepository */
    private $repository;

    /** @var MetarDecoder */
    private $metarDecoder;

    /** @var TafDecoder */
    private $tafDecoder;

    /** @var OutputInterface */
    private $output;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->repository = $this->em->getRepository(Metar::class);
        $this->metarDecoder = new MetarDecoder();
        $this->tafDecoder = new TafDecoder();
    }

    protected function configure(): void
    {
        $this->setName('import:metar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;

        $data = $this->download();
        $data = $this->parse($data);
        $data = $this->prepare($data);

        $counter = 0;
        foreach ($data as $item) {
            $metar = new Metar();
            $metar->setIcao($item->getIcao());
            $metar->setDate($this->getMeterDateTime($item));
            $metar->setMetar($item->getRawMetar());

            $type = strpos($item->getRawMetar(), Metar::TYPE_TAF) !== false ? Metar::TYPE_TAF : Metar::TYPE_METAR;
            $metar->setType($type);

            $this->em->persist($metar);
            $this->output->writeln(\sprintf('<info>METAR record queued for insert: %s</info>', $item->getRawMetar()));

            if (($counter % self::BATCH_SIZE) === 0) {
                $this->em->flush();
            }
            ++$counter;
        }
        $this->em->flush();
        $this->output->writeln(\sprintf('<info>Finished: Inserted %d METAR records to database</info>', $counter));
    }

    private function download(): array
    {
        $this->output->writeln(\sprintf('Downloading METAR information from %s', $this->getUrl()));
        $html = file_get_contents($this->getUrl());
        $crawler = new Crawler($html);
        $nodes = $crawler->filter('#awc_main_content_wrap code');

        $result = [];

        foreach ($nodes as $node) {
            $raw = $node->textContent;
            $type = Metar::TYPE_METAR;

            if (strpos($raw, 'TAF') === 0) {
                $raw = trim(substr($raw, 2));
                $type = Metar::TYPE_TAF;
            }

            $result[] = [
                'raw' => $raw,
                'type' => $type,
            ];
        }

        return $result;
    }

    public function parse(array $data): array
    {
        $results = [];
        foreach ($data as $item) {
            $object = null;

            if ($item['type'] === Metar::TYPE_METAR) {
                $object = $this->metarDecoder->parseNotStrict($item['raw']);
            }

            if ($object) {
                $results[] = $object;
            }
        }

        return $results;
    }

    /**
     * @param DecodedMetar[] $data
     *
     * @return DecodedMetar[]
     */
    public function prepare(array $data): array
    {
        $latest = [];
        foreach (self::IMPORT_AIRPORT_ICAO as $icao) {
            $latest[$icao] = $this->getDateTimeEpochStart();

            try {
                $metar = $this->repository->latest($icao);
            } catch (\Exception $exception) {
                $this->output->writeln('<error>'.$exception->getMessage().'</error>');
                $metar = null;
            }

            if ($metar) {
                $latest[$icao] = $metar->getDate();
            }
        }

        foreach ($data as $key => $item) {
            if (!isset($latest[$item->getIcao()])) {
                unset($data[$key]);
                continue;
            }

            $date = $latest[$item->getIcao()];

            if ($date >= $this->getMeterDateTime($item)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    private function getMeterDateTime(DecodedMetar $metar): \DateTime
    {
        $date = new \DateTime($metar->getTime(), new \DateTimeZone('UTC'));
        $date->setDate($date->format('Y'), $date->format('m'), $metar->getDay());

        return $date;
    }

    private function getDateTimeEpochStart(): \DateTime
    {
        $date = new \DateTime();
        $date->setDate(1970, 1, 1);
        $date->setTime(0, 0, 0);

        return $date;
    }

    private function getUrl(): string
    {
        $query = [
            'ids' => implode(',', self::IMPORT_AIRPORT_ICAO),
            'format' => 'raw',
            'hours' => 36,
            'taf' => 'on',
        ];

        return self::URL.'?'.http_build_query($query);
    }
}
