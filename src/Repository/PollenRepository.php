<?php

namespace App\Repository;

use App\Entity\Pollen;
use App\Service\MySqlNative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PollenRepository extends ServiceEntityRepository
{
    /** @var MySqlNative */
    private $native;

    public function __construct(RegistryInterface $registry, MySqlNative $native)
    {
        parent::__construct($registry, Pollen::class);
        $this->native = $native;
    }

    public function getAggregated(): array
    {
        $query = '
            SELECT 
            DATE_FORMAT(date, " %d-%m") as day_month, 
            pollen_type_id as type, 
            location_id as location,
            AVG(concentration) as concentration,
            SUM(case when tendency = 1 then 1 else 0 end) as tendencyFalling,
            SUM(case when tendency = 2 then 1 else 0 end) as tendencyConstant,
            SUM(case when tendency = 3 then 1 else 0 end) as tendencyRising
            FROM pollen
            GROUP BY DATE_FORMAT(date, " %d-%m"), location_id, pollen_type_id
            ORDER BY day_month
            LIMIT 1000
        ';

        $query = $this->native->prepare($query);
        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$item) {
            $tendency = 0;
            if ($item['tendencyFalling'] <= $item['tendencyConstant']) {
                $tendency = (int)$item['tendencyConstant'];
            }

            if ($tendency <= $item['tendencyRising']) {
                $tendency = (int)$item['tendencyRising'];
            }

            $item['tendency'] = $tendency;
            $item['location'] = (int)$item['location'];
            $item['type'] = (int)$item['type'];
            $item['concentration'] = (float)$item['concentration'];

            unset($item['tendencyFalling'], $item['tendencyConstant'], $item['tendencyRising']);
        }

        return $data;
    }
}
