<?php
declare(strict_types=0);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeAction {
    public function __invoke($req, ResponseInterface $res)
    {
        $data = ['name'=>'Binh'];
        $payload = json_encode($data);
        $res->getBody()->write($payload);
        return $res->withStatus(200)->withHeader('Content-type', 'application/json');
    }
}