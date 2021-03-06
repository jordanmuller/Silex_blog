<?php
// Pour utiliser la session de Silex, idem pour Symfony


use Controller\CategoryController;
use Controller\IndexController;
use Repository\CategoryRepository;
use Service\UserManager;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    // On ajoute une globale à twig, 1er arg : nom de la globale, 2eme arg on lui ajoute en valeur l'instance de notre classe UserManager
    // pour éccéder au menu UserManager dans les templates twig
    $twig->addGlobal('user_manager', $app['user.manager']);
    return $twig;
});

/* */
/* Ajout Doctrine DBAL ($app['db'])
 * nécessite l'utilisation par composer
 * composer require doctrine/dbal:~2.2 en ligne de commande dans le répertoire de l'application
 */
$app->register(
    new DoctrineServiceProvider(),
    [
        'db.options' => [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'silex_blog',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8' 
        ]
    ]
);
// Les providers sont dans vendor/silex/silex/provider, on a fait la même chose avec le DoctrineServiceProvider
// cela renregistre $app['session'] prédéfini dans Silex
$app->register(new SessionServiceProvider());

// L'application $app déclenche l'appel au controller avec le nom de la méthode déclarée dans controllers.php (routing, qui fait l epoint d'entrée) placé en indice

/* CONTROLLERS */
/* Front Office  MEMBRE CONTROLLERS, APPEL A LA VUE */
// On ajoute un nouveau service à l'application
// use $app, on va cherche une variable du stock global
$app['index.controller'] = function() use($app) {
    // Dans le ControllerAbstract, on a passé $app dans le constructer, on doit 
    // donc le placer en use ici pour instancier la classe IndexController héritée de ControllerAbstract
    return new IndexController($app);
    // On aurait pu écrire global $app; pour éviter le use $app après la fonction
};

$app['article.controller'] = function() use($app) {
  return new \Controller\ArticleController($app);  
};

$app['category.controller'] = function() use($app) {
    return new CategoryController($app);
};

$app['user.repository'] = function() use($app) {
    return new \Repository\UserRepository($app);  
};

$app['user.controller'] = function() use($app) {
    return new \Controller\UserController($app);
};

/* BACK OFFICE CONTROLLER ADMIN*/
$app['admin.category.controller'] = function() use($app) {
    return new \Controller\Admin\CategoryController($app);
};

$app['admin.article.controller'] = function() use($app) {
  return new \Controller\Admin\ArticleController($app);  
};

/* REPOSITORIES  APPEL A LA BDD */
$app['category.repository'] = function() use($app) {
    return new CategoryRepository($app);
};

$app['article.repository'] = function() use($app) {
  return new \Repository\ArticleRepository($app);  
};

/* AUTRES SERVICES */
$app['user.manager'] = function() use($app) {
    return new UserManager($app['session']);
};


return $app;
