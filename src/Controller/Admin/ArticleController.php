<?php

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Article;
use Entity\Category;

class ArticleController extends ControllerAbstract 
{
    public function listAction() 
    {
        $articles = $this->app['article.repository']->findAll();
        
        return $this->render(
          'admin/article/list.html.twig',
            [
               'articles' => $articles 
            ]
        );
    }
    
    public function editAction($id = null) 
    {
        if(!is_null($id))
        {
            $article = $this->app['article.repository']->find($id);
        
            if(!$article instanceof Article)
            {
                $this->app->abort(404);
            }
        } else {
            // Nouvel objet $categorie donc nouvelle catégorie par le biais de 
            // la propriété setCategory qui définit la propriété $catégory de l'objet article
            $article = new Article();
            $article->setCategory(new Category());
        }
        
        // On a besoin de la liste des rubriques pour construire le select
        // dans le formulaire
        $categories = $this->app['category.repository']->findAll();
        
        // On déclare un tableau vide $errors
        $errors = [];
        
        // $_POST ne sera jamais vide car il contient un indice, une entrée suite à la validation du formulaire avec ['name'] issu de l'input
        if(!empty($_POST))
        {
            $article->setTitle($_POST['title']);
            $article->setHeader($_POST['header']);
            $article->setContent($_POST['content']);
            
            // on attribue un id à l'objet $category crée avec 
            // $article->setCategory(new Category()), la propriété setId() appartient à l'objet Category;
            $article->getCategory()->setId($_POST['category']);
            
            if(empty($_POST['title']))
            {
                $errors['title'] = 'Le titre de l\'article est obligatoire';
            }
            elseif (iconv_strlen($_POST['title']) > 100)
            {
                $errors['title'] = 'Le titre ne doit pas faire plus de 20 caractères';
            }
            if(empty($_POST['header']))
            {
                $errors['header'] = 'Le chapeau de l\'article est obligatoire';
            }
            if(empty($_POST['content']))
            {
                $errors['content'] = 'Le contenu de l\'article est obligatoire';
            }
            if(empty($_POST['category']))
            {
                $errors['category'] = 'La rubrique est obligatoire';
            }
            
            if(empty($errors)) 
            {
            
                // On applique une méthode save, définie dans category.repository qui va se charger de l'enregistrement en bdd 
                $this->app['article.repository']->save($article);

                // On ajoute un message avant la redirection
                $this->addFlashMessage('L\'article a été enregistré');

                // Si le formulaire est validé, on redirige l'utilisateur
                return $this->redirectRoute('homepage');
            } else {
                $message = '<strong>Le formulaire contient des erreurs</strong>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
         return $this->render(      
        'admin/article/edit.html.twig',
            [
                'article' => $article,
                 'categories' => $categories
            ]
        );
    }
    
    public function deleteAction($id)
    {
        $article = $this->app['article.repository']->find($id);
        
        // if(empty($category)), on peut écrire le !instanceof différemment 
        if(!$article instanceof Article)
        {
            $this->app->abort(404);
        }
        
        // La méthode delete va être définie dans CategoryRepository 
        $this->app['article.repository']->delete($article);
        
        $this->addFlashMessage('L\'article a été supprimé');
        
        return $this->redirectRoute('admin_articles');
    }
}
