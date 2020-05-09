<?php

namespace App\DataFixtures;

use App\Entity\Encadrant;
use App\Entity\Etudiant;
use App\Repository\EncadrantRepository;
use App\Repository\FormationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EtudiantFixtures extends Fixture
{
    private $passwordEncoder;
    private $encadrantRepository;
    private $formationRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EncadrantRepository $encadrantRepository, FormationRepository $formationRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->encadrantRepository = $encadrantRepository;
        $this->formationRepository = $formationRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        $etudiant = new Etudiant();
        $etudiant->setEmail('etudiant@gmail.com');
        $etudiant->setPassword($this->passwordEncoder->encodePassword(
            $etudiant,
            '123456'
        ));
        $etudiant->setNom('Masmoudi');
        $etudiant->setPrenom('Etudiant');
        $etudiant->setRoles(['ROLE_ETUDIANT']);
        $date = new \DateTime("1990-05-01 00:00:00");
        $etudiant->setDateNaissance($date);
        $encadrant = $this->encadrantRepository->find(2);
        $etudiant->setEncadrant($encadrant);
        $etudiant->setSexe(1);
        $etudiant->setVille('Sfax');
        $formation = $this->formationRepository->find(2);
        $etudiant->setFormation($formation);
        $manager->persist($etudiant);

        $manager->flush();
    }
}
