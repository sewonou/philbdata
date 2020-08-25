<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Config
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $configAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un titre valide.")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=ConfigFile::class, mappedBy="config")
     * @Assert\Valid()
     */
    private $files;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;


    public function __construct()
    {
        $this->files = new ArrayCollection();
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

    public function getConfigAt(): ?\DateTimeInterface
    {
        return $this->configAt;
    }

    public function setConfigAt(?\DateTimeInterface $configAt): self
    {
        $this->configAt = $configAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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
     * @return Collection|ConfigFile[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(ConfigFile $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setConfig($this);
        }

        return $this;
    }

    public function removeFile(ConfigFile $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getConfig() === $this) {
                $file->setConfig(null);
            }
        }

        return $this;
    }
}
