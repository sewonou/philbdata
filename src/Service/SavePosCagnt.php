<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SavePosCagnt
{
    private $save;
    public function __construct(Security $security, Save $save)
    {
        $this->save = $save;
    }

    public function getLine(Reader $reader)
    {
        $msisdnTitle = $reader->getTitle('NUMERO FLOOZ');
        $profileTitle = $reader->getTitle('PROFIL');
        $posTitle = $reader->getTitle('NOM DU POINT');
        $title = compact('msisdnTitle', 'profileTitle', 'posTitle');

        return $title;
    }

    public function getValue($value, $title)
    {
        $msisdn = $value[$title['msisdnTitle']];
        $profile = $value[$title['profileTitle']];
        $posName = $value[$title['posTitle']];

        return compact('msisdn', 'profile', 'posName');
    }

    public function save($value)
    {
        $this->save->addPosCagnt($value);
    }

}