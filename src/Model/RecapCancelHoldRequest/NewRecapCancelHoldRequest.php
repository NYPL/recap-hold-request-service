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

    /**
     * @return array
     */
    public function getSchema()
    {
        return [
            "name" => "NewRecapCancelHoldRequest",
            "type" => "record",
            "fields" => [
                ["name" => "trackingId", "type" => "string"],
                ["name" => "owningInstitutionId", "type" => "string"],
                ["name" => "patronBarcode", "type" => "string"],
                ["name" => "itemBarcode", "type" => "string"]
            ]
        ];
    }
}
