<?php

namespace AppBundle\Eventsuser;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RedirectLogin
{
   

private $securityautorization;
private $router;
  public function __construct(AuthorizationCheckerInterface  $securityautorization,UrlGeneratorInterface  $router)
  {
    $this->securityautorization = $securityautorization;
    $this->router = $router;

  }
  public function eventuserredirect(GetResponseEvent $event)
  {
    
    $currentRoute = $event->getRequest()->attributes->get('_route');
    if ($currentRoute == "fos_user_security_login"  OR  $currentRoute == "fos_user_registration_register") {
       if ($this->securityautorization->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

        $event->setResponse(  new RedirectResponse($this->router->generate('homepage')));

       }

    }
    
  }
}


