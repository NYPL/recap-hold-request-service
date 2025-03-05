<?php
namespace NYPL\Services\Model\Response;

use NYPL\Services\Model\RecapCancelHoldRequest\RecapCancelHoldRequest;
use NYPL\Starter\Model\Response\SuccessResponse;

/**
 * @OA\Schema(title="RecapCancelHoldRequestResponse", type="object")
 *
 * @package NYPL\Services\Model\Response
 */
class RecapCancelHoldRequestResponse extends SuccessResponse
{
    /**
     * @OA\Property
     * @var RecapCancelHoldRequest
     */
    public $data;
}
