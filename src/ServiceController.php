<?php
namespace NYPL\Services;

use NYPL\Services\Model\RecapHoldRequestErrorResponse;
use NYPL\Starter\Controller;
use Slim\Container;

/**
 * Class ServiceController
 *
 * @package NYPL\Services
 */
class ServiceController extends Controller
{
    const READ_REQUEST_SCOPE = 'read:holds';

    const WRITE_REQUEST_SCOPE = 'write:holds';

    const GLOBAL_REQUEST_SCOPE = 'readwrite:holds';

    /**
     * @var Container
     */
    public $container;

    /**
     * Controller constructor.
     *
     * @param \Slim\Container $container
     * @param int $cacheSeconds
     */
    public function __construct(Container $container, $cacheSeconds = 0)
    {
        $this->setResponse($container->get('response'));
        $this->setRequest($container->get('request'));

        $this->addCacheHeader($cacheSeconds);

        $this->initializeContentType();

        $this->initializeIdentityHeader();

        parent::__construct($this->request, $this->response, $cacheSeconds);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function hasReadRequestScope()
    {
        return in_array(self::READ_REQUEST_SCOPE, $this->identityHeader->getScopes()) || $this->hasGlobalRequestScope();
    }

    public function hasWriteRequestScope()
    {
        return in_array(self::WRITE_REQUEST_SCOPE, $this->identityHeader->getScopes()) || $this->hasGlobalRequestScope();
    }

    protected function hasGlobalRequestScope()
    {
        return in_array(self::GLOBAL_REQUEST_SCOPE, $this->identityHeader->getScopes());
    }

    /**
     * @return \Slim\Http\Response
     */
    public function invalidScopeResponse()
    {
        return $this->getResponse()->withJson(
            new RecapHoldRequestErrorResponse(
                '403',
                'invalid-scope',
                'Client does not have sufficient privileges.'
            )
        );
    }
}
