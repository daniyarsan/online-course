<?php

namespace App\Tests\Business\DTO;

use App\Business\DTO\AnswerDto;
use PHPUnit\Framework\TestCase;

class AnswerDtoTest extends TestCase
{

    /**
     * @dataProvider data
     */
    public function testAnswerDtoParams($id, $type, $payload)
    {
        $answerDto = new AnswerDto($id, $type, $payload);

        $this->assertSame($id, $answerDto->getId());
        $this->assertSame($type, $answerDto->getType());
        $this->assertSame($payload, $answerDto->getPayload());
    }


    public function data()
    {
        return [
            [1, 'quiz', 123],
            [2, 'text', 'asdfasdfasdfasdfasdf']
        ];
    }

}
