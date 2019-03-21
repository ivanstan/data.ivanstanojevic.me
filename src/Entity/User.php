<?php

namespace App\Entity;

use App\Service\DateTimeService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="user.email.unique")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLES = [
        'Admin' => self::ROLE_ADMIN,
        'User' => self::ROLE_USER,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $ip;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $active = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $verified = false;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreference", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="preference_id", referencedColumnName="id")
     */
    protected $preference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreated(): void
    {
        $this->created = DateTimeService::getCurrentUTC();
        $this->updated = DateTimeService::getCurrentUTC();
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdated(): void
    {
        $this->updated = DateTimeService::getCurrentUTC();
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }

    public function getPreference(): ?UserPreference
    {
        if ($this->preference) {
            return $this->preference;
        }

        return new UserPreference();
    }

    public function setPreference(UserPreference $preference): void
    {
        $this->preference = $preference;
    }

    public function getAvatar(): string
    {
        $hash = md5(strtolower(trim($this->getEmail())));

        $fallback = 'https://ui-avatars.com/api/'.$this->getEmail().'?background=007bff&color=fff';

        return 'https://www.gravatar.com/avatar/'.$hash.'?d='.$fallback;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
}
