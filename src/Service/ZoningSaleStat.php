<?php


namespace App\Service;


use App\Entity\Search;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;

class ZoningSaleStat
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

    public function getSaleInDistrict(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInDistrict($startDate, $endDate, $id);
    }

    public function getSaleInDistrictWithLimit($id, $limit)
    {
        return $this->saleRepository->findSaleInDistrictWithLimit($id, $limit);
    }

    public function getSaleInDistrictByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInDistrictByDay($startDate, $endDate, $id);
    }

    public function getSaleInPrefecture(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInPrefecture($startDate, $endDate, $id);
    }

    public function getSaleInPrefectureByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInPrefectureByDay($startDate, $endDate, $id);
    }

    public function getSaleInPrefectureWithLimit(int $id, int $limit)
    {

        return $this->saleRepository->findSaleInPrefectureWithLimit($id, $limit);
    }

    public function getSaleInTown(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInTown($startDate, $endDate, $id);
    }

    public function getSaleInTownByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInTownByDay($startDate, $endDate, $id);
    }

    public function getSaleInTownWithLimit(int $id, int $limit)
    {

        return $this->saleRepository->findSaleInTownWithLimit($id, $limit);
    }

    public function getSaleInRegion(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInRegion($startDate, $endDate, $id);
    }

    public function getSaleInRegionByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInRegionByDay($startDate, $endDate, $id);
    }

    public function getSaleInRegionWithLimit(int $id, int $limit)
    {
        return $this->saleRepository->findSaleInRegionWithLimit($id, $limit);
    }

    public function getSaleInZone(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInZone($startDate, $endDate, $id);
    }

    public function getSaleInZoneByDay(Search $search, int $id)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->saleRepository->findSaleInZoneByDay($startDate, $endDate, $id);
    }

    public function getSaleInZoneWithLimit(int $id, int $limit)
    {
        return $this->saleRepository->findSaleInZoneWithLimit($id, $limit);
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