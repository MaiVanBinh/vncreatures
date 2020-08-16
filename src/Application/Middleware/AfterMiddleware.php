<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AfterMiddleware {
    public function __invoke($request, $handler)
    {
        $response = $handler->handle($request);
        // $response = new Response();
        $response->getBody()->write('AFTER');
        return $response;
    }
}
