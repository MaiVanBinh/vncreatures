<?php

namespace App\Application\Actions\Conbine;
use App\Application\Actions\Conbine\ConbineActions;
use Slim\Exception\HttpNotFoundException;
use Exception;

class FetchFilterDataActioncs extends ConbineActions {
    protected function action() {
        try {
            $family =  $this->familyServices->fetchFamily();
            $order = $this->orderService->fetchOrder();
            $group = $this->groupService->fetchGroup();
            $species = $this->speciesServices->fetchSpecies();
            $data = [];
            $data['species'] = $species;
            $data['family'] = $family;
            $data['order'] = $order;
            $data['group'] = $group;
            return $this->respondWithData($data);
        } catch(Exception $err) {
            $this->logger->warning('Fetch filter error');
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
    }
}