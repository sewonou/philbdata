<?php


namespace App\Service;


use App\Entity\Search;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;
use App\Repository\TradeRepository;

class PointofsaleStat
{
    private $pointofsaleRepository;
    private $saleRepository;
    private $tradeRepository;

    public function __construct(PointofsaleRepository $pointofsaleRepository, SaleRepository $saleRepository, TradeRepository $tradeRepository)
    {
        $this->pointofsaleRepository = $pointofsaleRepository;
        $this->saleRepository = $saleRepository;
        $this->tradeRepository = $tradeRepository;
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
        //dump($startDate, $endDate);
        return $this->pointofsaleRepository->findPointofsalesPeriodInput(true, $startDate, $endDate);
    }

    public function getPointofsaleComm(?Search $search, ?int $id)
    {
        $startDate = new \DateTime('-1 day');
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        //dump($startDate, $endDate);
        return $this->pointofsaleRepository->findPointofsaleComm(true, $startDate, $endDate, $id);
    }


    public function getPointofsaleGoal(?Search $search)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        $length = $startDate->diff($endDate);
        $length = $length->format('%d');
        $length = ($length == 0)? ($length + 1) : $length;
        //dump($length);
        $dailyGoal = 500;
        //dump($length);
        //die();
        return $length * $dailyGoal ;
    }

    public function getLastWeekCommission($limit)
    {
        $commissions = [];
        $results = $this->saleRepository->findSaleByDateLimit($limit);
        foreach ($results as  $value ){
            $commissions[] = round($value['dComm']/$value['total'], 1);
        }
        $inverse = array_reverse($commissions);
        $inverse = '['. implode(',', $inverse) .']';
        return $inverse;
    }

    public function getSaleByDay(?Search $search)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByDay($startDate, $endDate);
    }

    public function getGiveComByDay(?Search $search)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByDay($startDate, $endDate);
    }

    public function getSaleByRegion(?Search $search)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByRegion($startDate, $endDate);
    }

    public function getGiveReceivedByPos(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }

        return $this->tradeRepository->findGiveReceivedByPos($startDate, $endDate, $id);
    }

    public function getGiveSendByPos(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }

        return $this->tradeRepository->findGiveSendByPos($startDate, $endDate, $id);
    }
}