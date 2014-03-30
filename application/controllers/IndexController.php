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
        $meetup = new Application_Service_Meetup(
            $config->meetup->api->key
        );

        $collection = $meetup->getEventMembers($eventId);

        $this->view->assign(array (
            'collection' => $collection,
            'eventId' => $eventId,
        ));
    }

    public function listAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/api.ini');

        $eventId = $this->getRequest()->getParam('event', 115919622);
        $meetup = new Application_Service_Meetup(
            $config->meetup->api->key
        );

        $collection = $meetup->getEventMembers($eventId);
        $this->_helper->json($collection->toArray());
    }

    public function updateAction()
    {
        $eventId = $this->getRequest()->getPost('eventId', 0);
        $memberId = $this->getRequest()->getPost('memberId', 0);
        $winner = $this->getRequest()->getPost('winner', 0);
        $notHere = $this->getRequest()->getPost('notHere', 0);

        if (0 !== $eventId && 0 !== $memberId) {
            $this->getResponse()->setHttpResponseCode(201);
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/api.ini');
            $meetup = new Application_Service_Meetup(
                $config->meetup->api->key
            );
            $meetup->updateWinner($eventId, $memberId, $winner, $notHere);
        }
    }


}





