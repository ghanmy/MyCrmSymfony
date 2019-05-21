<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getRoles', [$this, 'getRoles']),
        ];
    }

    public function getRoles($serializedRoles)
    {
        return unserialize($serializedRoles);
    }
}