<?php 
namespace App\Application\Actions\Assets;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Assets\AssetsServices;

abstract class AssetsAction extends Actions {
    protected $assetsServices;
    public function __construct(LoggerInterface $logger, AssetsServices $assetsServices) 
    {
        parent::__construct($logger);
        $this->assetsServices = $assetsServices;
    }
}