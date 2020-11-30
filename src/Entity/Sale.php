<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 * @UniqueEntity(
 *     fields={"refId"},
 *     message="Cet identifiant est unique"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Sale
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
     * @Assert\NotBlank(message="Veuillez sélectionner un numéro valid")
     */
    private $msisdn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dComm = 0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $posComm = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transactionAt;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class)
     */
    private $type;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Regex(
     *     pattern="^[0-9]+$",
     *     htmlPattern="^[0-9]+$",
     *     message="Le numéro doit être composé de chiffre uniquement"
     * )
     */
    private $refId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dCommCalc;

    /**
     * @ORM\Column(type="float")
     */
    private $posCommCalc;



    /**
     * @param $amount
     * @return float
     */
    private function getAGNTCommission($amount)
    {
        $commission = 0 ;
        if($amount >= 500 and $amount<=5000){
            $commission = 45;
        }elseif($amount > 5000 and $amount <= 15000){
            $commission = 200;
        }elseif($amount > 15000 and $amount <= 20000){
            $commission = 200 ;
        }elseif($amount > 20000 and $amount <= 50000){
            $commission = 400 ;
        }elseif($amount > 50000 and $amount <= 100000){
            $commission = 900 ;
        }elseif($amount > 100000 and $amount <= 200000){
            $commission = 1500 ;
        }elseif($amount > 200000 and $amount <= 300000){
            $commission = 2200 ;
        }elseif($amount > 300000 and $amount <= 500000){
            $commission = 2475 ;
        }elseif($amount > 500000 and $amount <= 850000){
            $commission = 2585 ;
        }elseif($amount > 850000 and $amount <= 1000000){
            $commission = 2695 ;
        }elseif($amount > 1000000 and $amount <= 1500000){
            $commission = 3190 ;
        }elseif($amount > 1500000 and $amount <= 2000000){
            $commission = 4785 ;
        }
        return $commission;
    }

    /**
     * @param $amount
     * @return float
     */
    private function getCSINCommission($amount)
    {
        $commission = 0 ;
        if($amount >=500 and $amount<=5000){
            $commission = 25;
        }elseif($amount > 5000 and $amount <= 15000){
            $commission = 75;
        }elseif($amount > 15000 and $amount <= 20000){
            $commission = 150 ;
        }elseif($amount > 20000 and $amount <= 50000){
            $commission = 150 ;
        }elseif($amount > 50000 and $amount <= 100000){
            $commission = 300 ;
        }elseif($amount > 100000 and $amount <= 200000){
            $commission = 400 ;
        }elseif($amount > 200000 and $amount <= 300000){
            $commission = 400 ;
        }elseif($amount > 300000 and $amount <= 500000){
            $commission = 470 ;
        }elseif($amount > 500000 and $amount <= 850000){
            $commission = 500 ;
        }elseif($amount > 850000 and $amount <= 1000000){
            $commission = 750 ;
        }elseif($amount > 1000000 and $amount <= 1500000){
            $commission = 1000 ;
        }/*elseif($amount > 1500000 and $amount <= 2000000){
            $commission = 4785 ;
        }*/

        return $commission;
    }

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
        if($this->getSimProfile()=='AGNT'){
            $this->dCommCalc = $this->calcCommission() ;
            $this->posCommCalc = 0;
        }elseif ($this->getSimProfile()=='DISTRO'){
            $this->dCommCalc = $this->calcCommission() * (20/100);
            $this->posCommCalc = $this->calcCommission() * (80/100);
        }else{
            $this->dCommCalc = 0;
            $this->posCommCalc = 0;
        }

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDComm(): ?float
    {
        return $this->dComm;
    }

    public function setDealerCommission(?float $dComm): self
    {
        $this->dComm = $dComm;

        return $this;
    }

    public function getPosComm(): ?float
    {
        return $this->posComm;
    }

    public function setPosComm(?float $posComm): self
    {
        $this->posComm = $posComm;

        return $this;
    }

    public function getTransactionAt(): ?\DateTimeInterface
    {
        return $this->transactionAt;
    }

    public function setTransactionAt(?\DateTimeInterface $transactionAt): self
    {
        $this->transactionAt = $transactionAt;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function setRefId(?string $refId): self
    {
        $this->refId = $refId;

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

    public function calcCommission()
    {
        $withdrawal = ['AGNT', 'AWITH', 'WITH', 'APPAGNT'];
        $type = $this->getType()->getTitle();
        if(in_array($type,$withdrawal)){
            $commission = $this->getAGNTCommission($this->getAmount());
        }elseif ($type ='CSIN'){
            $commission = $this->getCSINCommission($this->getAmount());
        }
        return $commission;
    }

    public function getSimProfile()
    {
        if($this->getMsisdn()){
            return $this->getMsisdn()->getProfile()->getTitle();
        }else{
            return null;
        }

    }

    public function getDCommCalc(): ?float
    {
        return $this->dCommCalc;
    }

    public function setDCommCalc(?float $dCommCalc): self
    {
        $this->dCommCalc = $dCommCalc;

        return $this;
    }

    public function getPosCommCalc(): ?float
    {
        return $this->posCommCalc;
    }

    public function setPosCommCalc(float $posCommCalc): self
    {
        $this->posCommCalc = $posCommCalc;

        return $this;
    }



}
