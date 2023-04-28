<?php

class LoggerSingleton
{
    private int $logCount = 0;
    private static ?LoggerSingleton $loggerSingletonInstance = null;

    private function __construct()
    {
    }

    public static function getInstance(): LoggerSingleton
    {
        if (static::$loggerSingletonInstance == null) {
            static::$loggerSingletonInstance = new static();
        }
        return self::$loggerSingletonInstance;
    }

    public function log(string $message): void
    {
        printf("%s: %s\n", $this->logCount, $message);
        $this->logCount++;
    }
}

class HardProcessor
{
    private int $start;
    public function __construct(int $start)
    {
        $this->start = $start;
        LoggerSingleton::getInstance()->log("Processor just created.");
    }

    public function processTo(int $end): int
    {
        $sum = 0;
        for ($i = $this->start; $i <= $end; ++$i) {
            $sum += $i;
        }
        LoggerSingleton::getInstance()->log("Processor just calculated some value: " . $sum);

        return $sum;
    }
}

$logger = LoggerSingleton::getInstance();
$processor = new HardProcessor(1);
$logger->log("Hard work started...");
$processor->processTo(5);
$logger->log("Hard work finished...");