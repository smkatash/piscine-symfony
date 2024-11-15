<?php

namespace App\E04Bundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public function getCurrentSession()
    {
        return $this->requestStack->getSession();
    }
}

?>