<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'twitterbootstrapfull' => 'TwitterBootstrapFull\Controller\IndexController',
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options' => array(
                        'script_paths' => array(
                            'twitterbootstrapfull' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);