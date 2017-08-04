<?php

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Category;

class CategoryController extends ControllerAbstract {
    public function listAction() 
    {
        // Les deux classes CategoryController utilise toutes les deux le même outil, à savoir $app['category.repository']
        $categories = $this->app['category.repository']->findAll();
        
        return $this->render(
            // Le répertoire admin contiendra toutes les vues des admin du site
            'admin/category/list.html.twig',
            [
                // C'est le nom de l'indice et non la variable qui fait le lien avec la view.twig
                // On est donc obligé de passer les variables d'affichage dans un tableau pour leur affecter un indice
                'categories' => $categories
            ]
        );
    }
    
    public function editAction($id = null)
    {
        if(!is_null($id))
        {
            // On va charcher la catégorie en bdd
            //On définit la méthode find() dans CategoryRepository
            $category = $this->app['category.repository']->find($id);
            
            //Si l'objet $category n'a pas été instancié par la Classe Category, on jette une 404 avec abort()
            // abort() arrête l'exécution du script et renvoie une 404
            if(!$category instanceof Category)
            {
                $this->app->abort(404);
            }
        } else {
            // Nouvel objet $categorie donc nouvelle catégorie
            $category = new Category();
        }
        
        // On déclare un tableau vide $errors
        $errors = [];
        
        // $_POST ne sera jamais vide car il contient un indice, une entrée suite à la validation du formulaire avec ['name'] issu de l'input
        if(!empty($_POST))
        {
            $category->setName($_POST['name']);
            
            if(empty($_POST['name']))
            {
                $errors['name'] = 'Le nom de la catégorie est obligatoire';
            }
            elseif (iconv_strlen($_POST['name']) > 20)
            {
                $errors['name'] = 'Le nom ne doit pas faire plus de 20 caractères';
            }
            
            if(empty($errors)) 
            {
            
                // On applique une méthode save, définie dans category.repository qui va se charger de l'enregistrement en bdd 
                $this->app['category.repository']->save($category);

                // On ajoute un message avant la redirection
                $this->addFlashMessage('La rubrique a été enregistrée');

                // Si le formulaire est validé, on redirige l'utilisateur
                return $this->redirectRoute('admin_categories');
            } else {
                $message = '<strong>Le formulaire contient des erreurs</strong>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
         return $this->render(      
        'admin/category/edit.html.twig',
            [
                'category' => $category
            ]
        );
    }
    
    public function deleteAction($id)
    {
        $category = $this->app['category.repository']->find($id);
        
        // if(empty($category)), on peut écrire le !instanceof différemment 
        if(!$category instanceof Category)
        {
            $this->app->abort(404);
        }
        
        // La méthode delete va être définie dans CategoryRepository 
        $this->app['category.repository']->delete($category);
        
        $this->addFlashMessage('La rubrique a été supprimée');
        
        return $this->redirectRoute('admin_categories');
    }
}
