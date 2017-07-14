<?php
namespace NYPL\Services\Model;

use NYPL\Starter\Model;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * Class RecapHoldRequestModel
 *
 * @package NYPL\Services\Model
 */
abstract class RecapHoldRequestModel extends Model
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
     * @SWG\Property()
     * @var ItemDescription
     */
    public $description;

    /**
     * @return string
     */
    public function getTrackingId()
    {
        return $this->trackingId;
    }

    /**
     * @param string $trackingId
     */
    public function setTrackingId($trackingId)
    {
        $this->trackingId = $trackingId;
    }

    /**
     * @return string
     */
    public function getPatronBarcode()
    {
        return $this->patronBarcode;
    }

    /**
     * @param string $patronBarcode
     */
    public function setPatronBarcode($patronBarcode)
    {
        $this->patronBarcode = $patronBarcode;
    }

    /**
     * @return string
     */
    public function getOwningInstitutionId()
    {
        return $this->owningInstitutionId;
    }

    /**
     * @param string $owningInstitutionId
     */
    public function setOwningInstitutionId($owningInstitutionId)
    {
        $this->owningInstitutionId = $owningInstitutionId;
    }

    /**
     * @return string
     */
    public function getItemBarcode()
    {
        return $this->itemBarcode;
    }

    /**
     * @param string $itemBarcode
     */
    public function setItemBarcode($itemBarcode)
    {
        $this->itemBarcode = $itemBarcode;
    }

    /**
     * @param ItemDescription $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return ItemDescription
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array|string $data
     *
     * @return ItemDescription
     */
    public function translateDescription($data)
    {
        return new ItemDescription($data, true);
    }
}
