<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 26/09/2018
 * Time: 10:33
 */

namespace AppBundle\Security;

use AppBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    protected $route;
    public function __construct(UrlGeneratorInterface $route)
    {
        $this->route = $route;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {

//        return new Response('<h1>fzzzzzzzzzzz</h1>');
        return new RedirectResponse($this->route->generate('homepage'));

    }
}