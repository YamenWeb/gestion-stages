<?php

namespace App\Controller;

use App\Entity\Societe;
use App\Form\SocieteType;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SocieteController
 * @package App\Controller
 * @Route("/admin/societe")
 */

class SocieteController extends AbstractController
{
    /**
     * @Route("/", name="societe_index", methods={"GET"})
     */
    public function index(SocieteRepository $societeRepository): Response
    {
        return $this->render('societe/index.html.twig', [
            'controller_name' => 'SocieteController',
            'societes' => $societeRepository->findAll(),
            'activated_page' => 'admin-index-societe'
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="societe_new", methods={"GET","POST"})
     */

    public function new(Request $request): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class,$societe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('societe_index');
        }

        return $this->render('societe/new.html.twig', [
            'societe' => $societe,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-societe'
        ]);


    }

    /**
     * @Route("/{id}/edit", name="societe_edit")
     * @param Request $request
     * @param Societe $societe
     * @return Response
     */
    public function edit(Request $request, Societe $societe): Response
    {
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($societe);
            $entityManager->flush();

            $this->redirectToRoute('societe_index');
        }

        return $this->render('societe/edit.html.twig', [
            'societe' => $societe,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-societe'
        ]);
    }

    /**
     * @Route("/{id}", name="societe.show", methods={"GET"})
     * @param Societe $societe
     * @return Response
     */
    public function show (Societe $societe): Response
    {
        return $this->render('societe/show.html.twig',[
            'societe' => $societe,
            'activated_page' => 'admin-index-societe'
        ]);
    }

    /**
     * @Route("/{id}", name="societe.delete", methods={"DELETE"})
     * @param Request $request
     * @param Societe $societe
     * @return Response
     */
    public function delete(Request $request, Societe $societe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($societe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('societe_index');
    }
}
