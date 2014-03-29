<?php

class Application_Service_Meetup
{
    const API_MEETUP_URI = 'https://api.meetup.com/2';

    /**
     * @var Zend_Http_Client The HTTP Client to make requests
     */
    protected $_httpClient;
    /**
     * @var string The API Key received from Meetup.com
     */
    protected $_apiKey;

    /**
     * Constructor class allowing us to feed the service with API key
     * and HTTP client
     *
     * @param null|string $apiKey This is the Meetup.com API key
     * @param null|Zend_Http_Client $httpClient
     */
    public function __construct($apiKey = null, $httpClient = null)
    {
        if (null !== $apiKey) {
            $this->setApiKey($apiKey);
        }
        if (null !== $httpClient && $httpClient instanceof Zend_Http_Client) {
            $this->setHttpClient($httpClient);
        }
    }
    /**
     * @param \Zend_Http_Client $httpClient
     * @return Application_Service_Meetup
     */
    public function setHttpClient(Zend_Http_Client $httpClient)
    {
        $this->_httpClient = $httpClient;
        return $this;
    }

    /**
     * @return \Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (null === $this->_httpClient) {
            $this->setHttpClient(new Zend_Http_Client());
        }
        return $this->_httpClient;
    }

    /**
     * @param string $apiKey
     * @return Application_Service_Meetup
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     * @throws DomainException
     */
    public function getApiKey()
    {
        if (null === $this->_apiKey) {
            throw new DomainException(
                'API Key is missing, please get one at https://secure.meetup.com/meetup_api/key/');
        }
        return $this->_apiKey;
    }

    /**
     * Retrieve all attendees at an event
     *
     * @param $eventId
     * @return Application_Model_Collection
     */
    public function getEventMembers($eventId)
    {
        $collection = $this->retrieveEventMembersFromDatabase($eventId);
        if (0 === count($collection)) {
            $collection = $this->retrieveEventMembersFromMeetup($eventId);
            $this->storeAttendees($collection);
        }
        return $collection;
    }

    /**
     * Fetch a collection for a given event from DB
     *
     * @param $eventId
     * @return Application_Model_Collection
     */
    public function retrieveEventMembersFromDatabase($eventId)
    {
        $mapper = new Application_Model_MeetupMemberMapper();
        $collection = new Application_Model_Collection();
        $mapper->fetchAll(
            $collection,
            array ('event_id = ?' => $eventId),
            array ('mtime DESC')
        );
        return $collection;
    }

    /**
     * Fetch a collection for a given event from Meetup.com API
     *
     * @param $eventId
     * @return Application_Model_Collection
     */
    public function retrieveEventMembersFromMeetup($eventId)
    {
        $uri = sprintf('%s/rsvps?sign=true&event_id=%d&page=100&key=%s',
            self::API_MEETUP_URI,
            $eventId,
            $this->getApiKey()
        );
        $this->getHttpClient()->setUri($uri);
        $response = $this->getHttpClient()->request(Zend_Http_Client::GET);
        $data = json_decode($response->getBody());

        $collection = new Application_Model_Collection();
        foreach($data->results as $member) {
            if ('yes' !== $member->response) continue;
            $collection->addEntity(
                new Application_Model_MeetupMember(
                    array (
                        'event_id' => $member->event->id,
                        'member_id' => $member->member->member_id,
                        'name' => $member->member->name,
                        'thumb_link' => (isset ($member->member_photo) ? $member->member_photo->thumb_link : null),
                        'response' => $member->response,
                        'rsvp_id' => $member->rsvp_id,
                        'mtime' => $member->mtime,
                    )
                )
            );
        }
        return $collection;
    }

    /**
     * Store the attendees of a Meetup.com event into the database
     *
     * @param Application_Model_Collection $attendees
     */
    public function storeAttendees(Application_Model_Collection $attendees)
    {
        $mapper = new Application_Model_MeetupMemberMapper();
        foreach ($attendees as $member) {
            $mapper->save($member);
        }
    }
}