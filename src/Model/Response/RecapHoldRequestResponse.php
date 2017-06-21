<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\RecapHoldRequest\RecapHoldRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @SWG\Definition(title="RecapHoldRequestResponse", type="object")
 *
 * @param NYPL\Services\Model\Response
 */
class RecapHoldRequestResponse extends SuccessResponse
{
    /**
     * @SWG\Property
     * @var RecapHoldRequest
     */
    public $data;
}
