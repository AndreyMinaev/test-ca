<?php

abstract class Rest_Controller extends Zend_Rest_Controller
{
    public function init()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->getResponse()->setHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->getResponse()->setHeader('Accept-Charset', 'utf-8');
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isOptions()) {
            $this->getResponse()->setHeader(
                'Access-Control-Allow-Methods',
                'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE'
            );
        }
    }

    public function headAction()
    {
        $this->getResponse()->setBody(null);
    }

    public function optionsAction()
    {
        $this->getResponse()->setBody(null);
    }
}