<?php

namespace App\Core;

use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Core
{
    private const ROUTES_CONFIG_PATH = __DIR__ . '/../../config/routes.php';

    /**
     * @var array
     */
    private $routes = [];

    private Container $container;
    private ServerRequestInterface $request;
    private AbstractController $controller;

    public function __construct()
    {
        $this->routes = require_once self::ROUTES_CONFIG_PATH;

        $this->container = (new ContainerBuilder())->build();
        $this->request = ServerRequest::fromGlobals();
        $this->controller = new AbstractController();
    }

    /**
     * Request to controller handling
     * @return ResponseInterface
     */
    public function __invoke(): ResponseInterface
    {
        $route = $this
            ->request
            ->getServerParams()['PATH_INFO'];

        // if no route found return 404
        if (!isset($this->routes[$route])) {
            return $this->controller->json([], AbstractController::RESPONSE_NOT_FOUND);
        }

        // TODO: request method type check

        $controllerInfo = $this->routes[$route];
        $method = $controllerInfo['method'];

        $response = $this
            ->container
            ->get($controllerInfo['controller'])
            ->$method($this->request->getQueryParams());

        if (!$response instanceof ResponseInterface) {
            return $this->controller->json([], AbstractController::RESPONSE_NO_CONTENT);
        }

        return $response;
    }
}
