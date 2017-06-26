<?php
namespace NYPL\Services\Controller;

use NYPL\Services\ServiceController;
use NYPL\Services\Model\RecapHoldRequest\RecapHoldRequest;
use NYPL\Services\Model\RecapCancelHoldRequest\RecapCancelHoldRequest;
use NYPL\Services\Model\Response\RecapHoldRequestResponse;
use NYPL\Starter\APIException;
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
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api write:hold_requests readwrite:hold_requests"}
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
            if (!$this->hasWriteRequestScope()) {
                return $this->invalidScopeResponse();
            }

            $data = $this->getRequest()->getParsedBody();

            $holdRequest = new RecapHoldRequest($data);

            $holdRequest->create();

            return $this->getResponse()->withJson(
                new RecapHoldRequestResponse($holdRequest)
            );
        } catch(\Exception $exception) {
            throw new APIException(
                'An error occurred',
                $exception->getMessage(),
                $exception->getCode()
            );
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
     *         description="RecapCancelHoldRequest object",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/NewRecapCancelHoldRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestResponse")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestErrorResponse")
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Generic server error",
     *         @SWG\Schema(ref="#/definitions/RecapHoldRequestErrorResponse")
     *     ),
     *     security={
     *         {
     *             "api_auth": {"openid offline_access api write:hold_requests readwrite:hold_requests"}
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
            if (!$this->hasWriteRequestScope()) {
                return $this->invalidScopeResponse();
            }

            $data = $this->getRequest()->getParsedBody();

            $cancelHoldRequest = new RecapCancelHoldRequest($data);

            $cancelHoldRequest->create();

            return $this->getResponse()->withJson(
                new RecapHoldRequestResponse($cancelHoldRequest)
            );
        } catch(\Exception $exception) {
            throw new APIException(
                'An error occurred',
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }
}
