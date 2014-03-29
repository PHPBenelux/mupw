<?php

class Application_Model_MeetupMemberMapperTest extends PHPUnit_Framework_TestCase
{
    public function testMeetupMemberCanStoreData()
    {
        $data = array (
            'event_id' => 1234,
            'member_id' => 5678,
            'name' => 'Johny Test',
            'thumb_link' => 'http://www.example.com/img/profile.jpg',
            'response' => 'yes',
            'winner' => null,
        );
        $member = new Application_Model_MeetupMember($data);
        $mapper = new Application_Model_MeetupMemberMapper();
        $mapper->save($member);
    }
}