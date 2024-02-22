<?php

namespace App\Business;

use App\Business\DTO\CategoryDto;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCreator
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function create(CategoryDto $categoryDto): Category
    {
        $category = new Category();
        $category->setName($categoryDto->getName());

        return $category;
    }
}