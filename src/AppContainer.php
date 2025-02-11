<?php

namespace NYPL\Services;

use Aura\Di\Injection\InjectionFactory;
use Closure;
use NYPL\Starter\DefaultContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AppContainer
 *
 * @package NYPL\OAuthApp
 */
class AppContainer extends DefaultContainer
{
    public Closure $logger;

    public function __construct(
        InjectionFactory $injectionFactory,
        ContainerInterface $delegateContainer = null
    ) {
        parent::__construct($injectionFactory, $delegateContainer);
        $this->settings["displayErrorDetails"] = true;
    }
}
