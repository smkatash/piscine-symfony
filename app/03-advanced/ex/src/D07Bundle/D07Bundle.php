<?php

namespace App\D07Bundle;


use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use App\D07Bundle\DependencyInjection\D07Extension;


class D07Bundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new D07Extension();
    }
}

?>