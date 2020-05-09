<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Stage;
use App\Repository\StageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;

class DemandeStageType extends AbstractType
{
    private $stageRepository;
    private $security;
    public function __construct(StageRepository $stageRepository, Security $security)
    {
        $this->stageRepository = $stageRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Etudiant $etudiant */
        $etudiant = $this->security->getUser();
//        dump(\count($etudiant->getStages()));
        $countMaxDemande = 3 - \count($etudiant->getStages());
        $disabled = false;
        if (\count($etudiant->getStages()) >= 3){
            $disabled = true;
        }
        $stages = $this->stageRepository->findBy([
            "disponibilitee" => 1,
            "formation" => $etudiant->getFormation()->getId()
        ]);
        $builder
//            ->add('field_name')
            ->add('Stages', ChoiceType::class, [
                'choices' => $stages,
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                'choice_value' => 'id',
                // a callback to return the label for a given choice
                // if a placeholder is used, its empty value (null) may be passed but
                // its label is defined by its own "placeholder" option
                'choice_label' => function(?Stage $stages) {
                    return $stages ? "Stage ".$stages->getType()." - ".$stages->getTitre(): '';
                },
                'expanded' => true,
                'multiple' => true,
                'label' => 'Les sujets de stages disponibles pour votre formation',
                'constraints' => [new Count(['max' => $countMaxDemande])]
            ])
            ->add('submit', SubmitType::class,[
                'attr' => ['disabled' => $disabled],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
