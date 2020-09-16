<?php


namespace App\Service;


use App\Repository\SimCardRepository;

class SimCardStat
{
    private $simRepository;

    public function __construct(SimCardRepository $simRepository)
    {
        $this->simRepository  = $simRepository;
    }

    public function getActiveSimCard():int
    {
        return count($this->simRepository->findActiveSim());
    }

    public function getActivePointofsale():int
    {
        return count($this->simRepository->findActivePointofsale());
    }

    public function getActivePointofsaleByProfile($profile):int
    {
        return count($this->simRepository->findActivePointofsaleByProfile($profile));
    }

    public function getActiveTraderByProfile($profile):int
    {
        return count($this->simRepository->findActiveTraderByProfile($profile));
    }
}