<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Actions;
use App\Domain\Category\CategoryServices;
use Psr\Log\LoggerInterface;

abstract class CategoryAction extends Actions {
    protected $categoryServices;
    
    public function __construct(CategoryServices $categoryServices, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->categoryServices = $categoryServices;
    }
}