<?php

namespace App\Event\Doctrine;

use App\Entity\User;
use App\Service\FileManager;
use App\Service\ThemeService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserListener
{
    /** @var FileManager */
    private $manager;
    /** @var ThemeService */
    private $theme;

    public function __construct(FileManager $manager, ThemeService $theme)
    {
        $this->manager = $manager;
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
//            $user->setAvatar($this->getUIAvatar($user->getEmail()));
        }

//        if ($fileName = $user->getAvatar()) {
//            $user->setAvatar(new File($this->manager->getTargetDirectory().'/'.$fileName));
//        }
    }

    public function getUIAvatar(string $name): string
    {
        $hash = md5(strtolower(trim($name)));

        $fallback = 'https://ui-avatars.com/api/?name='.$name.'&background='.$this->theme->getBackgroundColor(
            ).'&color='.$this->theme->getPrimaryColor();

        return $fallback;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity): void
    {
        // upload only works for Product entities
        if (!$entity instanceof User) {
            return;
        }

        $file = $entity->getAvatar();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->manager->upload($file);
            $entity->setAvatar($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setAvatar($file->getFilename());
        }
    }
}
