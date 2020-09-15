<?php

namespace App\Entity;

use App\Repository\TownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TownRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message = "Un canton est déja enregistrer avec le même nom",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Town
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de commune valide")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="towns")
     * @Assert\NotBlank(message="Veuillez choisir une région valide")
     */
    private $region;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;


    /**
     * @ORM\OneToMany(targetEntity=Prefecture::class, mappedBy="town")
     */
    private $prefectures;

    public function __construct()
    {
        $this->prefectures = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
     * @return Collection|Prefecture[]
     */
    public function getPrefectures(): Collection
    {
        return $this->prefectures;
    }

    public function addPrefecture(Prefecture $prefecture): self
    {
        if (!$this->prefectures->contains($prefecture)) {
            $this->prefectures[] = $prefecture;
            $prefecture->setTown($this);
        }

        return $this;
    }

    public function removePrefecture(Prefecture $prefecture): self
    {
        if ($this->prefectures->contains($prefecture)) {
            $this->prefectures->removeElement($prefecture);
            // set the owning side to null (unless already changed)
            if ($prefecture->getTown() === $this) {
                $prefecture->setTown(null);
            }
        }

        return $this;
    }
}
