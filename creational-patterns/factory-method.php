<?php

interface ILogger
{
    public function logMessage(string $message);
    public function logError(string $message);
    public function logVerboseInformation(string $message);
}

class Log4NetLogger implements ILogger
{

    public function logMessage(string $message)
    {
        printf("%s %s: %s", "Log4Net", "message", $message);
    }

    public function logError(string $message)
    {
        printf("%s %s: %s", "Log4Net", "error", $message);
    }

    public function logVerboseInformation(string $message)
    {
        printf("%s %s: %s", "Log4Net", "info", $message);
    }
}

class EnterpriseLogger implements ILogger
{

    public function logMessage(string $message)
    {
        printf("%s %s: %s", "Enterprise", "message", $message);
    }

    public function logError(string $message)
    {
        printf("%s %s: %s", "Enterprise", "error", $message);
    }

    public function logVerboseInformation(string $message)
    {
        printf("%s %s: %s", "Enterprise", "info", $message);
    }
}

class LoggingProviders {
    public const ENTERPRISE = "enterprise";
    public const LOG4NET = "log4net";
}

class LoggerProviderFactory
{
    public static function getLoggingProvider(string $providerType): ILogger
    {
        switch ($providerType)
        {
            case LoggingProviders::ENTERPRISE:
                return new EnterpriseLogger();
            case LoggingProviders::LOG4NET:
                return new Log4NetLogger();
            default:
                return new EnterpriseLogger();
        }
    }
}

function getTypeOfLoggingProviderFromConfigFile()
{
    return 'log4net';
}

$providerType = getTypeOfLoggingProviderFromConfigFile();
$logger = LoggerProviderFactory::getLoggingProvider($providerType);
$logger->logMessage("Hello Factory Method Design Pattern.");
// Вивід: [Log4Net message: Hello Factory Method Design Pattern]