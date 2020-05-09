<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/utilisateur")
 * Require ROLE_ADMIN for *every* controller method in this class.
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class UtilisateurController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="utilisateur_index")
     */
    public function index(UserRepository $userRepository): Response
    {
        $utilisateurs = $userRepository->findAll();
        $adminUtilisateurs = array();
        foreach ($utilisateurs as $utilisateur) {
            if (!($utilisateur instanceof Etudiant)) {
                $adminUtilisateurs[] = $utilisateur;
            }
        }
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
            'utilisateurs' => $adminUtilisateurs,
            'activated_page' => 'admin-index-utilisateur'
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="utilisateur_new")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $request->request->get('user')['password']['first']
            ));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-utilisateur'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_edit")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $request->request->get('user')['password']['first']
            ));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView(),
            'activated_page' => 'admin-index-utilisateur'
        ]);

    }

    /**
     * @Route("/{id}", name="utilisateur_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $user,
            'activated_page' => 'admin-index-utilisateur'
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }
}
