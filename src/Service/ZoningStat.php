<?php


namespace App\Service;


use App\Entity\Search;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;

class ZoningStat
{
    private  $pointofsaleRepository;
    private  $saleRepository;

    public function __construct(PointofsaleRepository $pointofsaleRepository, SaleRepository $saleRepository)
    {
        $this->pointofsaleRepository = $pointofsaleRepository;
        $this->saleRepository = $saleRepository;
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

    public function getPointofsaleInDistrict(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInDistrict($id, $profile, true));
    }

    public function getPointofsaleInTown(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInTown($id, $profile,  true));
    }

    public function getPointofsaleInPrefecture(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInPrefecture($id, $profile, true));
    }

    public function getPointofsaleInRegion(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInRegion($id, $profile, true));
    }

    public function getPointofsaleInZone(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInZone($id, $profile, true));
    }

    public function getPointofsaleWithoutDistrict(string $profile)
    {
        return count($this->pointofsaleRepository->findPointofsaleWithoutDistrict($profile));
    }

    //
    //


    public function getSaleInPointofsale(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInPointofsale($startDate, $endDate, $id);
    }

    public function getSaleInPointofsaleByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInPointofsaleByDay($startDate, $endDate, $id);
    }

    public function getSaleInPointofsaleWithLimit(int $id, int $limit)
    {
        return $this->saleRepository->findSaleInPointofsaleWithLimit($id, $limit);
    }

    public function getLastWeekCommission($results)
    {
        $commissions = [];
        foreach ($results as  $value ){
            $commissions[] = round($value['dComm']/10000, 2);
        }
        $inverse = array_reverse($commissions);
        $inverse = '['. implode(',', $inverse) .']';
        return $inverse;
    }

}