<?php

namespace App\Form;

use App\Model\FileUploadModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filename', TextType::class, [
                'label' => 'Filename',
                'required' => true,
            ])
            ->add('path', TextType::class, [
                'label' => 'Path',
                'required' => true,
            ])
            ->add('storage', TextType::class, [
                'label' => 'Storage',
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'label' => 'File',
                'required' => true,
                'multiple' => false,
            ])
            ->add('discr', TextType::class, [
                'label' => 'Discriminator',
                'required' => true,
            ]);
    }

    // Optional: If you want to handle the form without field names
    public function getBlockPrefix(): string
    {
        return ''; // Empty string removes form name prefix
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => FileUploadModel::class,
        ]);
    }
}
