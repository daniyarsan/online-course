<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('instanceof', [$this, 'isInstanceof']),
        ];
    }

    public function isInstanceof($var, $instance): bool
    {

        return $var instanceof $instance;
    }


}