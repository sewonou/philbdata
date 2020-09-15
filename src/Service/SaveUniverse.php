<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SaveUniverse
{

    private $save;
    public function __construct(Save $save)
    {

        $this->save = $save;
    }

    public function getLine(Reader $reader)
    {
        $msisdnTitle = $reader->getTitle('NUMERO FLOOZ');
        $profileTitle = $reader->getTitle('PROFIL');
        $posTitle = $reader->getTitle('NOM DU POINT');
        $activityTitle = $reader->getTitle('TYPE D\'ACTIVITE');
        $localizationTitle = $reader->getTitle('LOCALISATION');
        $latitudeTitle = $reader->getTitle('LATITUDE');
        $longitudeTitle = $reader->getTitle('LONGITUDE');
        $contactTitle = $reader->getTitle('AUTRE CONTACT');
        $districtTitle = $reader->getTitle('QUARTIER');
        $townshipTitle = $reader->getTitle('CANTON');
        $prefectureTitle = $reader->getTitle('PREFECTURE');
        $townTitle = $reader->getTitle('COMMUNE');
        $regionTitle = $reader->getTitle('REGION');
        $zoneTitle = $reader->getTitle('ZONE');
        $traderTitle = $reader->getTitle('COMMERCIAL');
        $title = compact('msisdnTitle', 'profileTitle', 'posTitle', 'activityTitle', 'localizationTitle', 'latitudeTitle', 'longitudeTitle', 'contactTitle', 'traderTitle',  'districtTitle', 'townshipTitle', 'prefectureTitle', 'townTitle', 'regionTitle', 'zoneTitle');

        return $title ;
    }

    public function getValue($value, $title)
    {
        $msisdn = $value[$title['msisdnTitle']];
        $profile = $value[$title['profileTitle']];
        $posName = $value[$title['posTitle']];
        $activity = $value[$title['activityTitle']];
        $localization = $value[$title['localizationTitle']];
        $latitude = $value[$title['latitudeTitle']];
        $longitude = $value[$title['longitudeTitle']];
        $contact = $value[$title['contactTitle']];
        $district = $value[$title['districtTitle']];
        $township = $value[$title['townshipTitle']];
        $prefecture = $value[$title['prefectureTitle']];
        $town = $value[$title['townTitle']];
        $region = $value[$title['regionTitle']];
        $zone = $value[$title['zoneTitle']];
        $trader = $value[$title['traderTitle']];

        return compact('msisdn', 'posName', 'profile', 'activity', 'localization', 'latitude', 'longitude', 'contact', 'trader', 'district', 'township', 'prefecture', 'town', 'region', 'zone');
    }

    public function save($value)
    {
        $this->save->addControl($value);
    }


}