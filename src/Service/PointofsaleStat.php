<?php


namespace App\Service;


use App\Entity\Search;
use App\Repository\PointofsaleRepository;

class PointofsaleStat
{
    private $pointofsaleRepository;

    public function __construct(PointofsaleRepository $pointofsaleRepository)
    {
        $this->pointofsaleRepository = $pointofsaleRepository;
    }

    public function getPointofsalesPeriodInput(?Search $search)
    {
        $startDate = new \DateTime('-1 day');
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        dump($startDate, $endDate);
        return $this->pointofsaleRepository->findPointofsalesPeriodInput(true, $startDate, $endDate);
    }
}