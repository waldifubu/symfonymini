<?php

namespace App\Form;

use App\Entity\PodcastSeries;
use App\Enum\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\MainCategoryEnum;
use App\Enum\SubCategoryEnum;

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
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Episodic' => 'episodic',
                    'Serial' => 'serial',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            // episodic
            // serial
            ->add('owner')
            ->add('ttl')
            ->add('frequency')
            ->add('published', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('ownerEmail')
            ->add('mainCategory', ChoiceType::class, [
                'choices' => MainCategoryEnum::cases(), // Use enum cases as choices
                // VALUue not needed?????
//                'choice_value' => fn(?MainCategoryEnum $status): string => $status?->name ?? '',
                'choice_label' => fn(MainCategoryEnum $enum): string => $enum->value,
                'placeholder' => 'Select a category',
                'multiple' => false,
                'expanded' => false,
                'empty_data' => null,
            ])
            ->add('subCategory', ChoiceType::class, [
                'choices' => SubCategoryEnum::cases(),
//                'choice_value' => fn(?SubCategoryEnum $status): string => $status?->name ?? '',
                'choice_label' => fn(SubCategoryEnum $enum):string => $enum->value,
                'placeholder' => 'Select a category',
                'multiple' => false,
                'expanded' => false,
                'empty_data' => null,
            ])
            ->add('keywords');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound'   => true,
            'empty_data' => null,
            'csrf_protection' => false,
            'data_class' => PodcastSeries::class,
        ]);
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
}
