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

    public function getPointofsaleInDistrict(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInDistrict($id, $profile, true));
    }

    public function getPointofsaleInTownship(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInTownship($id, $profile, true));
    }

    public function getPointofsaleInPrefecture(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInPrefecture($id, $profile, true));
    }

    public function getPointofsaleInTown(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInTown($id, $profile,  true));
    }

    public function getPointofsaleInRegion(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInRegion($id, $profile, true));
    }

    public function getPointofsaleInZone(int $id, string $profile):int
    {
        return count($this->pointofsaleRepository->findPointofsaleInZone($id, $profile, true));
    }


    public function getSaleInDistrict(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInDistrict($startDate, $endDate, $id);
    }

    public function getSaleInDistrictWithLimit($id, $limit)
    {
        return $this->saleRepository->findSaleInDistrictWithLimit($id, $limit);
    }

    public function getSaleInDistrictByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInDistrictByDay($startDate, $endDate, $id);
    }


    public function getSaleInTownship(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInTownship($startDate, $endDate, $id);
    }

    public function getSaleInTownshipByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInTownshipByDay($startDate, $endDate, $id);
    }

    public function getSaleInTownshipWithLimit(int $id, int $limit)
    {

        return $this->saleRepository->findSaleInTownshipWithLimit($id, $limit);
    }

    public function getSaleInPrefecture(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInPrefecture($startDate, $endDate, $id);
    }

    public function getSaleInPrefectureByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInPrefectureByDay($startDate, $endDate, $id);
    }

    public function getSaleInPrefectureWithLimit(int $id, int $limit)
    {

        return $this->saleRepository->findSaleInPrefectureWithLimit($id, $limit);
    }

    public function getSaleInTown(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInTown($startDate, $endDate, $id);
    }

    public function getSaleInTownByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInTownByDay($startDate, $endDate, $id);
    }

    public function getSaleInTownWithLimit(int $id, int $limit)
    {

        return $this->saleRepository->findSaleInTownWithLimit($id, $limit);
    }

    public function getSaleInRegion(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInRegion($startDate, $endDate, $id);
    }

    public function getSaleInRegionByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInRegionByDay($startDate, $endDate, $id);
    }

    public function getSaleInRegionWithLimit(int $id, int $limit)
    {
        return $this->saleRepository->findSaleInRegionWithLimit($id, $limit);
    }

    public function getSaleInZone(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleInZone($startDate, $endDate, $id);
    }

    public function getSaleInZoneByDay(Search $search, int $id)
    {
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
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
            $commissions[] = round($value['dComm']/$value['total'], 1);
        }
        $inverse = array_reverse($commissions);
        $inverse = '['. implode(',', $inverse) .']';
        return $inverse;
    }
}