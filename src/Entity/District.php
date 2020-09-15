<?php

namespace App\Entity;

use App\Repository\DistrictRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DistrictRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message = "Un quarier est déja enregistrer avec le même nom",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class District
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de quartier valide")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Township::class, inversedBy="districts")
     * @Assert\NotBlank(message="Veuillez choisir un canton valide")
     */
    private $township;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;


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

    public function getTownship(): ?Township
    {
        return $this->township;
    }

    public function setTownship(?Township $township): self
    {
        $this->township = $township;

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
}
