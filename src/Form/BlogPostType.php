<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\BlogPost;
use App\Enum\Status;
use Symfony\Component\Form\Extension\Core\Type\EnumType;


class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'choice_label' => function (Status $enum) {return $enum->value;},
//                'choices' =>Status::cases()
                /*
                    function (Status $status): string {
                    // Customize labels if needed (e.g., using translations)
                    return $status->value;
                },
                */
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
