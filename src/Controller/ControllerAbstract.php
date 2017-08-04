<?php

namespace Controller;

use Silex\Application;

abstract class ControllerAbstract 
{
    // L apropriété $app va contenir notre appli $app est un objet de la classe Apllication
    protected $app;
    
    // La propriété va contenir un accès direct à twig, c'est un objet de la classe \Twig_Environment 
    protected $twig;
    
    
    // On redéfinit une propriété private $session qui sera passée dans le constructeur pour déclencher une session utilisateur
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;
  
    
    public function __construct(Application $app) 
    {
        $this->app = $app;
        $this->twig = $app['twig'];
        // 
        $this->session = $app['session'];
    }
    
    /**
     * 
     * @param string $view
     * @param array $parameters
     * return string
     */
    // Obliger de déclarer les prameters sous forme d'array
    public function render($view, array $parameters = []) 
    {
        return $this->twig->render($view, $parameters);
    }
    
    /**
     * 
     * @param string $routeName
     * @param array $parameters, pour les id ou toute variable que l'on passe dans l'URL
     *  (voir slug, lissage d'URL, on remplace les caractères spéciaux et les expaces par des tirets ou on optimise le référencement=
     * @return \Symphony\Component\HttpFoundation\RedirecteResponse
     */
    public function redirectRoute($routeName, array $parameters = []) 
    {
        return $this->app->redirect(
            $this->app['url_generator']->generate($routeName, $parameters)
         );
        
    }
    
    // $type définit si c'est un message de succès, on aurait pu mettre erreur
    public function addFlashMessage($message, $type = 'success')
    {
        // getFlashBag() est prédéfini dans Symphony
        $this->session->getFlashBag()->add($type, $message);
    }

}
