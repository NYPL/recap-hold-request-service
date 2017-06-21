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

    /**
     * @return array
     */
    public function getSchema()
    {
        return [
            "name" => "NewRecapHoldRequest",
            "type" => "record",
            "fields" => [
                ["name" => "trackingId", "type" => "string"],
                ["name" => "patronBarcode", "type" => "string"],
                ["name" => "itemBarcode", "type" => "string"],
                ["name" => "owningInstitutionId", "type" => "string"],
                ["name" => "description", "type" => [
                    ["name" => "description", "type" => "record", "fields" => [
                        ["name" => "title", "type" => "string"],
                        ["name" => "author", "type" => "string"],
                        ["name" => "callNumber", "type" => "string"],
                    ]]
                ]],
            ]
        ];
    }
}
