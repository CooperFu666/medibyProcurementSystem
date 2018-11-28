<?php
define('__ROOT__', __DIR__);
// change the following paths if necessary
$yii = __ROOT__ . '/framework/yii.php';
$config = __ROOT__ . '/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
require_once(__ROOT__ . '/common/config/aliases.php');
Yii::$classMap = require_once(__ROOT__ . "/common/config/autoloadClassMap.php");
require_once(__ROOT__ . "/vendor/autoload.php");
Yii::createWebApplication($config)->run();
