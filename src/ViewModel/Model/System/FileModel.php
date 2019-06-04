<?php

namespace App\ViewModel\Model\System;

class FileModel
{
    /** @var int */
    private $id;

    /** @var string */
    private $destination;

    /** @var int */
    private $size;

    /** @var string */
    private $mime;

    /** @var UserModel */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): FileModel
    {
        $this->id = $id;

        return $this;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): FileModel
    {
        $this->destination = $destination;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): FileModel
    {
        $this->size = $size;

        return $this;
    }

    public function getMime(): string
    {
        return $this->mime;
    }

    public function setMime(string $mime): FileModel
    {
        $this->mime = $mime;

        return $this;
    }

    public function getUser(): UserModel
    {
        return $this->user;
    }

    public function setUser(UserModel $user): FileModel
    {
        $this->user = $user;

        return $this;
    }
}
