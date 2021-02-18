<?php


namespace App\Service;


use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Reader
{
    private $sheetName;
    private $inputFile;
    private $reader ;
    public function __construct()
    {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '1524M');
        $this->reader = new  Xlsx();
    }

    /**
     * @return mixed
     */
    public function getSheetName()
    {
        return $this->sheetName;
    }


    public function setSheetName($sheetName)
    {
        $this->sheetName = $sheetName;
        return $this ;
    }



    /**
     * @return mixed
     */
    public function getInputFile()
    {
        return $this->inputFile;
    }

    public function setInputFile($inputFile)
    {
        $this->inputFile = $inputFile;
        return $this;
    }

    public function getActiveSheet()
    {
        $spreadSheet = $this->reader->load($this->inputFile);
        return $spreadSheet->getAllSheets();
    }

    /**
     * Retourne les données du fichier spécifier
     * @return array
     */
    public function getData()
    {
        if(isset($this->sheetName)){
            $this->reader->setLoadSheetsOnly($this->sheetName);
        }
        $spreadsheet = $this->reader->load($this->inputFile);
        $worksheet = $spreadsheet->getActiveSheet();
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        return $worksheet->toArray(null, true, true, true);
    }

    /**
     * Retourne les titre du fichier spécifier
     * @return mixed
     */
    public function getTitles()
    {
        $titles = [];
        $sheetData = $this->getData();
        foreach ($sheetData as $key=>$value){
            if($key == 1 ){
                foreach ($value as $k => $val){
                    if(!empty($val)){
                        $titles[$k] = $val ;
                    }
                }

            }
        }
        return $titles ;
    }

    public function getRows()
    {
        return $this->reader->load($this->getInputFile())->getActiveSheet()->getShowSummaryRight();
    }

    /**
     * Retourne et retoure un titre donnée sur le fichier excel
     * @param $title
     * @return false|int|string
     */
    public function getTitle($title)
    {
        $titles = $this->getTitles();
        return array_search($title, $titles);
    }

    /**
     * Retourne uniquement les valeurs suivant le titre du fichier excel renseigné
     * @return mixed
     */
    public function getValues()
    {
        $data = [];
        $sheetData = $this->getData();
        foreach ($sheetData as $key=>$value){
            if($key != 1 ){
                $data[$key] = $value ;
            }
        }

        return $data ;
    }

    public function getTransactionValues($type)
    {
        $data = [];
        $sheetData = $this->getData();
        foreach ($sheetData as $key=>$value){
            if($key != 1){

                if($value['B']==$type){
                    $data[$key] = $value ;
                }

            }
        }

        return $data ;
    }

    public function getOtherValues()
    {
        $data = [];
        $sheetData = $this->getData();
        foreach ($sheetData as $key=>$value){
            if($key != 1){

                if($value['B']!='AGNT' && $value['B']!='CSIN' && $value['B']!='GIVE'){
                    $data[$key] = $value ;
                }

            }
        }

        return $data ;
    }
}