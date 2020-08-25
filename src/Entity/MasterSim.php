<?php

namespace App\Entity;

use App\Repository\MasterSimRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MasterSimRepository::class)
 * @UniqueEntity(
 *     fields={"msisdn"},
 *     message="Ce numéro est déja enregistrer"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class MasterSim
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Veuillez sasir un numéro valide")
     * @Assert\Regex(
     *     pattern="^[0-9]+$",
     *     htmlPattern="^[0-9]+$",
     *     message="Le numéro doit être composé de chiffre uniquement"
     * )
     */
    private $msisdn;

    /**
     * @ORM\Column(type="string", length=155)
     * @Assert\NotBlank(message="Veuillez sasir un nom valide")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;


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

    public function getMsisdn(): ?string
    {
        return $this->msisdn;
    }

    public function setMsisdn(string $msisdn): self
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
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
}
