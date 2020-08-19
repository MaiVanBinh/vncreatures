<?php
declare(strict_types=0);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;

class HomeAction {
    private $c;
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }
    public function __invoke($req, ResponseInterface $res)
    {
        $settings = $this->c->get('settings');
        $data = ['name'=>'Binh'];
        $payload = json_encode($settings);
        $res->getBody()->write($payload);
        return $res->withStatus(200)->withHeader('Content-type', 'application/json');
    }
}