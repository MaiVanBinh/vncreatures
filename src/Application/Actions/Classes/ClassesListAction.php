<?php

namespace App\Application\Actions\Classes;

use App\Application\Actions\Classes\ClassesAction;
use Exception;

class ClassesListAction extends ClassesAction {
    public function action() {
        try{
            $id = $this->resolveArg('id');
            $classes = $this->services->getClassesBySpecies($id);
            $this->logger->info('Find classes by Species id', ['id' => $id]);
            return $this->respondWithData($classes);
        } catch(Exception $e) {
            $this->logger->warning('Classes list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
}