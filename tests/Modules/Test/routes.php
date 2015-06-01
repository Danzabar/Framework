<?php

/**
 * We should have access to the $route var here.
 *
 */
$route->add('mod.test', '/mod/test', ['GET'], Array('controller' => 'Wasp\Test\Controller\Controller::returnString'));
