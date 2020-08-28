<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BalanceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Balance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SimCard::class)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez choisir un numéro valide")
     */
    private $msisdn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $executionAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initialized()
    {
        $this->updateAt = new \DateTime();
    }

    public function getMsisdn(): ?SimCard
    {
        return $this->msisdn;
    }

    public function setMsisdn(?SimCard $msisdn): self
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    public function getExecutionAt(): ?\DateTimeInterface
    {
        return $this->executionAt;
    }

    public function setExecutionAt(?\DateTimeInterface $executionAt): self
    {
        $this->executionAt = $executionAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

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
