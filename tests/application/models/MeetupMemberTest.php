<?php

class Application_Model_MeetupMemberTest extends PHPUnit_Framework_TestCase
{
    public function testMemberModelCanBePopulated()
    {
        $data = array (
            'response' => 'yes',
            'member_id' => 1234,
            'name' => 'Johny Test',
            'thumb_link' => 'http://www.example.com/img/profile.jpg',
            'event_id' => 5678,
            'winner' => false,
        );
        $member = new Application_Model_MeetupMember($data);

        $this->assertEquals($data, $member->toArray());
    }
}