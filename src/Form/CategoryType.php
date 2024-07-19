<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-input border border-gray-300 rounded p-2 mb-4 w-full',
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-textarea border border-gray-300 rounded p-2 mb-4 w-full',
                ],

                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide.',
                    ])
                ],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn submit'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
