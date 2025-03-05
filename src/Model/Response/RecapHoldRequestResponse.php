<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\RecapHoldRequest\RecapHoldRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @OA\Schema(title="RecapHoldRequestResponse", type="object")
 *
 * @package NYPL\Services\Model\Response
 */
class RecapHoldRequestResponse extends SuccessResponse
{
    /**
     * @OA\Property
     * @var RecapHoldRequest
     */
    public $data;
}
