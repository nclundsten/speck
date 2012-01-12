<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'jqueryui' => 'JqueryUi\Controller\IndexController',
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options' => array(
                        'script_paths' => array(
                            'jqueryui' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);