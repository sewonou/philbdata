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

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUpload = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLoad;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLoadDeposit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLoadWithdrawal;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLoadOther;

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

    public function getIsUpload(): ?bool
    {
        return $this->isUpload;
    }

    public function setIsUpload(?bool $isUpload): self
    {
        $this->isUpload = $isUpload;

        return $this;
    }

    public function getIsLoad(): ?bool
    {
        return $this->isLoad;
    }

    public function setIsLoad(?bool $isLoad): self
    {
        $this->isLoad = $isLoad;

        return $this;
    }

    public function getIsLoadDeposit(): ?bool
    {
        return $this->isLoadDeposit;
    }

    public function setIsLoadDeposit(?bool $isLoadDeposit): self
    {
        $this->isLoadDeposit = $isLoadDeposit;

        return $this;
    }

    public function getIsLoadWithdrawal(): ?bool
    {
        return $this->isLoadWithdrawal;
    }

    public function setIsLoadWithdrawal(?bool $isLoadWithdrawal): self
    {
        $this->isLoadWithdrawal = $isLoadWithdrawal;

        return $this;
    }

    public function getIsLoadOther(): ?bool
    {
        return $this->isLoadOther;
    }

    public function setIsLoadOther(?bool $isLoadOther): self
    {
        $this->isLoadOther = $isLoadOther;

        return $this;
    }

}
