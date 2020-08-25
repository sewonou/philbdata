<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message = "Une region est déja enregistrer avec le même nom",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de région valide")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="regions")
     * @Assert\NotBlank(message="Veuillez sélectionner une zone valide")
     */
    private $zone;

    /**
     * @ORM\OneToMany(targetEntity=Town::class, mappedBy="region")
     */
    private $towns;

    /**
     * @ORM\OneToMany(targetEntity=Trader::class, mappedBy="region")
     */
    private $traders;

    public function __construct()
    {
        $this->towns = new ArrayCollection();
        $this->traders = new ArrayCollection();
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     */
    public function initialized()
    {
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection|Town[]
     */
    public function getTowns(): Collection
    {
        return $this->towns;
    }

    public function addTown(Town $town): self
    {
        if (!$this->towns->contains($town)) {
            $this->towns[] = $town;
            $town->setRegion($this);
        }

        return $this;
    }

    public function removeTown(Town $town): self
    {
        if ($this->towns->contains($town)) {
            $this->towns->removeElement($town);
            // set the owning side to null (unless already changed)
            if ($town->getRegion() === $this) {
                $town->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Trader[]
     */
    public function getTraders(): Collection
    {
        return $this->traders;
    }

    public function addTrader(Trader $trader): self
    {
        if (!$this->traders->contains($trader)) {
            $this->traders[] = $trader;
            $trader->setRegion($this);
        }

        return $this;
    }

    public function removeTrader(Trader $trader): self
    {
        if ($this->traders->contains($trader)) {
            $this->traders->removeElement($trader);
            // set the owning side to null (unless already changed)
            if ($trader->getRegion() === $this) {
                $trader->setRegion(null);
            }
        }

        return $this;
    }
}
