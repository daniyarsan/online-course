<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;

class RankService
{
    private const RANK_STUDENT = 'Студент';
    private const RANK_LABORANT = 'Лаборант';
    private const RANK_ASSISTENT = 'Ассистент';
    private const RANK_PREPOD = 'Препод';
    private const RANK_DOCENT = 'Доцент';


    public function __construct()
    {
    }

    public function getRankByScorePercentage(int $score): ?string
    {
        $rank = false;

        switch($score) {
            case $this->in_range($score, 0, 20):
                $rank = self::RANK_STUDENT;
                break;
            case $this->in_range($score, 20, 40):
                $rank = self::RANK_LABORANT;
                break;
            case $this->in_range($score, 40, 60):
                $rank = self::RANK_ASSISTENT;
                break;
            case $this->in_range($score, 60, 80):
                $rank = self::RANK_PREPOD;
                break;
            case $this->in_range($score, 80, 100):
                $rank = self::RANK_DOCENT;
                break;
        };
        return $rank;
    }


    private function in_range($number, $min, $max, $inclusive = false)
    {
        if (is_int($number) && is_int($min) && is_int($max))
        {
            return $inclusive
                ? ($number >= $min && $number <= $max)
                : ($number > $min && $number < $max) ;
        }

        return false;
    }


}