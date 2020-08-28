<?php

namespace App\Entity;

use App\Repository\ConfigFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConfigFileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class ConfigFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\File(
     *     maxSize="12M",
     *     maxSizeMessage="Le fichier ne doit pas excéder 12M"
     * )
     * @Vich\UploadableField(mapping="config_file", fileNameProperty="fileName")
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @ORM\ManyToOne(targetEntity=FileCategory::class)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=Config::class, inversedBy="files")
     */
    private $config;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $file
     * @throws \Exception
     */
    public function setFile(?File  $file = null): void
    {
        $this->file = $file;
        if (null !== $file) {
            $this->updateAt = new \DateTimeImmutable();
        }

    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getCategory(): ?FileCategory
    {
        return $this->category;
    }

    public function setCategory(?FileCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getConfig(): ?Config
    {
        return $this->config;
    }

    public function setConfig(?Config $config): self
    {
        $this->config = $config;
        return $this;
    }

}
