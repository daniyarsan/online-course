<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Category;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('title')
            ->add('timeLimit')
            ->add('departments', EntityType::class, [
                'class' => Department::class,
                'multiple' => true,
                'by_reference' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
