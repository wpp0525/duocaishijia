<?php
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
/*下面两行代码是开发者模式*/
//error_reporting(E_ALL|E_STRICT);
//ini_set('display_errors', 'On');

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
require_once(dirname(__FILE__).'/protected/components/Global.php');
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$local=require('./protected/config/main-local.php');
$base=require('./protected/backend/config/main.php');
$config=CMap::mergeArray($base, $local);
Yii::createWebApplication($config)->run();