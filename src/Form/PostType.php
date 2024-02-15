<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('text')
            ->add('imageFile', FileType::class, [
                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image',
                    ])
                ],
            ])
            ->add('categorie', ChoiceType::class, [
                'choices'  => [
                    'Guerre' => 'Guerre',
                    'Politique' => 'Politique',
                    'Divertissement' => 'Divertissement',
                    'Monstres' => 'Monstres',
                    'Amour' => 'Amour',
                    'Géographie' => 'Géographie',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
