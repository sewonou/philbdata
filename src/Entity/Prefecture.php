<?php

namespace App\Entity;

use App\Repository\PrefectureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PrefectureRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message = "Une préfecture est déja enregistrer avec le même nom",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Prefecture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de préfecture valide")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Town::class, inversedBy="prefectures")
     * @Assert\NotBlank(message="Veuillez choisir une commune valide")
     */
    private $town;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Township::class, mappedBy="prefecture")
     */
    private $townships;

    public function __construct()
    {
        $this->townships = new ArrayCollection();
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

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): self
    {
        $this->town = $town;

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

    /**
     * @return Collection|Township[]
     */
    public function getTownships(): Collection
    {
        return $this->townships;
    }

    public function addTownship(Township $township): self
    {
        if (!$this->townships->contains($township)) {
            $this->townships[] = $township;
            $township->setPrefecture($this);
        }

        return $this;
    }

    public function removeTownship(Township $township): self
    {
        if ($this->townships->contains($township)) {
            $this->townships->removeElement($township);
            // set the owning side to null (unless already changed)
            if ($township->getPrefecture() === $this) {
                $township->setPrefecture(null);
            }
        }

        return $this;
    }
}
