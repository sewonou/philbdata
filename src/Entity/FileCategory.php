<?php

namespace App\Entity;

use App\Repository\FileCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FileCategoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class FileCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un titre valide")
     *
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un slug valide")
     * @Assert\Length(
     *     max=12,
     *     maxMessage="Le slug ne peut pas dépasser 12 carrectères"
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-zA-Z0-9]+$",
     *     message="Le slug ne doit pas contenir d'espace"
     * )
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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


}
