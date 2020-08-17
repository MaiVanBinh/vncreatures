<?php

namespace App\Application\Actions\Classes;

use App\Application\Actions\Classes\ClassesAction;
use Exception;

class ClassesListAction extends ClassesAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $loaiId = null;
            if(array_key_exists('loaiId', $query)) {
                $loaiId = $query['loaiId'];
            }
            $classes = $this->services->getClassesBySpecies($loaiId);
            $this->logger->info('Find classes by Species id', ['loaiId' => $loaiId]);
            return $this->respondWithData($classes);
        } catch(Exception $e) {
            $this->logger->warning('Classes list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
}