<?php

/**
 *  Templating settings
 *
 */
return array (

    'twig'                      => array(

        'cache'                 => dirname(__DIR__) . '/cache/templates/',
        'debug'                 => true,
        'base_template_class'   => 'Twig_Template',
        'autoescape'            => false
    ),
);
