<?php
namespace NYPL\Services\Controller;

use NYPL\Services\JobService;
use NYPL\Services\ServiceController;
use NYPL\Services\Model\RecapHoldRequest\RecapHoldRequest;
use NYPL\Services\Model\RecapCancelHoldRequest\RecapCancelHoldRequest;
use NYPL\Services\Model\Response\RecapHoldRequestResponse;
use NYPL\Services\Model\Response\RecapCancelHoldRequestResponse;
use NYPL\Starter\APIException;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;
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
            $holdRequest = new RecapHoldRequest($data);
            $holdRequest->create();

            return $this->getResponse()->withJson(
                new RecapHoldRequestResponse($holdRequest)
            );
        } catch (\Exception $exception) {
            $errorResp = new ErrorResponse(
                500,
                'create-recap-hold-request-error',
                'An error occurred while trying to process your request.',
                $exception
            );
            $errorResp->setError($errorResp->translateException($exception));
            return $this->getResponse()->withJson($errorResp)->withStatus(500);
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
            $data['jobId'] = JobService::generateJobId(Config::get('USE_JOB_SERVICE'));

            $cancelHoldRequest = new RecapCancelHoldRequest($data);
            $cancelHoldRequest->create();

            return $this->getResponse()->withJson(
                new RecapCancelHoldRequestResponse($cancelHoldRequest)
            );
        } catch (\Exception $exception) {
            $errorResp = new ErrorResponse(
                500,
                'cancel-recap-hold-request-error',
                'An error occurred while trying to process your request.',
                $exception
            );
            $errorResp->setError($errorResp->translateException($exception));
            return $this->getResponse()->withJson($errorResp)->withStatus(500);
        }
    }
}
