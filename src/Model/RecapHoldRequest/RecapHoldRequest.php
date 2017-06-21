<?php
namespace NYPL\Services\Model\RecapHoldRequest;

use NYPL\Starter\Model\LocalDateTime;
use NYPL\Starter\Model\ModelInterface\MessageInterface;
use NYPL\Starter\Model\ModelInterface\ReadInterface;
use NYPL\Starter\Model\ModelTrait\DBCreateTrait;
use NYPL\Starter\Model\ModelTrait\DBReadTrait;

/**
 * @SWG\Definition(title="RecapHoldRequest", type="object")
 *
 * @package NYPL\Services\Model\RecapHoldRequest
 */
class RecapHoldRequest extends NewRecapHoldRequest implements MessageInterface, ReadInterface
{
    use DBCreateTrait, DBReadTrait;

    /**
     * @SWG\Property(example="229")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(example="2016-01-07T02:32:51Z", type="string")
     * @var LocalDateTime
     */
    public $createdDate;

    /**
     * @SWG\Property(example="2016-01-07T02:32:51Z", type="string")
     * @var LocalDateTime
     */
    public $updatedDate;

    /**
     * @return array
     */
    public function getSchema()
    {
        return [
            "name" => "RecapHoldRequest",
            "type" => "record",
            "fields" => [
                ["name" => "id", "type" => "int"],
                ["name" => "trackingId", "type" => "string"],
                ["name" => "patronBarcode", "type" => "string"],
                ["name" => "itemBarcode", "type" => "string"],
                ["name" => "createdDate", "type" => ["string", "null"]],
                ["name" => "updatedDate", "type" => ["string", "null"]],
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

    /**
     * @return string
     */
    public function getSequenceId()
    {
        return 'recap_hold_request_id_seq';
    }

    /**
     * @return array
     */
    public function getIdFields()
    {
        return ['id'];
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return LocalDateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param LocalDateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @param string $createdDate
     *
     * @return LocalDateTime
     */
    public function translateCreatedDate($createdDate = '')
    {
        return new LocalDateTime(LocalDateTime::FORMAT_DATE_TIME_RFC, $createdDate);
    }

    /**
     * @return LocalDateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param LocalDateTime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @param string $updatedDate
     *
     * @return LocalDateTime
     */
    public function translateUpdatedDate($updatedDate = '')
    {
        return new LocalDateTime(LocalDateTime::FORMAT_DATE_TIME_RFC, $updatedDate);
    }
}
