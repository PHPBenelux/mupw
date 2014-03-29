<?php

interface Application_Model_ModelInterface
{
    /**
     * Populates the model with data
     *
     * @param $data
     */
    public function populate($data);

    /**
     * Converts the model into an array
     *
     * @return array
     */
    public function toArray();
}

