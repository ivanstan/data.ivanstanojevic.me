<?php

namespace App\Entity;

use App\Field\CountryField;
use App\Field\DescriptionField;
use App\Field\IdField;
use App\Field\LatLngField;
use App\Field\NameField;
use App\Field\TypeField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Location
{
    public const TYPE_ALLERGY_CONTROL = 'allergy';

    use IdField;
    use NameField;
    use TypeField;
    use LatLngField;
    use DescriptionField;
    use CountryField;
}
