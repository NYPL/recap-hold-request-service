<?php
namespace NYPL\Services;

use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\JobManager;
use Ramsey\Uuid\Uuid;

/**
 * Class JobService
 *
 * @package NYPL\Services
 */
class JobService
{
    /**
     * @var string
     */
    public static $jobId;

    /**
     * @param  bool         $useJobManager
     * @throws APIException
     * @return string
     */
    public static function generateJobId(bool $useJobManager = true): string
    {
        if ($useJobManager) {
            try {
                $jobId = JobManager::createJob();
                self::setJobId($jobId);
            } catch (\Exception $exception) {
                APILogger::addError('Not able to communicate with the Jobs Service API.');
                throw new APIException('Jobs Service failed to generate an ID.');
            }
        }

        if (!self::getJobId()) {
            self::generateRandomId();
            APILogger::addInfo('No job started. Job ID returned as UUID.');
        }

        return self::getJobId();
    }

    /**
     * @return string|null
     */
    protected static function getJobId()
    {
        return self::$jobId;
    }

    /**
     * @param string $jobId
     */
    protected static function setJobId(string $jobId)
    {
        self::$jobId = $jobId;
    }

    protected static function generateRandomId()
    {
        self::setJobId(Uuid::uuid4()->toString());
    }
}
