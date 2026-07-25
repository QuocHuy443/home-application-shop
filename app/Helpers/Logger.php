<?php

namespace App\Helpers;

class Logger
{
    public static function log($level, $message, $context = [])
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $logFile = $logDir . '/app-' . $date . '.log';
        
        $contextStr = empty($context) ? '' : json_encode($context);
        $logMessage = "[{$time}] {$level}: {$message} {$contextStr}" . PHP_EOL;

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }
}
