<?php
namespace NYPL\Services\Model\RecapHoldRequest;

use NYPL\Services\Model\RecapHoldRequestModel;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * @SWG\Definition(title="NewRecapHoldRequest", type="object")
 *
 * @package NYPL\Services\Model\RecapHoldRequest
 */
class NewRecapHoldRequest extends RecapHoldRequestModel
{
    use TranslateTrait;
}
