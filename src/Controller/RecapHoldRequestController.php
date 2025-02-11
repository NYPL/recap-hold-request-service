<?php
namespace NYPL\Services\Controller;

use Exception;
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
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RecapHoldRequestController
 *
 * @package NYPL\Services\Controller
 */


/**
 * @OA\Info(title="RecapHoldRequestController", version="1")
 */
class RecapHoldRequestController extends ServiceController
{

    /**
     * @OA\Post(
     *     path="/v0.1/recap/hold-requests",
     *     summary="Create a new ReCAP hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="createRecapHoldRequest",
     *     @OA\RequestBody(
     *         required=true,
     *         description="NewRecapHoldRequest",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/NewRecapHoldRequest")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/RecapHoldRequestResponse")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Generic server error",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api readwrite:hold_request"}
     *         }
     *     }
     * )
     */
    public function createRecapHoldRequest()
    {
        try {
            $data = $this->getRequest()->getParsedBody();
            $recapHoldRequest = new RecapHoldRequest($data);
            APILogger::addInfo('Processing hold request from ReCAP', ['Request ID' => $data['trackingId']]);
            $recapHoldRequest->create();

            return $this->getJsonResponse(
                new RecapHoldRequestResponse($recapHoldRequest)
            );

        } catch (\Exception $exception) {
            $errorType = 'recap-hold-request-error';
            $errorMsg = 'Unable to process ReCAP hold request. ' . $exception->getMessage();

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @OA\Post(
     *     path="/v0.1/recap/cancel-hold-requests",
     *     summary="Cancel a ReCAP hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="cancelRecapHoldRequest",
     *     @OA\RequestBody(
     *         required=true,
     *         description="NewRecapCancelHoldRequest",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/NewRecapCancelHoldRequest")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/RecapCancelHoldRequestResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Generic server error",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api readwrite:hold_request"}
     *         }
     *     }
     * )
     *
     * @return Response
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

            return $this->getJsonResponse(
                new RecapCancelHoldRequestResponse($recapCancelHoldRequest)
            );
        } catch (\Exception $exception) {
            $errorType = 'cancel-recap-hold-request-error';
            $errorMsg = 'Unable to cancel ReCAP hold request. ' . $exception->getMessage();

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @OA\Patch(
     *     path="/v0.1/recap/cancel-hold-requests/{id}",
     *     summary="Update a ReCAP cancel hold request",
     *     tags={"recap-hold-requests"},
     *     operationId="updateCancelRecapHoldRequest",
     *     @OA\RequestBody(
     *         required=true,
     *         description="RecapCancelHoldRequest",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/RecapCancelHoldRequest")
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="ID of ReCAP cancel hold request",     *
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string", format="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/RecapCancelHoldRequestResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Generic server error",
     *         @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api"}
     *         }
     *     }
     * )
     *
     * @param array $args
     *
     * @return Response
     */
    public function updateCancelRecapHoldRequest(array $args)
    {
        try {
            $data = $this->getRequest()->getParsedBody();

            $recapCancelHoldRequest = new RecapCancelHoldRequest();

            CancelRequestLogger::addDebug('Raw PATCH request sent.', [(string)$this->getRequest()->getUri(), $this->getRequest()->getParsedBody()]);
            CancelRequestLogger::addDebug('PATCH request sent.', [(string)$this->getRequest()->getUri(), $data]);

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
                (array)$this->getJsonResponse(new RecapCancelHoldRequestResponse($recapCancelHoldRequest))
            );

            return $this->getJsonResponse(new RecapCancelHoldRequestResponse($recapCancelHoldRequest));
        } catch (\Exception $exception) {
            CancelRequestLogger::addDebug('Exception thrown.', [$exception->getMessage()]);
            $errorType = 'update-cancel-recap-hold-request-error';
            $errorMsg = 'Unable to update canceled recap hold request.';

            return $this->processException($errorType, $errorMsg, $exception, $this->getRequest());
        }
    }

    /**
     * @param string $errorType
     * @param string $errorMessage
     * @param \Exception $exception
     * @param Request    $request
     * @return Response
     */
    protected function processException(string $errorType, string $errorMessage, Exception $exception, Request $request): Response
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

        return $this->getJsonResponse($errorResp)->withStatus($statusCode);
    }
}
