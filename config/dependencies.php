<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;

return function(ContainerBuilder $containerBuilder) {
    // nhận vào array
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get('settings')['db'];
        
            $host = $settings['host'];
            $dbname = $settings['database'];
            $username = $settings['username'];
            $password = $settings['password'];
            $charset = $settings['charset'];
            $flags = $settings['flags'];
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        
            return new PDO($dsn, $username, $password, $flags);
        }
    ]);
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function(ContainerInterface $c) {
            $settings = $c->get('settings');
            
            $loggerSetting = $settings['logger'];
            
            $logger = new Logger($loggerSetting['name']);
            
            $processor =  new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSetting['path'], $loggerSetting['level']);
            $logger->pushHandler($handler);

            return $logger;
        }
    ]);
};