<?php

namespace App\Entity;

use App\Repository\ControlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlRepository::class)
 */
class Control
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Trader::class, inversedBy="controls")
     */
    private $trader;

    /**
     * @ORM\ManyToOne(targetEntity=Pointofsale::class, inversedBy="controls")
     */
    private $pointofsale;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrader(): ?Trader
    {
        return $this->trader;
    }

    public function setTrader(?Trader $trader): self
    {
        $this->trader = $trader;

        return $this;
    }

    public function getPointofsale(): ?Pointofsale
    {
        return $this->pointofsale;
    }

    public function setPointofsale(?Pointofsale $pointofsale): self
    {
        $this->pointofsale = $pointofsale;

        return $this;
    }

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
