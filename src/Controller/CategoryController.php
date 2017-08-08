<?php

namespace Controller;

class CategoryController extends ControllerAbstract
{
    public function listAction() 
    {
        // Appel de la méthode findAll() définie dans la classe Repository\CategoryRepository
        // Nécessite qu'elle a été déclarée en service dans src/app.php
        $categories = $this->app['category.repository']->findAll();
        
        // premier paramètre, le chemin de la view, enfant du layout
        return $this->render(
            'category/list.html.twig',
            [
                'categories' => $categories
            ]    
        );
    }
    
    /*
     * Faire la page rubriques qui a pour titre le nom de la rubrique
     * et qui affiche les articles de la rubrique
     * en utilisant la vue article_list.html
     */
    
    public function indexAction($id) 
    {
        $category = $this->app['category.repository']->find($id);
        $articles = $this->app['article.repository']->findByCategory($id);
        
        
        return $this->render(
            'category/article_list.html.twig',
            [
                'category' => $category,
                'articles' => $articles
            ]
        );
    }
}
