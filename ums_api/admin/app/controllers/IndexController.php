<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->modulename = "home";
        $this->view->activesidebar = "/index/index";
        parent::initialize();
    }

    public function indexAction()
    {

    }

    public function changelanguageAction()
    {
        $lang = $this->request->getQuery("lang","string");
        $this->session->set("lang",$lang);
        $this->cookies->set('wsi_lang', $lang, time() + 30 * 86400);
        $r = $this->request->get("r");
        $r = urldecode($r);
        if(strlen($r)<=0) $r = "index/index";
        $this->response->redirect($r);
    }
}

