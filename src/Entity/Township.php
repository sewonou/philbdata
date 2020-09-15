<?php

namespace App\Entity;

use App\Repository\TownshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TownshipRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message = "Un canton est déja enregistrer avec le même nom",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Township
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de canton valide")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Prefecture::class, inversedBy="townships")
     * @Assert\NotBlank(message="Veuillez choisir une préfecture valide")
     */
    private $prefecture;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\OneToMany(targetEntity=District::class, mappedBy="township")
     */
    private $districts;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
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

    public function getPrefecture(): ?Prefecture
    {
        return $this->prefecture;
    }

    public function setPrefecture(?Prefecture $prefecture): self
    {
        $this->prefecture = $prefecture;

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


    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setTownship($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);
            // set the owning side to null (unless already changed)
            if ($district->getTownship() === $this) {
                $district->setTownship(null);
            }
        }

        return $this;
    }
}
