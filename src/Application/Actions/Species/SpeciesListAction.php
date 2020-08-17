<?php

namespace App\Application\Actions\Species;

use App\Application\Actions\Species\SpeciesAction;
use Exception;

final class SpeciesListAction extends SpeciesAction {
    public function action() {
        try{
            $species = $this->speciesService->listSpecies();
            $this->logger->info('List Species');
            return $this->respondWithData($species);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}