<?php

namespace App\Command;

use App\Entity\Location;
use App\Entity\Pollen;
use App\Entity\PollenType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see http://data.sepa.gov.rs/group/polen
 */
class PollenImport extends Command
{
    public const LOCATIONS = 'http://data.sepa.gov.rs/dataset/69c0f898-b55f-443c-b863-d16f679774a9/resource/ba44a6ca-7f9a-4f91-a73e-f0c42be664a2/download/polenlokacije_0.json';

    public const TYPES = 'http://data.sepa.gov.rs/api/3/action/datastore_search?resource_id=d2bf27c1-4863-422f-b8ed-0e4197d858ef';

    public const POLLEN = 'http://data.sepa.gov.rs/api/3/action/datastore_search?resource_id=7186b8fa-a62e-4253-a1b7-cf0ea63cd029&limit=5000';

    /** @var EntityManagerInterface */
    private $em;

    private $location;

    private $type;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setName('import:pollen');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->location = $this->getLocation();
        $this->type = $this->getType();

        $this->em->flush();

        $data = json_decode(file_get_contents(self::POLLEN), true);
        $this->persist($data['result']['records']);
        $output->writeln(\sprintf('Downloaded from: %s', self::POLLEN));
        $next = $data['result']['_links']['next'];

        $base = parse_url(self::POLLEN, PHP_URL_HOST);
        $scheme = parse_url(self::POLLEN, PHP_URL_SCHEME);
        while ($next !== null) {
            $data = json_decode(file_get_contents($scheme.'://'.$base.$next), true);
            $this->persist($data['result']['records']);
            $output->writeln(\sprintf('Downloaded from: %s', $scheme.'://'.$base.$next));
            $next = $data['result']['_links']['next'] ?? null;
        }
    }

    private function persist(array $data): void
    {
        foreach ($data as $item) {
            $location = $this->location[$item['ID_LOKACIJE']];
            $type = $this->type[$item['ID_VRSTE']];

            $pollen = new Pollen();
            $pollen->setType($type);
            $pollen->setLocation($location);
            $pollen->setDate(new \DateTime($item['Datum']));
            $pollen->setConcentration($item['KONCENTRACIJA']);
            $pollen->setTendency($item['TENDENCIJA']);

            $this->em->persist($pollen);
        }

        $this->em->flush();
    }

    /**
     * @return Location[]
     */
    private function getLocation(): array
    {
        $data = json_decode(file_get_contents(self::LOCATIONS), true);

        $result = [];
        foreach ($data as $item) {
            $location = new Location();
            $location->setName($item['IME_LOKACIJE']);
            $location->setDescription($item['OPIS_LOKACIJE']);
            $location->setLatitude($item['GEOGRAFSKA_SIRINA']);
            $location->setLongitude($item['GEOGRAFSKA_DUZINA']);
            $location->setType(Location::TYPE_ALLERGY_CONTROL);

            $this->em->persist($location);

            $result[$item['ID_LOKACIJE']] = $location;
        }

        return $result;
    }

    /**
     * @return PollenType[]
     */
    private function getType(): array
    {
        $data = file_get_contents(self::TYPES);
        $data = json_decode($data, true);

        $result = [];
        foreach ($data['result']['records'] as $item) {
            $type = new PollenType();

            $type->setName($item['IME_VRSTE_LAT']);
            $type->setGroup($item['GRUPA_BILJAKA']);
            $type->setPotential($item['ALERGENI_POTENCIJAL']);

            $this->em->persist($type);

            $result[$item['ID_VRSTE']] = $type;
        }

        return $result;
    }
}
