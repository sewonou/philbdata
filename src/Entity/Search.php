<?php

namespace App\Entity;

use App\Repository\SearchRepository;

class Search
{

    /**
     * @var \DateTime | null
     */
    private $startAt;

    /**
     * @var \DateTime | null
     */
    private $endAt;


    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }
}
