<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\StatusCandidature;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste')
            ->add('entreprise')
            ->add('lieu')
            ->add('typeContrat')
            ->add('source')
            ->add('urlOffre')
            ->add('dateCandidature', null, [
                'widget' => 'single_text'
            ])
            ->add('statut', EnumType::class, [
                'class' => StatusCandidature::class,
                'choice_label' => fn (StatusCandidature $statut) => $statut->label(),
            ])
            ->add('notes')
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
