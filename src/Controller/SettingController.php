<?php

namespace App\Controller;

use App\Entity\MasterSim;
use App\Form\MasterSimType;
use App\Repository\ConfigFileRepository;
use App\Repository\ConfigRepository;
use App\Repository\FileCategoryRepository;
use App\Repository\MasterSimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/settings", name="setting")
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param ConfigRepository $configRepository
     * @param MasterSimRepository $masterRepository
     * @param ConfigFileRepository $configFileRepository
     * @param FileCategoryRepository $fileCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(ConfigRepository $configRepository,MasterSimRepository $masterRepository, ConfigFileRepository $configFileRepository, FileCategoryRepository $fileCategoryRepository, Request $request):Response
    {
        $user = $this->getUser();

        $masters = $masterRepository->findAll();
        $master = new  MasterSim();
        $form = $this->createForm(MasterSimType::class, $master);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Un noouveau numéro master a été ajouté");
            $master->setAuthor($user);
            $this->manager->persist($master);
            return $this->redirectToRoute('setting');
        }

        $configs = $configRepository->findAll();
        $categories = $fileCategoryRepository->findAll();
        $files = $configFileRepository->findConfigFile('transaction');
        return $this->render('setting/index.html.twig', [
            'masters'=> $masters,
            'form'=>$form->createView(),
            'configs' => $configs,
            'categories' => $categories,
            'files' => $files
        ]);
    }


    public function showMaster(MasterSim $master)
    {
        return $this->render('setting/showMaster.html.twig', [
            'master'=>$master,
        ]);
    }
}
