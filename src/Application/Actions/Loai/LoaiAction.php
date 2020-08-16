<?php

namespace App\Application\Actions\Loai;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Action;

abstract class LoaiAction extends Action{
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
    }
}