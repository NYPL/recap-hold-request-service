<?php
namespace NYPL\Services;

require __DIR__ . '/vendor/autoload.php';

use Exception;
use NYPL\Services\Controller\RecapHoldRequestController;
use NYPL\Starter\Service;
use NYPL\Starter\Config;
use NYPL\Starter\ErrorHandler;
use NYPL\Starter\SwaggerGenerator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

try {
    Config::initialize(__DIR__ . '/config');

    // Initialize Container.
    $container = (new AppContainerBuilder())->newInstance();
    $app = new Service($container);

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $afterMiddleware = function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type,X-Amz-Date,Authorization,X-Api-Key,X-Amz-Security-Token')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('X-NYPL-Original-Request', $request->getUri()->__toString())
            ->withHeader('X-NYPL-Response-Date', date('c'));
    };
    $app->add($afterMiddleware);

    $app->get("/docs/recap-hold-requests", function (Request $request, Response $response) {
        return SwaggerGenerator::generate(
            [__DIR__ . "/src", __DIR__ . "/vendor/nypl/microservice-starter/src"],
            $response
        );
    });

    $app->post("/api/v0.1/recap/hold-requests", function (Request $request, Response $response) {
        $this->initServices($request, $response);
        $controller = new RecapHoldRequestController($this);
        return $controller->createRecapHoldRequest();
    });

    $app->post("/api/v0.1/recap/cancel-hold-requests", function (Request $request, Response $response) {
        $this->initServices($request, $response);
        $controller = new RecapHoldRequestController($this);
        return $controller->cancelRecapHoldRequest();
    });

    $app->patch("/api/v0.1/recap/cancel-hold-requests/{id}", function (Request $request, Response $response, $parameters) {
        $this->initServices($request, $response);
        $controller = new RecapHoldRequestController($this);
        return $controller->updateCancelRecapHoldRequest($parameters);
    });

    $app->run();

} catch (Exception $exception) {
    ErrorHandler::processShutdownError($exception->getMessage(), $exception);
}
