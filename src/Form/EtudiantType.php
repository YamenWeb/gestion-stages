<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantType extends AbstractType
{
    private $formationRepository;
    public function __construct(FormationRepository $formationRepository)
    {
        $this->formationRepository = $formationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formations = $this->formationRepository->findAll();
        $builder
            ->add('email')
//            ->add('roles')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
            ))
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance')
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Homme' => '1',
                    'Femme' => '2',
                    ],
                ])
            ->add('ville')
//            ->add('encadrant')
            ->add('formation', ChoiceType::class, [
                'choices' => $formations,
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                'choice_value' => 'id',
                // a callback to return the label for a given choice
                // if a placeholder is used, its empty value (null) may be passed but
                // its label is defined by its own "placeholder" option
                'choice_label' => function(?Formation $formation) {
                    return $formation ? $formation->getNom() : '';
                },

            ])
//            ->add('stages')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
