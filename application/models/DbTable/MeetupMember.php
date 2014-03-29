<?php

class Application_Model_DbTable_MeetupMember extends Zend_Db_Table_Abstract
{

    protected $_name = 'event_member';
    protected $_primary = array ('event_id', 'member_id');

}

