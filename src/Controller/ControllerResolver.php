<?php namespace Wasp\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseResolver,
    Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Controller resolver class
 *
 * @package Wasp
 * @subpackage Controller
 * @author Dan Cox
 */
class ControllerResolver extends BaseResolver
{
    use DependencyInjectionAwareTrait;

    /**
     * Returns a callable controller
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Callable|False
     * @author Dan Cox
     */
    public function getController(\Symfony\Component\HttpFoundation\Request $request)
    {
        $controller = parent::getController($request);

        if (is_subclass_of($controller[0], 'Wasp\Controller\BaseController'))
        {
            $controller[0]->setDI($this->DI);
        }

        if (is_subclass_of($controller[0], 'Wasp\Controller\RestController') || is_a($controller[0], 'Wasp\Controller\RestController'))
        {
            $controller[0]->setEntity($request->attributes->get('entity'));
        }

        return $controller;
    }

} // END class ControllerResolver extends BaseResolver
