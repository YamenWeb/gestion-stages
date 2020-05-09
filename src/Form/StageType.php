<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Societe;
use App\Entity\Stage;
use App\Repository\FormationRepository;
use App\Repository\SocieteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    private $formationRepository;
    private $societeRepository;

    public function __construct(FormationRepository $formationRepository, SocieteRepository $societeRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->societeRepository = $societeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formations = $this->formationRepository->findAll();
        $societes = $this->societeRepository->findAll();
        $builder
            ->add('titre')
            ->add('description')
//            ->add('type')
            ->add('type',ChoiceType::class, [
                'choices'  => [
                    'PFE' => 'PFE',
                    'Ouvrier' => 'Ouvrier',
                ],
            ])
            ->add('duree')
            ->add('date_debut')
            ->add('disponibilitee')
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
            ->add('societee', ChoiceType::class, [
                'choices' => $societes,
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                'choice_value' => 'id',
                // a callback to return the label for a given choice
                // if a placeholder is used, its empty value (null) may be passed but
                // its label is defined by its own "placeholder" option
                'choice_label' => function(?Societe $societes) {
                    return $societes ? $societes->getNom() : '';
                },

            ])
//            ->add('formation')
//            ->add('societee')
//            ->add('etudiant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
