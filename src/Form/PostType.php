<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => ['class' => 'form-control']])
            ->add('text', null, ['attr' => ['class' => 'form-control']])
            ->add('img', null, ['attr' => ['class' => 'form-control']])
            ->add('categorie', ChoiceType::class, [
                'choices'  => [
                    'Guerre' => 'Guerre',
                    'Politique' => 'Politique',
                    'Divertissement' => 'Divertissement',
                    'Monstres' => 'Monstres',
                    'Amour' => 'Amour',
                    'Géographie' => 'Géographie',
                ],
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
