<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Quiz;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name');
//            ->add('active', null, ['label' => 'Категория активна']);
            // ->add('quizes', EntityType::class, [
            //     'class' => Quiz::class,
            //     'multiple' => true,
            //     'by_reference' => false
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
