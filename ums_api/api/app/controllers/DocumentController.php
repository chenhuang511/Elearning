<?php

class DocumentController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setMainView("documentation");
    }
    public function indexAction(){
        
    }
}