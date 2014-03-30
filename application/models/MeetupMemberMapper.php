<?php

class Application_Model_MeetupMemberMapper
{
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * @param \Zend_Db_Table_Abstract $dbTable
     * @throws DomainException
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            if (!class_exists($dbTable)) {
                throw new DomainException('Provided DB Gateway doesn\'t exits');
            }
            $dbTable = new $dbTable;
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new DomainException('Invalid DB Gateway provided');
        }
        $this->_dbTable = $dbTable;
    }

    /**
     * @return \Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_MeetupMember');
        }
        return $this->_dbTable;
    }

    public function find(Application_Model_ModelInterface $model, $id)
    {
        $resultSet = $this->getDbTable()->find($id);
        if (!empty ($resultSet)) {
            $model->populate($resultSet->current());
        }
    }

    public function fetchRow(Application_Model_ModelInterface $model, $where = null, $order = null)
    {
        $resultRow = $this->getDbTable()->fetchRow($where, $order);
        if (!empty ($resultRow)) {
            $model->populate($resultRow);
        }
    }

    public function fetchAll(Application_Model_Collection $collection, $where = null, $order = null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where, $order);
        if (!empty ($resultSet)) {
            foreach ($resultSet as $row) {
                $collection->addEntity(new Application_Model_MeetupMember($row));
            }
        }
    }

    public function save(Application_Model_ModelInterface $model)
    {
        try {
            $this->getDbTable()->insert($model->toArray());
        } catch (Zend_Db_Exception $exception) {
            $this->getDbTable()->update($model->toArray(), array (
                'event_id = ?' => $model->getEventId(),
                'member_id = ?' => $model->getMemberId()
            ));
        }
    }
}

