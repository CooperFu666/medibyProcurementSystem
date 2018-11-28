<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/5/23
 * Time: 17:18
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class GuoBangLog extends Logger
{
    public static function getMonolog($logName, $levelAlias) {
        $logger = new Logger($logName);
        $logger->pushHandler(new StreamHandler(__ROOT__ . Yii::app()->params['monologPath'], $levelAlias));
        return $logger;
    }

    public static function setMonolog($logName, $logTitle, $logContent = [], $levelAlias = GuoBangLog::INFO) {
        $levelAlias = Logger::$levels[$levelAlias];
        $logger = self::getMonolog($logName, $levelAlias);
        $logger->$levelAlias($logTitle, $logContent);
    }
}