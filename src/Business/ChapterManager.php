<?php

namespace App\Business;

use App\Entity\Chapter;
use App\Entity\Course;
use App\Repository\ChapterRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ChapterManager
{
    private EntityManagerInterface $em;
    private ChapterRepository $chapterRepository;

    public function __construct(
        EntityManagerInterface $em,
        ChapterRepository      $chapterRepository
    )
    {
        $this->em = $em;
        $this->chapterRepository = $chapterRepository;
    }


    public function create(string $title, Course $course): Chapter
    {
        $chapter = new Chapter();
        $chapter->setTitle($title);
        $chapter->setCourse($course);

        $latestChapter = $this->chapterRepository->findOneBy(['course' => $course], ['sorting' => 'DESC'], 1, 0);
        if (!$latestChapter) {
            $chapter->setSorting(1);
        } else {
            $chapter->setSorting($latestChapter->getSorting() + 1);
        }

        return $chapter;
    }
}