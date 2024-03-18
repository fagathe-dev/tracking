<?php

namespace App\Form\Admin\Xtrak;

use App\Entity\XtrakSite;
use App\Enum\Xtrak\EnvEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('domain', TextType::class, [
                'label' => 'Nom de domaine',
            ])
            ->add('env', ChoiceType::class, [
                'label' => 'Environnement',
                'choices' => EnvEnum::choices(),
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => XtrakSite::class,
            'csrf_protection' => false,
        ]);
    }
}
