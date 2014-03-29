<?php

class Application_Model_MeetupMember implements Application_Model_ModelInterface
{
    const DEFAULT_PROFILE_THUMBNAIL = '/images/yoda_profile.jpg';
    /**
     * @var int The ID given to this member by Meetup.com
     */
    protected $_memberId;
    /**
     * @var int The ID of the Meetup.com event
     */
    protected $_eventId;
    /**
     * @var string The name of the Meetup.com member
     */
    protected $_name;
    /**
     * @var string The URI of the member image on Meetup.com
     */
    protected $_thumbnail;
    /**
     * @var string Meetup.com RSVP status of this member
     */
    protected $_response;
    /**
     * @var int The registration ID for this member
     */
    protected $_rsvpId;
    /**
     * @var int The last modification time of this member for this event
     */
    protected $_mtime;
    /**
     * @var bool If this member was selected as winner or not
     */
    protected $_winner;
    /**
     * @var bool If the winning attendee was not at the event
     */
    protected $_notHere;
    /**
     * @param array|Zend_Db_Row $data
     */
    public function __construct($data = null)
    {
        if (null !== $data) {
            $this->populate($data);
        }
    }
    /**
     * @param int $eventId
     * @return Application_Model_MeetupMember
     */
    public function setEventId($eventId)
    {
        $this->_eventId = $eventId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEventId()
    {
        return $this->_eventId;
    }

    /**
     * @param int $memberId
     * @return Application_Model_MeetupMember
     */
    public function setMemberId($memberId)
    {
        $this->_memberId = $memberId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMemberId()
    {
        return $this->_memberId;
    }

    /**
     * @param string $name
     * @return Application_Model_MeetupMember
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $response
     * @return Application_Model_MeetupMember
     */
    public function setResponse($response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param int $rsvpId
     * @return Application_Model_MeetupMember
     */
    public function setRsvpId($rsvpId)
    {
        $this->_rsvpId = $rsvpId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRsvpId()
    {
        return $this->_rsvpId;
    }

    /**
     * @param int $mtime
     * @returns Application_Model_MeetupMember
     */
    public function setMtime($mtime)
    {
        $this->_mtime = $mtime;
        return $this;
    }

    /**
     * @return int
     */
    public function getMtime()
    {
        return $this->_mtime;
    }

    /**
     * @param string $thumbnail
     * @return Application_Model_MeetupMember
     */
    public function setThumbnail($thumbnail)
    {
        $this->_thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        if (null === $this->_thumbnail) {
            $this->setThumbnail(self::DEFAULT_PROFILE_THUMBNAIL);
        }
        return $this->_thumbnail;
    }

    /**
     * @param boolean $winner
     * @return Application_Model_MeetupMember
     */
    public function setWinner($winner)
    {
        $this->_winner = $winner;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isWinner()
    {
        return $this->_winner;
    }

    /**
     * @param boolean $notHere
     */
    public function setNotHere($notHere)
    {
        $this->_notHere = $notHere;
    }

    /**
     * @return boolean
     */
    public function isNotHere()
    {
        return $this->_notHere;
    }

    /**
     * @param array|Zend_Db_Row $resultRow The object as result
     */
    public function populate($resultRow)
    {
        if (is_array($resultRow)) {
            $resultRow = new ArrayObject($resultRow, ArrayObject::ARRAY_AS_PROPS);
        }
        $this->_safeSet($resultRow, 'response', 'setResponse')
            ->_safeSet($resultRow, 'name', 'setName')
            ->_safeSet($resultRow, 'member_id', 'setMemberId')
            ->_safeSet($resultRow, 'thumb_link', 'setThumbnail')
            ->_safeSet($resultRow, 'event_id', 'setEventId')
            ->_safeSet($resultRow, 'winner', 'setWinner')
            ->_safeSet($resultRow, 'rsvp_id', 'setRsvpId')
            ->_safeSet($resultRow, 'mtime', 'setMtime')
            ->_safeSet($resultRow, 'notHere', 'setNotHere');
    }

    /**
     * Convert object into an array
     *
     * @return array
     */
    public function toArray()
    {
        return array (
            'event_id' => $this->getEventId(),
            'member_id' => $this->getMemberId(),
            'name' => $this->getName(),
            'thumb_link' => $this->getThumbnail(),
            'response' => $this->getResponse(),
            'rsvp_id' => $this->getRsvpId(),
            'mtime' => $this->getMtime(),
            'winner' => $this->isWinner() ? 1 : 0,
            'notHere' => $this->isNotHere() ? 1 : 0,
        );
    }

    protected function _safeSet($row, $key, $method)
    {
        if (isset ($row->$key)) {
            $this->$method($row->$key);
        }
        return $this;
    }
}

