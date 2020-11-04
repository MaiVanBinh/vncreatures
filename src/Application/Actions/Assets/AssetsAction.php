<?php 
namespace App\Application\Actions\Assets;
use App\Application\Actions\Actions;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class AssetsAction extends Actions {
    public function __construct(LoggerInterface $logger) 
    {
        parent::__construct($logger);
    }
}