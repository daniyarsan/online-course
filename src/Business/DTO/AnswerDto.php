<?php

namespace App\Business\DTO;

class AnswerDto
{
    private $id;
    private $type;
    private $payload;

    public function __construct(int $id, string $type, $payload)
    {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }


}