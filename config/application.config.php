<?php
return array(
    'modules' => array(
        //'AssetLoader',
        'Application',
        'ZfcUser',
        'ZfcBase',
        'SpeckCatalog',
        //'TwitterBootstrap',
        //'AsseticBundle',
        //'Install',
        //'SpeckBase',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled'    => false,
        'cache_dir'               => './data/cache',
        'module_paths' => array(
            //'../devmodules',
            './moduledev',
            './module',
            './vendor',
        ),
    ),
);
