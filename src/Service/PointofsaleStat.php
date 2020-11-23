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

    private function getStartDate(Search $search)
    {
        $startDate = new \DateTime('-1 day');

        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        return$startDate;
    }

    private function getEndDate(Search $search)
    {
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $endDate;
    }

    public function getPointofsalesPeriodInput(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        //dump($startDate, $endDate);
        return $this->pointofsaleRepository->findPointofsalesPeriodInput(true, $startDate, $endDate);
    }

    public function getAllPointofsalesPeriodInput(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        //dump($startDate, $endDate);
        return $this->pointofsaleRepository->findAllPointofsalesPeriodInput( $startDate, $endDate);
    }

    public function getPointofsaleComm(?Search $search, ?int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        //dump($startDate, $endDate);
        return $this->pointofsaleRepository->findPointofsaleComm(true, $startDate, $endDate, $id);
    }


    public function getPointofsaleGoal(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        $length = $startDate->diff($endDate);
        $length = $length->format('%d');
        //dump($length);
        $length = $length+1;
        //dump($length);
        $dailyGoal = 500;
        //dump($length);
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
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleByDay($startDate, $endDate);
    }

    public function getGiveComByDay(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findGiveComByDay($startDate, $endDate);
    }

    public function getGiveComByPeriod(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findGiveComByPeriod($startDate, $endDate);
    }

    public function getSaleByRegion(?Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleByRegion($startDate, $endDate);
    }

    public function getGiveReceivedByPos(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveReceivedByPos($startDate, $endDate, $id);
    }

    public function getGiveSendByPos(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveSendByPos($startDate, $endDate, $id);
    }

    public function getIllegalSale($date)
    {
        return $this->saleRepository->findSaleIllegal($date);
    }


    public function getPointofsales(array $values)
    {
        $pointofsales = [];
        foreach ($values as $key=>$value){
           $pointofsales[] = $value['pointofsale'] ;
        }
        return $pointofsales;
    }

    public function getInactivePointofsales(array $values)
    {
        $activePointofsales = $this->getPointofsales($values);

        $pointofsales = $this->pointofsaleRepository->findPointofsaleWithoutProfile(true, 'POSCAGNT');
        $inactivePointofsales = [];
        foreach ($pointofsales as $pointofsale){
            if(!(in_array($pointofsale, $activePointofsales))  ){
                $inactivePointofsales[] = $pointofsale;
            }
        }
        //dump($inactivePointofsales);
        return $inactivePointofsales;
    }

    public function getCommByProfile(Search $search,string $profile)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleByProfile($startDate, $endDate, $profile);
    }

    public function getCommissionDealer(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleDealer($startDate, $endDate);
    }

    public function getCommissionPOS(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSalePOS($startDate, $endDate);
    }

    public function getSaleInRegionForPointofsaleWithoutDistrict(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleWithoutDistrict($startDate, $endDate);
    }
}