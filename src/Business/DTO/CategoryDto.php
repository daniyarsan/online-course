<?php

namespace App\Business\DTO;

class CategoryDto
{
    private $id;
    private $name;
    private $active;

    public function __construct($id, $name, $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }


}