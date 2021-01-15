<?php

namespace App\Controller;

use App\Entity\ConfigFile;
use App\Repository\ProfileRepository;
use App\Repository\SimCardRepository;
use App\Repository\TraderRepository;
use App\Service\Reader;
use App\Service\SaveBalance;
use App\Service\SaveMonthlyReport;
use App\Service\SaveOneTransaction;
use App\Service\SavePosCagnt;
use App\Service\SaveTrader;
use App\Service\SaveTransaction;
use App\Service\SaveUniverse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class UploaderController extends AbstractController
{
    private $manager;
    private $simCardRepository;
    private $traderRepository;
    private $reader;
    private $helper;
    private $pRepo;


    public function __construct(EntityManagerInterface $manager, SimCardRepository $simCardRepository, TraderRepository $traderRepository, UploaderHelper $helper, Reader $reader, ProfileRepository $pRepo)
    {
        $this->manager = $manager;
        $this->simCardRepository = $simCardRepository;
        $this->traderRepository = $traderRepository;
        $this->reader = $reader;
        $this->helper = $helper;
        $this->pRepo = $pRepo ;
    }

    /**
     * @Route("/admin/uploads/universe/{id}", name="uploader_universe")
     * @param ConfigFile $file
     * @param SaveUniverse $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function universeUpload(ConfigFile $file,  SaveUniverse $save):Response
    {

        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1));
        $traders = $this->traderRepository->findAll();
        if(empty($traders)){
            $this->addFlash('danger', "Aucun Commerciaux dans la base de donné; Veuillez charger le fichier des commerciaux");
            return $this->redirectToRoute('transaction_file', [

            ]);
        }
        $values = $this->reader->getValues();

        $title = $save->getLine($this->reader);
        $count = 0 ;

        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true)
            ->setIsLoad(true)
        ;
        $this->manager->persist($file);
        $this->manager->flush();
        $simCards = $this->simCardRepository->findAll();
        if($simCards){
            $date = $this->simCardRepository->findPointofsaleLastUpdate();

            $profile = $this->pRepo->findOneBy(['title'=>'AGNT']);
            $this->simCardRepository->setInactive($profile, $date);

            $profile = $this->pRepo->findOneBy(['title'=>'DISTRO']);
            $this->simCardRepository->setInactive($profile, $date);
        }
        return $this->redirectToRoute('transaction_file', [

        ]);
    }

    /**
     * @Route("/admin/uploads/trader/{id}", name="uploader_trader")
     * @param ConfigFile $file
     * @param SaveTrader $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function traderUpload(ConfigFile $file,  SaveTrader $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1));

        $values = $this->reader->getValues();

        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true)
            ->setIsLoad(true)
        ;
        $this->manager->persist($file);
        $this->manager->flush();
        $simCards = $this->simCardRepository->findAll();

        if($simCards){
            $date = $this->simCardRepository->findPointofsaleLastUpdate();

            $profile = $this->pRepo->findOneBy(['title'=>'CAGNT']);
            $this->simCardRepository->setInactive($profile, $date);

        }
        return $this->redirectToRoute('transaction_file', [

        ]);
    }
    /**
     * @Route("/admin/uploads/poscagnt/{id}", name="uploader_poscagnt")
     * @param ConfigFile $file
     * @param SavePosCagnt $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function uploadPosCagnt(ConfigFile $file, SavePosCagnt $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1));

        $values = $this->reader->getValues();

        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true)
            ->setIsLoad(true)
        ;
        $this->manager->persist($file);
        $this->manager->flush();
        $simCards = $this->simCardRepository->findAll();

        if($simCards){
            $date = $this->simCardRepository->findPointofsaleLastUpdate();

            $profile = $this->pRepo->findOneBy(['title'=>'POSCAGNT']);
            $this->simCardRepository->setInactive($profile, $date);

        }
        return $this->redirectToRoute('transaction_file', [

        ]);
    }

    /**
     * @Route("/admin/uploads/transaction/{id}", name="uploader_transaction")
     * @param ConfigFile $file
     * @param SaveTransaction $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function uploadTransaction(ConfigFile $file, SaveTransaction $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1))
            ->setSheetName('POS_TRANSACTIONS')
        ;

        $values = $this->reader->getValues();
        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true);
        $this->manager->persist($file);
        $this->manager->flush();
        return $this->redirectToRoute('transaction_file', [

        ]);
    }


    /**
     * @Route("/admin/uploads/balance/{id}", name="uploader_balance")
     * @param ConfigFile $file
     * @param SaveBalance $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function uploadTrade(ConfigFile $file, SaveBalance $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1))
            ->setSheetName('POS_SOLDE')
        ;
        $values = $this->reader->getValues();
        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsLoad(true);
        $this->manager->persist($file);
        $this->manager->flush();
        return $this->redirectToRoute('transaction_file', [

        ]);
    }

    /**
     * @Route("/admin/uploads/oneTransaction/{id}", name="uploader_one_transaction")
     * @param ConfigFile $file
     * @param SaveOneTransaction $save
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function uploadOneTransaction(ConfigFile $file, SaveOneTransaction $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1))
        ;

        $values = $this->reader->getValues();
        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true);
        $this->manager->persist($file);
        $this->manager->flush();
        return $this->redirectToRoute('transaction_file', [

        ]);
    }

    /**
     * @Route("/admin/uploads/monthlyReport/{id}", name="uploader_monthly_report")
     * @param ConfigFile $file
     * @param SaveMonthlyReport $save
     * @return Response
     */
    public function uploadMonthlyReport(ConfigFile $file, SaveMonthlyReport $save):Response
    {
        $input = $this->helper->asset($file, 'file');
        $this->reader->setInputFile(substr($input, 1))
        ;
        $values = $this->reader->getValues();
        $title = $save->getLine($this->reader);
        $count = 0 ;
        foreach ($values as $key=>$value){
            $save->save($save->getValue($value, $title));
            $count ++;
        }
        $this->addFlash('success', "$count enregistrement ont été ajouté dans la base de donnée" );
        $file->setIsUpload(true);
        $this->manager->persist($file);
        $this->manager->flush();
        return $this->redirectToRoute('transaction_file', [

        ]);
    }
}
