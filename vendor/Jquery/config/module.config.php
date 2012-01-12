<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'jquery' => 'Jquery\Controller\IndexController',
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options' => array(
                        'script_paths' => array(
                            'jquery' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);