<?php
//
//// uncomment the following to define a path alias
//// Yii::setPathOfAlias('local','path/to/local-folder');
//
//// This is the main Web application configuration. Any writable
//// CWebApplication properties can be configured here.
return array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=lzdcsj',
            'emulatePrepare' => true,
            'username' => 'duocai',
            'password' => 'www!!@@##',
            'charset' => 'utf8',
            'tablePrefix' => ''
        )
        )
    );