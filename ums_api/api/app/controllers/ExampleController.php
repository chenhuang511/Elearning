<?php
class ExampleController extends RESTController
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * The index action
     */
    public function indexAction()
    {
        $this->setPayload(array("name"=>"Vi","age"=>"10"));        
        echo $this->render();
    }
}