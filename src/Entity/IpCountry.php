<?php

namespace App\Entity;

use App\Field\IdField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ip_country")
 */
class IpCountry
{
    use IdField;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", nullable=true)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", nullable=true)
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", nullable=true)
     */
    private $country;
}
