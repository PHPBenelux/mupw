<?php

class Application_Model_MeetupMemberTest extends PHPUnit_Framework_TestCase
{
    public function testMemberModelCanBePopulated()
    {
        $data = array (
            'event_id' => 5678,
            'member_id' => 1234,
            'name' => 'Johny Test',
            'thumb_link' => 'http://www.example.com/img/profile.jpg',
            'response' => 'yes',
            'rsvp_id' => 987654321,
            'mtime' => time(),
        );
        $member = new Application_Model_MeetupMember($data);

        $data['winner'] = 0;
        $data['notHere'] = 0;

        $this->assertEquals($data, $member->toArray());
    }

    public function testMemberModelReturnsDefaultThumbnailWhenNoImageProvided()
    {
        $member = new Application_Model_MeetupMember();

        $this->assertSame(
            Application_Model_MeetupMember::DEFAULT_PROFILE_THUMBNAIL,
            $member->getThumbnail(),
            'Expecting default thumbnail here'
        );
    }
}