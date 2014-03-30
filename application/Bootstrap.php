<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView()
    {
        // Initialize view
        if (null !== $this->getPluginResource('view')) {
            $view = $this->getPluginResource('view')->getView();
        } else {
            $view = new Zend_View();
        }
        $view->headMeta()->setHttpEquiv(
            'Content-Type', 'text/html; Charset=UTF-8'
        );
        $view->headTitle('PHPBenelux Meetup Raffle');
        $view->headTitle()->setSeparator(' | ');
        $view->headLink()
            ->appendStylesheet('http://fonts.googleapis.com/css?family=Righteous')
            ->appendStylesheet($view->baseUrl('/styles/style.css'));
        $view->headScript()
            ->appendFile('http://code.jquery.com/jquery-1.11.0.min.js');

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
}

