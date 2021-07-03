<?php

namespace App\Application\Actions\Conbine;

use App\Application\Actions\Conbine\ConbineActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Count extends ConbineActions {
    public function action() {
        try {   
            $creaturesNum = $this->creaturesServices->countCreatures();
            $groupsNum = $this->groupService->countGroups();
            $ordersNum = $this->orderService->countOrders();
            $familiesNum = $this->familyServices->countFamilies();

            return $this->respondWithData(["creaturesNum" => $creaturesNum, 'groupsNum' => $groupsNum, 'ordersNum' => $ordersNum, 'familiesNum' => $familiesNum], 200);
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
        
    }
}