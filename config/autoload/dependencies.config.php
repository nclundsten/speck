<?php
return array('di' => array(
    'instance' => array(
        'alias' => array(
            // Which user service for SpeckCatalog to use
            'speckcatalog_user_service' => 'zfcuser_user_service',
        ),
    ),
));
