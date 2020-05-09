<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Stage;
use App\Form\DemandeStageType;
use App\Repository\EncadrantRepository;
use App\Repository\StageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Encadrant;

/**
 * @Route("/front")
 */
class FrontController extends AbstractController
{

    private $encadrantRepositorie;
    private $stageRepositorie;

    public function __construct(EncadrantRepository $encadrantRepository, StageRepository $stageRepository)
    {
        $this->encadrantRepositorie = $encadrantRepository;
        $this->stageRepositorie = $stageRepository;
    }

    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/encadrants", name="front_encadrants")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function encadrants(PaginatorInterface $paginator,Request $request): Response
    {
//        $encadrants = $this->encadrantRepositorie->findAll();


        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $encadrantRepository = $em->getRepository(Encadrant::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allEncadrantsQuery = $encadrantRepository->createQueryBuilder('p')
            ->getQuery();

        // Paginate the results of the query
        $pagination = $paginator->paginate(
        // Doctrine Query, not results
            $allEncadrantsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('front/encadrants.html.twig', [
//            'encadrants' => $encadrants,
            'activated_page' => "front-encadrants",
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/stages", name="front_stages")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function stages(Request $request, PaginatorInterface $paginator): Response
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $stageRepository = $em->getRepository(Stage::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allStagesQuery = $stageRepository->createQueryBuilder('p')
            ->getQuery();

        // Paginate the results of the query
        $pagination = $paginator->paginate(
        // Doctrine Query, not results
            $allStagesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('front/stages.html.twig', [
            'pagination' => $pagination,
            'activated_page' => "front-stages"
        ]);
    }

    /**
     * @Route("/demandes", name="demande_stage", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function demandeStage(Request $request): Response
    {

        /** @var Etudiant $etudiant */
        $etudiant = $this->getUser();
        $nombreStagesEtudiant = count($etudiant->getStages());

        dump($nombreStagesEtudiant);
        if($nombreStagesEtudiant == 0){
            $this->addFlash('info','NB : Vous avez le droit de demander au maximun 3 stages.');
        }
        if($nombreStagesEtudiant > 0 && $nombreStagesEtudiant < 3){
            $this->addFlash('info','NB : Vous avez le droit de demander au maximun 3 stages.');
            $this->addFlash('warning','Attention : Vous avez déjà fait '.$nombreStagesEtudiant.' demande(s), il vous reste '.(3-$nombreStagesEtudiant).' tentative(s)');
        }
        if($nombreStagesEtudiant >= 3){
            $this->addFlash('danger','Vous avez atteint le nombre maximale des demandes, vous ne pouvez pas éfféctué plus de demande...!!');
        }

        $form = $this->createForm(DemandeStageType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $stages_demande = $request->request->get("demande_stage")['Stages'];
            foreach ($stages_demande as $idStage) {
                $stage = $this->stageRepositorie->find($idStage);
                $etudiant->addStage($stage);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etudiant);
            $entityManager->flush();
        }

        return $this->render('front/deamnde_stage.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
