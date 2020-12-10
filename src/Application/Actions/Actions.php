<?php

namespace App\Application\Actions;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Exception\HttpBadRequestException;
use Exception;

abstract class Actions {
    /**
     * @var LoggerInterface
     */

    protected $logger;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        try{
            return $this->action();
        } catch(Exception $e) {
            throw $e;
        }
        
    }
    
    /**
     * @return Response
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action();

    /**
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param  array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }

    protected function getFilterNameAndValue($array) {
        $filter = null;
        if(array_key_exists('id', $array)) {
            $filter = ['name' => 'id', 'value' =>  intval($array['id'])];
        }
        if(array_key_exists('name_vn', $array)) {
            $value = html_entity_decode($array["name_vn"], ENT_NOQUOTES, 'UTF-8');
            $filter = ['name' => 'name_vn', 'value' =>  $value];
        }
        if(array_key_exists('name_latin', $array)) {
            $filter = ['name' => 'name_latin', 'value' =>  $array['name_latin']];
        }
        if(array_key_exists('species', $array)) {
            $filter = ['name' => 'species', 'value' =>  $array['species']];
        }
        if(array_key_exists('created_by_name', $array)) {
            $filter = ['name' => 'created_by', 'value' =>  $array['created_by_name']];
        }
        if(array_key_exists('updated_by_name', $array)) {
            $filter = ['name' => 'updated_by', 'value' =>  $array['updated_by_name']];
        }
        return $filter;
    }
}