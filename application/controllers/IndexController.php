<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/api.ini');

        $eventId = $this->getRequest()->getParam('event', 115919622);
        $url = sprintf('https://api.meetup.com/2/rsvps?&sign=true&event_id=%d&page=20&key=%s',
            $eventId, $config->meetup->api->key);
        $client = new Zend_Http_Client($url);

        $response = $client->request(Zend_Http_Client::GET);

        $data = json_decode($response->getBody());

        $attendees = array ();
        foreach ($data->results as $member) {
            if ('yes' === $member->response) {
                $attendees[] = $member;
            }
        }


        $this->view->assign(array (
            'response' => $attendees,
            'eventId' => $eventId,
        ));
    }


}

