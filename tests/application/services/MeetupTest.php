<?php

class Application_Service_MeetupTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException DomainException
     */
    public function testServiceStopsWhenNoApikeyWasProvided()
    {
        $mockService = $this->getMock(
            'Application_Service_Meetup',
            array ('retrieveEventMembersFromDatabase','storeAttendees')
        );
        $mockService->expects($this->once())
            ->method('retrieveEventMembersFromDatabase')
            ->will($this->returnValue(null));

        $mockService->getEventMembers(1234);
    }

    public function testServiceRetrievesDataFromWebServiceWhenEventNotInDb()
    {
        $mockService = $this->getMock(
            'Application_Service_Meetup',
            array ('retrieveEventMembersFromDatabase','storeAttendees'),
            array ('apikey')
        );
        $mockService->expects($this->once())
            ->method('retrieveEventMembersFromDatabase')
            ->will($this->returnValue(null));

        $testAdapter = new Zend_Http_Client_Adapter_Test();
        $mockService->getHttpClient()->setAdapter($testAdapter);
        $mockService->getHttpClient()->getAdapter()->setResponse(
            file_get_contents(__DIR__ . '/_files/event-168008172.response')
        );

        $result = $mockService->getEventMembers(168008172);

        $this->assertCount(33, $result,
            'Expected 33 entries in this collection');
        $result->seek(count($result) - 1);
        $this->assertTrue($result->valid());
        $this->assertSame('Michelangelo van Dam', $result->current()->getName(),
            'Expecting Michelangelo as 33rd entry');
    }
}