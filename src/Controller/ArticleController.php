<?php

namespace Controller;

class ArticleController extends ControllerAbstract
{
    public function ficheArticle($id) 
    {
        $article = $this->app['article.repository']->find($id);
        
        return $this->render(
            'article.html.twig',
            [
                'article' => $article
            ]
        );
    }
}
