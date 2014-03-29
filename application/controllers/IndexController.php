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


}

