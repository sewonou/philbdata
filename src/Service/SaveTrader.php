<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SaveTrader
{

    private $save;
    public function __construct(Save $save)
    {

        $this->save = $save;
    }

    public function getLine(Reader $reader)
    {
        $msisdnTitle = $reader->getTitle('NUMERO FLOOZ');
        $nameTitle = $reader->getTitle('NOM');
        $posTitle = $reader->getTitle('NOM DU POINT');
        $profileTitle = $reader->getTitle('PROFIL');
        $regionTitle = $reader->getTitle('REGION');
        $villeTitle = $reader->getTitle('Ville');
        $title = compact('msisdnTitle', 'nameTitle', 'posTitle', 'profileTitle', 'regionTitle', 'villeTitle');

        return $title;
    }

    public function getValue($value, $title)
    {
        $msisdn = $value[$title['msisdnTitle']];
        $name = $value[$title['nameTitle']];
        $posName = $value[$title['posTitle']];
        $profile = $value[$title['profileTitle']];
        $region = $value[$title['regionTitle']];
        $town = $value[$title['villeTitle']];

        return compact('msisdn', 'name', 'posName', 'profile', 'region', 'town');
    }

    public function save($value)
    {
        $this->save->addTrader($value);
    }

}