<?php

namespace App\Application\Actions\Creatures;

use Slim\Exception\HttpNotFoundException;
use App\Application\Actions\Creatures\CreaturesActions;
use Exception;

class CreaturesFindByIdAction extends CreaturesActions {
    public function action() {
        try{
            $id = $this->resolveArg('id');
            $creatures = $this->services->getCreatureById($id);
            // $creatures = ['id' => $id];
            return $this->respondWithData($creatures);
        } catch(Exception $e) {
            throw new HttpNotFoundException($this->request,$e->getMessage());
        }
    }
}