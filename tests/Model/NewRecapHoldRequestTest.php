<?php

use NYPL\Services\Model\RecapHoldRequest\NewRecapHoldRequest;
use PHPUnit\Framework\TestCase;

class NewRecapHoldRequestTest extends TestCase
{
    public $fakeRecapHoldRequest;

    public function setUp()
    {
        $this->fakeRecapHoldRequest = new class extends NewRecapHoldRequest {
            public function __construct($data = ['owningInstitutionId' => 'PUL'])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    public function testAlwaysReturnsValidInstitutionId()
    {
        $this->assertEquals('PUL', $this->fakeRecapHoldRequest->owningInstitutionId);
        $this->fakeRecapHoldRequest->setOwningInstitutionId('CUL');
        $this->assertEquals('CUL', $this->fakeRecapHoldRequest->owningInstitutionId);
    }
}
