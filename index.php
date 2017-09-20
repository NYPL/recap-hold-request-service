<?php
namespace NYPL\Services;

use NYPL\Starter\Service;
use NYPL\Starter\Config;
use NYPL\Starter\ErrorHandler;
use NYPL\Services\Controller\RecapHoldRequestController;

require __DIR__ . '/vendor/autoload.php';

try {
    Config::initialize(__DIR__);

    $container = new ServiceContainer();

    $service = new Service($container);

    $service->get('/docs/recap-hold-requests', Swagger::class);

    $service->post(
        '/api/v0.1/recap/hold-requests',
        RecapHoldRequestController::class . ':createRecapHoldRequest'
    );

    $service->post(
        '/api/v0.1/recap/cancel-hold-requests',
        RecapHoldRequestController::class . ':cancelRecapHoldRequest'
    );

    $service->patch(
        '/api/v0.1/recap/cancel-hold-requests/{id}',
        RecapHoldRequestController::class . ':updateCancelRecapHoldRequest'
    );

    $service->run();
} catch (\Exception $exception) {
    ErrorHandler::processShutdownError($exception->getMessage(), $exception);
}
