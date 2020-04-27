<?php

namespace App\Controller;

use App\Form\EncadrantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Encadrant;
use App\Repository\EncadrantRepository;

/**
 * @Route("/admin/encadrant")
 */
class EncadrantController extends AbstractController
{
    /**
     * @Route("/", name="encadrant_index")
     */
    public function index(EncadrantRepository $encadrantRepository): Response
    {
        return $this->render('encadrant/index.html.twig', [
            'controller_name' => 'EncadrantController',
            'encadrants' => $encadrantRepository->findAll(),
            'activated_page' => 'admin-index-encadrant'
        ]);
    }

    /**
     * @Route("/new", name="encadrant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $encadrant = new Encadrant();
        $form = $this->createForm(EncadrantType::class, $encadrant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($encadrant);
            $entityManager->flush();

            return $this->redirectToRoute('encadrant_index');
        }

        return $this->render('encadrant/new.html.twig',[
            'encadrant' => $encadrant,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-encadrant'
        ]);

    }

    /**
     * @param Response $requset
     * @param Encadrant $encadrant
     * @Route("/{id}/edit", name="encadrant_edit", methods={"GET","POST"})
     * @return Response
     */

    public function edit(Request $requset,Encadrant $encadrant): Response
    {
        $form = $this->createForm(EncadrantType::class, $encadrant);
        $form->handleRequest($requset);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($encadrant);
            $entityManager->flush();

            return $this->redirectToRoute('encadrant_index');
        }

        return $this->render('encadrant/edit.html.twig',[
            'encadrant' => $encadrant,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-encadrant'
        ]);
    }

    /**
     * @param Encadrant $encadrant
     * @Route("/{id}.show", name="encadrant_show")
     * @return Response
     */

    public function show(Encadrant $encadrant): Response
    {
        return $this->render('encadrant/show.html.twig',[
            'encadrant' => $encadrant,
            'activated_page' => 'admin-index-encadrant'
            ]);
    }

    /**
     * @param Request $request
     * @param Encadrant $encadrant
     * @Route("/{id}", name="encadrant_delete")
     * @return Response
     */

    public function delete(Request $request ,Encadrant $encadrant):Response
    {
        if($this->isCsrfTokenValid('delete'.$encadrant->getId(),$request->request->get('_toker')))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($encadrant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('encadrant_index');
    }

}
