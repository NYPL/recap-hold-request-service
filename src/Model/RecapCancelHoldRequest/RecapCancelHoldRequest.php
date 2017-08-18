<?php
namespace NYPL\Services\Model\RecapCancelHoldRequest;

use NYPL\Starter\Config;
use NYPL\Starter\Model\LocalDateTime;
use NYPL\Starter\Model\ModelInterface\MessageInterface;
use NYPL\Starter\Model\ModelInterface\ReadInterface;
use NYPL\Starter\Model\ModelTrait\DBCreateTrait;
use NYPL\Starter\Model\ModelTrait\DBReadTrait;

/**
 * @SWG\Definition(title="RecapCancelHoldRequest", type="object")
 *
 * @package NYPL\Services\Model\RecapCancelHoldRequest
 */
class RecapCancelHoldRequest extends NewRecapCancelHoldRequest implements MessageInterface, ReadInterface
{
    use DBCreateTrait, DBReadTrait;

    /**
     * @SWG\Property(example="229")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(example="991873slx938")
     * @var string
     */
    public $jobId;

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
     * Returns a valid Avro 1.8.1 schema structure.
     *
     * @return array
     */
    public function getSchema()
    {
        return [
            "name"   => "RecapCancelHoldRequest",
            "type"   => "record",
            "fields" => [
                ["name" => "id", "type" => "int"],
                ["name" => "jobId", "type" => ["null", "string"]],
                ["name" => "trackingId", "type" => ["null", "string"]],
                ["name" => "patronBarcode", "type" => ["null", "string"]],
                ["name" => "itemBarcode", "type" => ["null", "string"]],
                ["name" => "owningInstitutionId", "type" => ["null", "string"]],
                ["name" => "createdDate", "type" => ["null", "string"]],
                ["name" => "updatedDate", "type" => ["null", "string"]]
            ]
        ];
    }

    /**
     * @return string
     */
    public function getSequenceId()
    {
        return 'recap_cancel_hold_request_id_seq';
    }

    /**
     * @return array
     */
    public function getIdFields()
    {
        return ['id'];
    }

    public function getStreamName()
    {
        return Config::get('CANCEL_REQUEST_STREAM');
    }

    /**
     * @param int|string $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
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
