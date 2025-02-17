<?php

namespace App\Form;

use App\Entity\PodcastSeries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\DBAL\MainCategoryEnum;
use App\DBAL\SubCategoryEnum;

class PodcastSeriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('author')
            ->add('locked')
            ->add('blocked')
            ->add('complete')
            ->add('explicit')
            ->add('copyright')
            ->add('language')
            ->add('cover')
            ->add('type')
            ->add('owner')
            ->add('mainCategory', EnumType::class, [
                'class' => MainCategoryEnum::class,
                'choices' => MainCategoryEnum::cases() ,//$this->formatEnumChoices(MainCategoryEnum::cases()),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('subCategory', EnumType::class, [
                'class' => SubCategoryEnum::class,
//                'choices' => SubCategoryEnum::cases(),//
              'choices' =>  SubCategoryEnum::cases(), //$this->formatEnumChoices(SubCategoryEnum::cases()),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('keywords')
        ;
    }


    /**
     * Formats enum cases into a choices array for Symfony's ChoiceType.
     *
     * @param array $cases The enum cases.
     * @return array The formatted choices.
     */
    private function formatEnumChoices(array $cases): array
    {
        $choices = [];
        foreach ($cases as $case) {
            // Use the enum value as the key and the enum name as the label
            $choices[$case->name] = $case->name;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => PodcastSeries::class,
        ]);
    }
}
