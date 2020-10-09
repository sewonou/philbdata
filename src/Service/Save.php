<?php


namespace App\Service;


use App\Entity\Balance;
use App\Entity\Control;
use App\Entity\District;
use App\Entity\Pointofsale;
use App\Entity\Prefecture;
use App\Entity\Profile;
use App\Entity\Region;
use App\Entity\Sale;
use App\Entity\SimCard;
use App\Entity\Town;
use App\Entity\Township;
use App\Entity\Trade;
use App\Entity\Trader;
use App\Entity\Type;
use App\Entity\Zone;
use App\Repository\ControlRepository;
use App\Repository\DistrictRepository;
use App\Repository\MasterSimRepository;
use App\Repository\PointofsaleRepository;
use App\Repository\PrefectureRepository;
use App\Repository\ProfileRepository;
use App\Repository\RegionRepository;
use App\Repository\SimCardRepository;
use App\Repository\TownRepository;
use App\Repository\TownshipRepository;
use App\Repository\TraderRepository;
use App\Repository\TypeRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class Save
{
    private $manager;
    private $zoneRepository;
    private $regionRepository;
    private $townRepository;
    private $prefectureRepository;
    private $townshipRepository;
    private $districtRepository;
    private $profileRepository;
    private $traderRepository;
    private $pointofsaleRepository;
    private $typeRepository;
    private $simCardRepository;
    private $controlRepository;
    private $user;
    private  $master;

    public function __construct(EntityManagerInterface $manager, ZoneRepository $zoneRepository, RegionRepository $regionRepository, TownRepository $townRepository, PrefectureRepository $prefectureRepository, TownshipRepository $townshipRepository, DistrictRepository $districtRepository, ProfileRepository $profileRepository, TraderRepository $traderRepository, PointofsaleRepository $pointofsaleRepository, TypeRepository $typeRepository, SimCardRepository $simCardRepository, ControlRepository $controlRepository, Security $security, MasterSimRepository $masterRepository)
    {
        $this->manager = $manager;
        $this->zoneRepository = $zoneRepository;
        $this->regionRepository = $regionRepository;
        $this->townRepository = $townRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->townshipRepository = $townshipRepository;
        $this->districtRepository = $districtRepository;
        $this->profileRepository = $profileRepository;
        $this->traderRepository = $traderRepository;
        $this->pointofsaleRepository = $pointofsaleRepository ;
        $this->typeRepository = $typeRepository;
        $this->simCardRepository = $simCardRepository;
        $this->controlRepository = $controlRepository;
        $this->master = $masterRepository->findOneBy(['name'=>'PHIL']);
        $this->user = $security->getUser();
    }

    /**
     * @param $value
     * @return Zone|null
     */
    public function addZone($value)
    {
        $zone = null ;
        if(isset($value['zone'])){
            $zone = $this->zoneRepository->findOneBy(['name' => $value['zone']]);
            if(empty($zone)) {
                $zone = new Zone();
            }
            $zone->setName($value['zone'])
            ;
            $this->manager->persist($zone);
            $this->manager->flush();
            $zone = $this->zoneRepository->findOneBy(['name'=>$value['zone']]);
        }

        return $zone;
    }

    /***
     * @param $value
     * @return Region|null
     */
    public function addRegion($value){

        $region = null ;
        if( isset($value['region'])){
            $zone = $this->addZone($value);
            $region = $this->regionRepository->findOneBy(['name' => $value['region'], 'zone'=>$zone]);
            if(empty($region)) {
                $region = new Region();
            }
            $region
                ->setName($value['region'])
                ->setZone($zone)
            ;
            $this->manager->persist($region);
            $this->manager->flush();
            $region = $this->regionRepository->findOneBy(['name'=>$value['region'], 'zone'=>$zone]);

        }

        return $region;
    }

    /**
     * @param $value
     * @return Town|null
     */
    public function addTown($value){

        $town = null ;
        if(isset($value['town'])){
            $region = $this->addRegion($value);
            $town = $this->townRepository->findOneBy(['name' => $value['town'],'region'=>$region]);
            if(empty($town)) {
                $town = new Town();
            }
            $town
                ->setName($value['town'])
                ->setRegion($region)
            ;
            $this->manager->persist($town);
            $this->manager->flush();
            $town = $this->townRepository->findOneBy(['name'=>$value['town'],'region'=>$region]);
        }

        return $town;
    }

    /**
     * @param $value
     * @return Prefecture|null
     */
    public function addPrefecture($value)
    {
        $prefecture = null ;
        if(isset($value['prefecture'])){
            $town = $this->addTown($value);
            $prefecture = $this->prefectureRepository->findOneBy(['name' => $value['prefecture'], 'town'=>$town]);
            if(empty($prefecture)) {
                $prefecture = new Prefecture();
            }
            $prefecture
                ->setName($value['prefecture'])
                ->setTown($town)
            ;
            $this->manager->persist($prefecture);
            $this->manager->flush();
            $prefecture = $this->prefectureRepository->findOneBy(['name'=>$value['prefecture'], 'town'=>$town]);
        }


        return $prefecture;
    }

    /**
     * @param $value
     * @return Township|null
     */
    public function addTownship($value){
        $township = null ;
        if (isset($value['township'])){
            $prefecture = $this->addPrefecture($value);
            $township = $this->townshipRepository->findOneBy(['name' => $value['township'], 'prefecture'=>$prefecture]);

            if(empty($township)) {
                $township = new Township();
            }
            $township
                ->setName($value['township'])
                ->setPrefecture($prefecture)
            ;
            $this->manager->persist($township);
            $this->manager->flush();
            $township = $this->townshipRepository->findOneBy(['name'=>$value['township'], 'prefecture'=>$prefecture]);
        }

        return $township;
    }

    /**
     * @param $value
     * @return District|null
     */
    public function addDistrict($value)
    {
        $district = null ;

        if(isset($value['district'])){

            $township = $this->addTownship($value);
            $district = $this->districtRepository->findOneBy(['name'=>$value['district'], 'township'=> $township]);

            if(empty($district)){
                $district = new District();
            }
            $district
                ->setName($value['district'])
                ->setTownship($township)
            ;
            $this->manager->persist($district);
            $this->manager->flush();
            $district = $this->districtRepository->findOneBy(['name'=>$value['district'], 'township'=> $township]);
        }


        return $district;
    }

    /**
     * @param $value
     * @return Profile|null
     */
    public function addProfile($value){
        $profile = null ;
        if (isset($value['profile'])){
            $profile = $this->profileRepository->findOneBy(['title' => $value['profile']]);
            if(empty($profile)) {
                $profile = new Profile();
            }
            $profile->setTitle($value['profile'])
            ;
            $this->manager->persist($profile);
            $this->manager->flush();
            $profile = $this->profileRepository->findOneBy(['title'=>$value['profile']]);

        }
        return $profile;
    }

    /**
     * @param $value
     * @return Profile|null
     */
    public function addOtherProfile($value){
        $profile = null ;
        if (isset($value['profile'])){
            $profile = $this->profileRepository->findOneBy(['title' => $value['profile']]);
            if(empty($profile)) {
                $profile = new Profile();
            }
            $profile->setTitle($value['profile'])
            ;
            $this->manager->persist($profile);
            $this->manager->flush();
            $profile = $this->profileRepository->findOneBy(['title'=>$value['profile']]);

        }
        return $profile;
    }

    /**
     * @param $value
     * @return SimCard|null
     */
    public function addSimCard($value){
        $msisdn = null ;
        if (isset($value['msisdn'])){
            $msisdn = $this->simCardRepository->findOneBy(['msisdn' => $value['msisdn']]);
            if(empty($msisdn)) {
                $msisdn = new SimCard();
            }
            $profile= $this->addProfile($value);
            $msisdn->setMsisdn($value['msisdn'])
                ->setProfile($profile)
                ->setIsActive(false)
                ->setMaster($this->master)
            ;
            $this->manager->persist($msisdn);
            $this->manager->flush();
            $msisdn = $this->simCardRepository->findOneBy(['msisdn' => $value['msisdn']]);
        }
        return $msisdn;
    }



    /**
     * @param $value
     * @return Trader|null
     */
    public function addTrader($value)
    {
        $trader = null ;
        if (isset($value['msisdn'])){
            $region = $this->addRegion($value);
            $town = $this->addTown($value);
            $msisdn = $this->addSimCard($value);
            $trader = $this->traderRepository->findOneBy(['msisdn' => $msisdn]);

            if(empty($trader)) {
                $trader = new Trader();
            }
            $msisdn->setIsActive(true);
            $trader
                ->setMsisdn($msisdn)
                ->setRegion($region)
                ->setFullName($value['name'])
                ->setName($value['posName'])
                ->setIsActive(true)
                ->setIsTrader((isset($town)? true : false))
            ;
            $this->manager->persist($msisdn);
            $this->manager->persist($trader);
            $this->manager->flush();

            $trader = $this->traderRepository->findOneBy(['msisdn'=>$msisdn]);
        }

        return $trader;
    }

    public function addPosCagnt($value)
    {
        $pointofsale = null ;
        if(isset($value['msisdn'])){
            $msisdn = $this->addSimCard($value);
            $pointofsale = $this->pointofsaleRepository->findOneBy(['msisdn'=>$msisdn]);
            if(empty($pointofsale) && isset($value['msisdn'])){
                $pointofsale = new Pointofsale();
            }
            $pointofsale
                ->setMsisdn($msisdn)
                ->setName($value['posName'])
                ->setActivity('Numéro POSCAGNT')
                ->setIsActive(true)
            ;
            $msisdn->setIsActive(true);
            $this->manager->persist($msisdn);
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $pointofsale = $this->pointofsaleRepository->findOneBy(['msisdn'=>$msisdn]);
        }

        return $pointofsale;
    }

    /**
     * @param $value
     * @return Pointofsale|null
     */
    public function addPointofsale($value)
    {
        $pointofsale = null ;

        if (isset($value['msisdn'])){
            $district = $this->addDistrict($value);
            $msisdn = $this->addSimCard($value);
            $pointofsale = $this->pointofsaleRepository->findOneBy(['msisdn'=>$msisdn]);
            if(empty($pointofsale) && isset($value['msisdn'])){
                $pointofsale = new Pointofsale();
            }

            $pointofsale
                ->setMsisdn($msisdn)
                ->setName($value['posName'])
                ->setDistrict($district)
                ->setIsActive(false)
            ;
            if(isset($district)){
                $pointofsale
                    ->setIsActive(true)
                    ->setActivity($value['activity'])
                    ->setPosition($value['localization'])
                    ->setLatitude((float)$value['latitude'])
                    ->setLongitude((float)$value['longitude'])
                    ->setContact($value['contact'])
                ;
                $msisdn->setIsActive(true);
            }

            $this->manager->persist($msisdn);
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $pointofsale = $this->pointofsaleRepository->findOneBy(['msisdn'=>$msisdn]);
        }

        return $pointofsale;
    }


    /**
     * @param $value
     * @return Control|null
     * @throws \Exception
     */
    public function addControl($value)
    {

        $pointofsale = $this->addPointofsale($value);
        $trader = $this->traderRepository->findOneBy(['fullName'=>$value['trader']]);

        $control = $this->controlRepository->findOneBy(['trader'=>$trader, 'pointofsale'=> $pointofsale]);
        if(empty($control) && isset($value['msisdn'])){
            $control = new Control();
            $oldControls = $this->controlRepository->findBy(['pointofsale'=> $control->getPointofsale(), 'isActive'=>true]);
            if($oldControls){
                foreach ($oldControls as $oldControl){
                    $oldControl->setIsActive(false)
                        ->setEndAt( new  \DateTime());
                    $this->manager->persist($oldControl);
                }
            }
            $control
                ->setStartAt(new  \DateTime())
                ->setIsActive(true)
                ->setTrader($trader)
                ->setPointofsale($pointofsale)
            ;
            $this->manager->persist($control);
            $this->manager->flush();
            $control = $this->controlRepository->findOneBy(['trader'=>$trader, 'pointofsale'=> $pointofsale]);
        }elseif(isset($control) && isset($value['msisdn']) ){
            $oldControls = $this->controlRepository->findBy(['pointofsale'=> $control->getPointofsale(), 'isActive'=>true]);
            if($oldControls){
                foreach ($oldControls as $oldControl){
                    $oldControl->setIsActive(false)
                        ->setEndAt( new  \DateTime());
                    $this->manager->persist($oldControl);
                }
            }
            $control->setIsActive(true)
                ->setEndAt(null);
            $this->manager->persist($control);
            $this->manager->flush();
            $control = $this->controlRepository->findOneBy(['trader'=>$trader, 'pointofsale'=> $pointofsale]);
        }

        return $control;
    }

    /**
     * @param $value
     * @return Type|null
     */
    public function addType($value)
    {

        $type = $this->typeRepository->findOneBy(['title'=>$value['type']]);
        if(isset($value['type']) && empty($type)){
            $type =new Type();
            $type->setTitle($value['type']);
            $this->manager->persist($type);
            $this->manager->flush();
            $type =  $this->typeRepository->findOneBy(['title'=>$value['type']]);
        }
        return $type ;
    }

    /**
     * @param $value
     *
     */
    public function addTransaction($value)
    {

        $type = $this->addType($value);
        $toSim = $this->simCardRepository->findOneBy(['msisdn'=>$value['toSim']]);
        $fromSim = $this->simCardRepository->findOneBy(['msisdn'=>$value['fromSim']]);
        $msisdn = (isset($toSim)) ? $toSim : $fromSim;
        if($value['id'] && $value['type'] != 'GIVE'){
            $sale = new Sale();
            $sale->setMsisdn($msisdn)
                ->setType($type)
                ->setRefId($value['id'])
                ->setAmount($value['amount'])
                ->setDealerCommission($value['dealerCommission'])
                ->setPosComm($value['posCommission'])
                ->setTransactionAt($value['transactionAt'])
            ;
            $this->manager->persist($sale);
        }elseif ($value['id'] && $value['type'] == 'GIVE'){
            $trade = new Trade();
            $trade->setToMsisdn($toSim)
                ->setFromMsisdn($fromSim)
                ->setType($type)
                ->setRefId($value['id'])
                ->setAmount($value['amount'])
                ->setTransactionAt($value['transactionAt'])
            ;
            $this->manager->persist($trade);
        }
    }


    public function addBalance($value)
    {
        if(isset($value)){
            $simCard = $this->simCardRepository->findOneBy(['msisdn' => $value['msisdn']]);
            if(empty($simCard)){
                if(($value['profile'] == 'CAGNT') or ($value['profile'] == 'DLER')){
                    $simCard = $this->addSimCard($value);
                }else{
                    $simCard = $this->addSimCard($value);
                }
            }
            $balance = new Balance();
            $balance
                ->setMsisdn($simCard)
                ->setExecutionAt($value['executeAt'])
                ->setAmount((float) $value['posBalance'])
            ;
            $this->manager->persist($balance);
        }
    }
}