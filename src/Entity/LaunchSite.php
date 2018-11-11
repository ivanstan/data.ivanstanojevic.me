<?php

namespace App\Entity;

use App\Field\IdField;
use App\Field\LatLngField;
use App\Field\NameField;

class LaunchSite
{
    use IdField;
    use NameField;
    use LatLngField;
}
