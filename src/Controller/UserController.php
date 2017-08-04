<?php

namespace Controller;

use Entity\User;

class UserController extends ControllerAbstract 
{
    public function registerAction() 
    {
        $user = new User();
        $errors = [];
        
        if(!empty($_POST))
        {
            $user
                ->setLastname($_POST['lastname'])
                ->setFirstname($_POST['firstname'])
                ->setEmail($_POST['email'])
            ;
            
            if(empty($_POST['lastname'])) 
            {
                $errors['lastname'] = 'Le nom est obligatoire';
            }
            elseif(iconv_strlen($_POST['lastname']) > 100)
            {
                $errors['lastname'] = 'Le nom ne doit pas dépasser 100 caractères';
            }
            
            if(empty($_POST['firstname'])) 
            {
                $errors['firstname'] = 'Le prénom est obligatoire';
            }
            elseif(iconv_strlen($_POST['firstname']) > 100)
            {
                $errors['firstname'] = 'Le prénom ne doit pas dépasser 100 caractères';
            }
            
            if(empty($_POST['email'])) 
            {
                $errors['email'] = 'L\'émail est obligatoire';
            } 
            elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'L\'émail n\'est pas valide';
            }
            // On vérifie ici si l'email existe déjà en base grâce à la méthode findByEmail dans UserRepository
            elseif(!empty($this->app['user.repository']->findByEmail($_POST['email'])))
            {
                $errors['email'] = "Cet email est déjà utilisé";
            }
            
            if(empty($_POST['password'])) 
            {
                $errors['password'] = 'Le mot de passe est obligatoire';
            } 
            elseif (!preg_match('/^[a-zA-Z0-9 -]{6,20}$/', $_POST['password']))
            {
                $errors['password'] = 'Le mot de passe doit faire entre 6 et 20 caractères'
                    . ' et ne contenir que des lettres, des chiffres, ou les '
                    . 'caractères _ et -'
                ;
            }
            
            if(empty($_POST['password_confirm'])) 
            {
                $errors['password_confirm'] = 'Vous devez confirmer votre mot de passe';
            } 
            elseif($_POST['password_confirm'] != $_POST['password'])
            {
                $errors['password-confirm'] = 'La confirmation n\'est pas identique au mot de passe';
            }
            
            if(empty($errors))
            {
                // On applique une méthode save, définie dans category.repository qui va se charger de l'enregistrement en bdd 
                $this->app['user.repository']->save($user);

                // On ajoute un message avant la redirection
                $this->addFlashMessage('Vous avez été enregistré');

                // Si le formulaire est validé, on redirige l'utilisateur
                return $this->redirectRoute('admin_articles');
            } else {
                $message = '<strong>Le formulaire contient des erreurs</strong>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
        return $this->render(
            'user/register.html.twig',
            [
                'user' => $user
            ]
        );
    }
}
