<?php

namespace App\Form;

use App\Entity\PodcastSeries;
use App\Enum\Status;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('locked', CheckboxType::class, ['required' => false])
            ->add('blocked', CheckboxType::class, ['required' => false])
            ->add('explicit', CheckboxType::class, ['required' => false])
            ->add('complete', CheckboxType::class, ['required' => false])
            ->add('copyright', TextType::class, ['required' => false])
            ->add('language', TextType::class, ['required' => false])
            ->add('cover', TextType::class, ['required' => false])
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
            ->add('published')
            ->add('ownerEmail')
            ->add('mainCategory', EnumType::class, [
                'class' => MainCategoryEnum::class,
                'placeholder' => 'Choose a category',
                'choice_value' => fn(?MainCategoryEnum $status): string => $status?->name ?? '',
                'choice_label' => fn(MainCategoryEnum $enum): string => $enum->value,
                'required' => false,
                'attr' => [
                    'data-action' => 'change->subcategory#load input->subcategory#load',
                    'data-subcategory-target' => 'category',
                ],
            ])
            ->add('subCategory', ChoiceType::class, [
                'choices' => SubCategoryEnum::cases(),
                'choice_value' => fn(?SubCategoryEnum $status): string => $status?->name ?? '',
                'choice_label' => fn(SubCategoryEnum $enum): string => $enum->value,
                'placeholder' => 'Select a category',
                'multiple' => false,
                'expanded' => false,
                'empty_data' => null,
                'attr' => [
                    'data-subcategory-target' => 'subcategory'
                ],
            ])
            ->add('keywords');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => true,
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
