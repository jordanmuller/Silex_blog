<?php

namespace Controller;

class IndexController extends ControllerAbstract
{ 
    public function indexAction() 
    {
        return $this->render('index.html.twig');
    }
}
