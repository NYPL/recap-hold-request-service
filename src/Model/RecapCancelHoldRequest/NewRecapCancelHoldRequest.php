<?php
namespace NYPL\Services\Model\RecapCancelHoldRequest;

use NYPL\Services\Model\RecapCancelHoldRequestModel;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * @SWG\Definition(title="NewRecapCancelHoldRequest", type="object")
 *
 * @package NYPL\Services\Model\RecapCancelHoldRequest
 */
class NewRecapCancelHoldRequest extends RecapCancelHoldRequestModel
{
    use TranslateTrait;
}
