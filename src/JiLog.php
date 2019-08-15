<?php

namespace OFashion\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

class Log
{
    protected static $logInstance;

    protected static $requestId;

    public static function getLogInstance()
    {
        if (null === static::$logInstance) {
            static::$logInstance = new Logger(env('APP_NAME', 'logger_name_undefined'));
        }

        return static::$logInstance;
    }

    public static function getRequestId()
    {
        if (null == static::$requestId) {
            static::setRequestId();
        }

        return static::$requestId;
    }

    public static function setRequestId(string $requestId = null)
    {
        static::$requestId = $requestId ?? self::generateRequestID();
    }

    private static function generateRequestID(): string
    {
        return substr(hash('ripemd128', uniqid("", true) .
                md5(time() . getmypid() . env('APP_NAME'))), 0, 12) . "-" . env('APP_SOURCE', '0');
    }

    public static function __callStatic($method, $args)
    {
        $args[1]['request_id'] = static::getRequestId();
        $args[1]['datetime'] = date("Y-m-d H:i:s");
        $logName = $args[2] ?? 'default';
        $path = env('LOG_PATH', storage_path('../../../api_logs/' . env('APP_NAME', 'undefined') . '/')) . $logName . '/';
        $logFile = $path . date('Y') . '/' . date('m') . date('Ymd') . '.log';
        $handler = new StreamHandler($logFile);
        $handler->setFormatter(new JsonFormatter());
        ($logInstance = static::getLogInstance())->pushHandler($handler);
        $logInstance->$method($args[0], $args[1]);
    }
}