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
        $townTitle = $reader->getTitle('COMMUNE');
        $prefectureTitle = $reader->getTitle('PREFECTURE');
        $regionTitle = $reader->getTitle('REGION');
        $traderTitle = $reader->getTitle('COMMERCIAL');
        $title = compact('msisdnTitle', 'profileTitle', 'posTitle', 'activityTitle', 'localizationTitle', 'latitudeTitle', 'longitudeTitle', 'contactTitle', 'traderTitle',  'districtTitle', 'prefectureTitle', 'townTitle', 'regionTitle');

        return $title ;
    }

    public function getValue($value, $title)
    {
        $msisdn = $value[$title['msisdnTitle']];
        $profile = $value[$title['profileTitle']];
        $posName = strval($value[$title['posTitle']]);
        $activity = strval($value[$title['activityTitle']]);
        $localization = strval($value[$title['localizationTitle']]);
        $latitude = strval($value[$title['latitudeTitle']]);
        $longitude = strval($value[$title['longitudeTitle']]);
        $contact = mb_strtoupper($value[$title['contactTitle']]);
        $district = $value[$title['districtTitle']];
        $town = $value[$title['townTitle']];
        $prefecture = $value[$title['prefectureTitle']];
        $region = $value[$title['regionTitle']];
        $trader = $value[$title['traderTitle']];

        return compact('msisdn', 'posName', 'profile', 'activity', 'localization', 'latitude', 'longitude', 'contact', 'trader', 'district', 'prefecture', 'town', 'region');
    }

    public function save($value)
    {
        $this->save->addControl($value);
    }


}