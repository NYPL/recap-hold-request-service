<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\RecapCancelHoldRequest\RecapCancelHoldRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @SWG\Definition(title="RecapCancelHoldRequestResponse", type="object")
 *
 * @param NYPL\Services\Model\Response
 */
class RecapCancelHoldRequestResponse extends SuccessResponse
{
    /**
     * @SWG\Property
     * @var RecapCancelHoldRequest
     */
    public $data;
}
