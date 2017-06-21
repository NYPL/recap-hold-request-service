<?php
namespace NYPL\Services\Model;

use NYPL\Starter\Model;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * Class RecapCancelHoldRequestModel
 *
 * @package NYPL\Services\Model
 */
abstract class RecapCancelHoldRequestModel extends Model
{
    use TranslateTrait;

    /**
     * @SWG\Property(example="901bdd1d-bd8f-4310-ba31-7f13a55877fd")
     * @var string
     */
    public $trackingId;

    /**
     * @SWG\Property(example="34333000000000")
     * @var string
     */
    public $itemBarcode;

    /**
     * @SWG\Property(example="23333000000000")
     * @var string
     */
    public $patronBarcode;

    /**
     * @SWG\Property(example="PUL")
     * @var string
     */
    public $owningInstitutionId;

    /**
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    /**
     * @param string $trackingId
     */
    public function setTrackingId(string $trackingId)
    {
        $this->trackingId = $trackingId;
    }

    /**
     * @return string
     */
    public function getPatronBarcode(): string
    {
        return $this->patronBarcode;
    }

    /**
     * @param string $patronBarcode
     */
    public function setPatronBarcode(string $patronBarcode)
    {
        $this->patronBarcode = $patronBarcode;
    }

    /**
     * @return string
     */
    public function getOwningInstitutionId(): string
    {
        return $this->owningInstitutionId;
    }

    /**
     * @param string $owningInstitutionId
     */
    public function setOwningInstitutionId(string $owningInstitutionId)
    {
        $this->owningInstitutionId = $owningInstitutionId;
    }

    /**
     * @return string
     */
    public function getItemBarcode(): string
    {
        return $this->itemBarcode;
    }

    /**
     * @param string $itemBarcode
     */
    public function setItemBarcode(string $itemBarcode)
    {
        $this->itemBarcode = $itemBarcode;
    }
}
