<?php

namespace App\Controller;

use App\Entity\Config;
use App\Entity\ConfigFile;
use App\Form\ConfigType;
use App\Repository\ConfigFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigFileController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/transaction/files", name="transaction_file")
     * @param ConfigFileRepository $repository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ConfigFileRepository $repository)
    {
        $files = $repository->findTransactionFile('transaction');
        $configs = $repository->findConfigFile('transaction');
        return $this->render('config_file/index.html.twig', [
            'files' => $files,
            'configs' => $configs
        ]);
    }

    /**
     * @Route("/admin/config/files/add", name="file_add")
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request):Response
    {
        $user = $this->getUser();
        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $config->setAuthor($user);
            $files = $config->getFiles();
            $this->addFlash('success', "Le fichier <strong>{$config->getContent()}</strong> a bien été ajouter");
            $this->manager->persist($config);
            foreach ($files as $file) {
                $file->setConfig($config);
            }
            $this->manager->flush();
            return $this->redirectToRoute('setting');
        }
        return $this->render('config_file/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/config/files/{id}/edit", name="file_edit")
     * @param Request $request
     * @param Config $config
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Config $config):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $config->setAuthor($user);
            $this->addFlash('success', "Le fichier <strong>{$config->getContent()}</strong> a bien été modifier");
            $this->manager->persist($config);
            $this->manager->flush();
            return $this->redirectToRoute('transaction_file');
        }
        return $this->render('config_file/edit.html.twig', [
            'form' => $form->createView(),
            'config' => $config,
        ]);
    }

    /**
     * @Route("/admin/config/files/{id}/delete", name="file_delete")
     * @param Request $request
     * @param Config $config
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Request $request, Config $config):Response
    {

        $this->addFlash('success', "La configuration du <strong>{date_format($config->getConfigAt(), 'F jS à g:ia')}</strong> a bien été supprimer");
        $this->manager->remove($config);
        $this->manager->flush();
        return $this->redirectToRoute('setting');
    }

    /**
     * @Route("/admin/files/{id}/delete", name="configFile_delete")
     * @param Request $request
     * @param ConfigFile $configFile
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function deleteFile(Request $request, ConfigFile $configFile):Response
    {

        $this->addFlash('success', "La configuration du <strong>{$configFile->getFileName()}</strong> a bien été supprimer");
        $this->manager->remove($configFile);
        $this->manager->flush();
        return $this->redirectToRoute('transaction_file');
    }
}
