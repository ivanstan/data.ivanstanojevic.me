<?php

namespace App\Event\Doctrine;

use App\Entity\User;
use App\Service\ThemeService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{
    /** @var ThemeService */
    private $theme;

    public function __construct(ThemeService $theme)
    {
        $this->theme = $theme;
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        if (!$args->getEntity() instanceof User) {
            return;
        }

        /** @var User $user */
        $user = $args->getEntity();

        if (!$user->getAvatar()) {
            $user->setAvatar($this->getUIAvatar($user->getEmail()));
        }
    }

    public function getUIAvatar(string $name): string
    {
        $hash = md5(strtolower(trim($name)));

        $fallback = 'https://ui-avatars.com/api/?name='.$name.'&background='.$this->theme->getBackgroundColor(
            ).'&color='.$this->theme->getPrimaryColor();

        return $fallback;
    }
}
