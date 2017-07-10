<?php
namespace NYPL\Services;

use NYPL\Starter\Controller;
use NYPL\Starter\Model\Response\ErrorResponse;
use Slim\Container;

/**
 * Class ServiceController
 *
 * @package NYPL\Services
 */
class ServiceController extends Controller
{
    const READ_REQUEST_SCOPE = 'read:hold_request';

    const WRITE_REQUEST_SCOPE = 'write:hold_request';

    const GLOBAL_REQUEST_SCOPE = 'readwrite:hold_request';

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
    public function __construct(Container $container, int $cacheSeconds = 0)
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
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function hasReadRequestScope()
    {
        return in_array(
            self::READ_REQUEST_SCOPE,
            $this->identityHeader->getScopes()
        )
        || $this->hasGlobalRequestScope();
    }

    public function hasWriteRequestScope()
    {
        return in_array(
            self::WRITE_REQUEST_SCOPE, $this->identityHeader->getScopes()
        )
        || $this->hasGlobalRequestScope();
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
            new ErrorResponse(
                '403',
                'invalid-scope',
                'Client does not have sufficient privileges.'
            )
        )->withStatus(403);
    }
}
