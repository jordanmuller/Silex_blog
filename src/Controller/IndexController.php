<?php

namespace Controller;

class IndexController extends ControllerAbstract
{ 
    public function indexAction() 
    {
        $articles = $this->app['article.repository']->findAll();
        
        return $this->render(
                'index.html.twig',
                ['articles' =>$articles]
        );
    }
}
