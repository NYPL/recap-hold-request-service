<?php
namespace NYPL\Services\Controller;

use NYPL\Services\CancelRequestLogger;
use NYPL\Services\JobService;
use NYPL\Services\ServiceController;
use NYPL\Services\Model\RecapHoldRequest\RecapHoldRequest;
use NYPL\Services\Model\RecapCancelHoldRequest\RecapCancelHoldRequest;
use NYPL\Services\Model\Response\RecapHoldRequestResponse;
use NYPL\Services\Model\Response\RecapCancelHoldRequestResponse;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Filter;
use NYPL\Starter\Model\Response\ErrorResponse;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RecapHoldRequestController
 *
 * @package NYPL\Services\Controller
 */
class RecapHoldRequestController extends ServiceController
{
    /**
     * @SWG\Post(
     *     path="/v0.1/recap/hold-requests",
     *     summary="Create a new ReCAP hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="createRecapHoldRequest",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="NewRecapHoldRequest",
     *         in="body",
     *         description="",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewRecapHoldRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestResponse")
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api readwrite:hold_request"}
     *         }
     *     }
     * )
     *
     * @return Response
     * @throws APIException
     */
    public function createRecapHoldRequest()
    {
        try {
            $data = $this->getRequest()->getParsedBody();
            $recapHoldRequest = new RecapHoldRequest($data);
            APILogger::addInfo('Processing hold request from ReCAP', ['Request ID' => $data['trackingId']]);
            $recapHoldRequest->create();

            return $this->getResponse()->withJson(
                new RecapHoldRequestResponse($recapHoldRequest)
            );
        } catch (\Exception $exception) {
            $errorType = 'recap-hold-request-error';
            $errorMsg = 'Unable to process ReCAP hold request. ' . $exception->getMessage();

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @SWG\Post(
     *     path="/v0.1/recap/cancel-hold-requests",
     *     summary="Cancel a ReCAP hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="cancelRecapHoldRequest",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="NewRecapCancelHoldRequest",
     *         in="body",
     *         description="",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewRecapCancelHoldRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/RecapCancelHoldRequestResponse")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api readwrite:hold_request"}
     *         }
     *     }
     * )
     *
     * @return Response
     * @throws APIException
     */
    public function cancelRecapHoldRequest()
    {
        try {
            $data = $this->getRequest()->getParsedBody();
            $data['jobId'] = JobService::generateJobId($this->isUseJobService());
            $data['success'] = $data['processed'] = false;

            CancelRequestLogger::addDebug('POST request sent.', $data);

            $recapCancelHoldRequest = new RecapCancelHoldRequest($data);
            $recapCancelHoldRequest->create();

            CancelRequestLogger::addInfo('Processing cancel hold request from ReCAP. (CancelRequestId: ' . $recapCancelHoldRequest->getId() . ')');

            if ($this->isUseJobService()) {
                CancelRequestLogger::addDebug('Initiating job via Job Service API.', ['jobID' => $recapCancelHoldRequest->getJobId()]);
                JobService::beginJob(
                    $recapCancelHoldRequest,
                    'Job started for hold request. (CancelRequestID: ' . $recapCancelHoldRequest->getId() . ')'
                );
            }

            return $this->getResponse()->withJson(
                new RecapCancelHoldRequestResponse($recapCancelHoldRequest)
            );
        } catch (\Exception $exception) {
            $errorType = 'cancel-recap-hold-request-error';
            $errorMsg = 'Unable to cancel ReCAP hold request. ' . $exception->getMessage();

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @SWG\Patch(
     *     path="/v0.1/recap/cancel-hold-requests/{id}",
     *     summary="Update a ReCAP cancel hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="updateCancelRecapHoldRequest",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of ReCAP cancel hold request",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string",
     *         format="string"
     *     ),
     *     @SWG\Parameter(
     *         name="RecapCancelHoldRequest",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/RecapCancelHoldRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/RecapCancelHoldRequestResponse")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api"}
     *         }
     *     }
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function updateCancelRecapHoldRequest(Request $request, Response $response, array $args)
    {
        try {
            $data = $this->getRequest()->getParsedBody();

            $recapCancelHoldRequest = new RecapCancelHoldRequest();

            CancelRequestLogger::addDebug('Raw PATCH request sent.', [(string)$request->getUri(), $request->getParsedBody()]);
            CancelRequestLogger::addDebug('PATCH request sent.', [(string)$request->getUri(), $data]);

            try {
                $recapCancelHoldRequest->validatePatchData((array)$data);
            } catch (APIException $exception) {
                return $this->invalidRequestResponse($exception);
            }

            $recapCancelHoldRequest->addFilter(new Filter('id', $args['id']));
            $recapCancelHoldRequest->read();

            $recapCancelHoldRequest->update(
                $this->getRequest()->getParsedBody()
            );

            // TODO: Change back to debug after testing.
            CancelRequestLogger::addInfo('Cancel request database record updated. (CancelRequestId: ' . $recapCancelHoldRequest->getId() . ')');

            if ($this->isUseJobService()) {
                CancelRequestLogger::addDebug('Updating an existing job.', ['jobID' => $recapCancelHoldRequest->getJobId()]);
                JobService::finishJob($recapCancelHoldRequest);
            }

            CancelRequestLogger::addDebug(
                'PATCH response',
                (array)$this->getResponse()->withJson(new RecapCancelHoldRequestResponse($recapCancelHoldRequest))
            );

            return $this->getResponse()->withJson(new RecapCancelHoldRequestResponse($recapCancelHoldRequest));
        } catch (\Exception $exception) {
            CancelRequestLogger::addDebug('Exception thrown.', [$exception->getMessage()]);
            $errorType = 'update-cancel-recap-hold-request-error';
            $errorMsg = 'Unable to update canceled recap hold request.';

            return $this->processException($errorType, $errorMsg, $exception, $request);
        }
    }

    /**
     * @param string     $errorType
     * @param string     $errorMessage
     * @param \Exception $exception
     * @param Request    $request
     * @return \Slim\Http\Response
     */
    protected function processException($errorType, $errorMessage, \Exception $exception, Request $request)
    {
        $statusCode = 500;
        if ($exception instanceof APIException) {
            $statusCode = $exception->getHttpCode();
        }

        APILogger::addLog(
            $statusCode,
            get_class($exception) . ': ' . $exception->getMessage(),
            [
                $request->getHeaderLine('X-NYPL-Log-Stream-Name'),
                $request->getHeaderLine('X-NYPL-Request-ID'),
                (string) $request->getUri(),
                $request->getParsedBody()
            ]
        );

        if ($exception instanceof APIException) {
            if ($exception->getPrevious()) {
                $exception->setDebugInfo($exception->getPrevious()->getMessage());
            }
            APILogger::addDebug('APIException debug info.', [$exception->debugInfo]);
        }

        $errorResp = new ErrorResponse(
            $statusCode,
            $errorType,
            $errorMessage,
            $exception
        );

        return $this->getResponse()->withJson($errorResp)->withStatus($statusCode);
    }
}
