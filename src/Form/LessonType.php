<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Lesson;
use App\Repository\ChapterRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentCourse = $options['course'];

        $builder
            ->add('title')
            ->add('content')
            ->add('chapter', EntityType::class, [
                'class' => Chapter::class,
                'query_builder' => function(ChapterRepository $repo) use ($currentCourse) {
                    return $repo->createQueryBuilder('c')
                        ->andWhere('c.course = :courseId')
                        ->setParameter('courseId', $currentCourse->getId());
                }, 'required' => false
            ])
            ->add('chapterTitle', TextType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('media', CollectionType::class, [
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ])
            ->add('mediaFile', CollectionType::class, [
                'entry_type' => MediaFileType::class,
                'allow_add' => true,
                'mapped' => false,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
            'course' => null,
        ]);
    }
}
