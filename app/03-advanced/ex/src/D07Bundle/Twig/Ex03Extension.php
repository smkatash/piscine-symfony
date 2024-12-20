<?php

namespace App\D07Bundle\Twig;

use App\D07Bundle\Service\Ex03Service;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Ex03Extension extends AbstractExtension
{
    private $ex03Service;

    public function __construct(Ex03Service $ex03Service)
    {
        $this->ex03Service = $ex03Service;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('uppercaseWords', [$this->ex03Service, 'uppercaseWords']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('countNumbers', [$this->ex03Service, 'countNumbers']),
        ];
    }
}


?>