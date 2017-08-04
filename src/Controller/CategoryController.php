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
    
   
}
