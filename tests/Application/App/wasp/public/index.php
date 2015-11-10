<?php

/**
 *  Simply calls the application's respond method
 *
 */
$env = require_once(dirname(__DIR__) . '/bootstrap/bootstrap.php');

$env->getApplication()->respond();
