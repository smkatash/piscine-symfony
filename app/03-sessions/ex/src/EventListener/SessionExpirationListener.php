<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class SessionExpirationListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        if ($request->attributes->get('_route') !== 'anonymous_page') {
            return;
        }

        if ($session->has('expiration_time') && time() > $session->get('expiration_time')) {
            $session->invalidate();
            $response = new RedirectResponse($this->router->generate('app_session_expired'));
            $event->setResponse($response);
        }
        if (!$session->has('expiration_time') && !$session->has('anonymous_data')) {
            $response = new RedirectResponse($this->router->generate('app_session_expired'));
            $event->setResponse($response);
        }
    }
}


?>