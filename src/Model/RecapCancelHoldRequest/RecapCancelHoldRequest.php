<?php
namespace NYPL\Services\Model\RecapCancelHoldRequest;

use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\LocalDateTime;
use NYPL\Starter\Model\ModelInterface\MessageInterface;
use NYPL\Starter\Model\ModelInterface\ReadInterface;
use NYPL\Starter\Model\ModelTrait\DBCreateTrait;
use NYPL\Starter\Model\ModelTrait\DBReadTrait;
use NYPL\Starter\Model\ModelTrait\DBUpdateTrait;

/**
 * @SWG\Definition(title="RecapCancelHoldRequest", type="object")
 *
 * @package NYPL\Services\Model\RecapCancelHoldRequest
 */
class RecapCancelHoldRequest extends NewRecapCancelHoldRequest implements MessageInterface, ReadInterface
{
    use DBCreateTrait, DBReadTrait, DBUpdateTrait;

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
     * @SWG\Property(example=true)
     * @var bool
     */
    public $processed;

    /**
     * @SWG\Property(example=false)
     * @var bool
     */
    public $success;

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
                ["name" => "processed", "type" => "boolean"],
                ["name" => "success", "type" => "boolean"],
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
     * @return boolean
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * @param boolean $processed
     */
    public function setProcessed(bool $processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return boolean
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
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

    /**
     * @throws \NYPL\Starter\APIException
     */
    public function validatePatchData(array $data)
    {
        APILogger::addDebug('Validating PATCH request payload.', $data);

        if (!is_bool($data['success']) || !is_bool($data['processed'])) {
            APILogger::addError('Success and processed flags must be boolean values.');
            throw new APIException('Success and processed must be boolean values.', null, 0, null, 400);
        }

        APILogger::addDebug('PATCH request payload validation passed.');
    }
}
