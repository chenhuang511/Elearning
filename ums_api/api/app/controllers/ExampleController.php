<?php
use \Phalcon\Mvc\View as PhView;
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
        $this->initResponse();
        $this->setPayload(array("name"=>"Vi","age"=>"10"));
        return $this->render();
    }
}