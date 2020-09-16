<?php


namespace App\Service;


use App\Repository\PointofsaleRepository;

class ZoningStat
{
    private  $pointofsaleRepository;

    public function __construct(PointofsaleRepository $pointofsaleRepository)
    {
        $this->pointofsaleRepository = $pointofsaleRepository;
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


}