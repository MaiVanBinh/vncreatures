<?php

namespace App\Application\Actions\Conbine;
use App\Application\Actions\Conbine\ConbineActions;
use Slim\Exception\HttpNotFoundException;
use Exception;

class FetchFilterDataActioncs extends ConbineActions {
    protected function action() {
        try {
            $family =  $this->familyServices->fetchFamilies(null, null, true);
            $order = $this->orderService->fetchOrder(null, null, true);
            $group = $this->groupService->fetchGroup(null, null, true, null);
            $species = $this->speciesServices->fetchSpecies(true);
            $data = [];
            $data['species'] = $species;
            $data['families'] = $family;
            $data['orders'] = $order;
            $data['groups'] = $group;
            return $this->respondWithData($data);
        } catch(Exception $err) {
            $this->logger->warning('Fetch filter error');
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
    }
}