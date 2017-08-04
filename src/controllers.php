<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

/* FRONT-OFFICE */
$app
    ->get('/', 'index.controller:indexAction')
    ->bind('homepage')
;

$app
    ->get('/rubrique/liste', 'category.controller:listAction')
    ->bind('category_list')
;
        
$app
    ->match('/utilisateur/inscription', 'user.controller:registerAction')
    ->bind('user_register')
;

/* ROUTE ADMIN */
// On crée un groupe de routes grâce à l'indice ['controllers-factory'] prédéfini par Silex
$admin = $app['controllers_factory'];

// Toutes les routes définies par $admin
// auront une URL commençant par /admin sans avoir à l'ajouter dans chaque route
// grâce à la fonction mount()
$app->mount('/admin', $admin);


/* BACK OFFICE */
// L'URL de cette route est /admin/rubriques
/* RUBRIQUE */
$admin
    ->get('/rubriques/', 'admin.category.controller:listAction')
    ->bind('admin_categories')
;

$admin
    // match(car la page va contenir un formulaire POST et l'URL pour y accéder est en GET)
        //la route match à la fois /rubrique/edition et /rubrique/edition/1
    ->match('/rubrique/edition/{id}', 'admin.category.controller:editAction')
    ->value('id', null) // value() donne une valeur par défaut au paramètre URL id
    ->bind('admin_category_edit')
;

$admin
    ->get('/rubrique/suppression/{id}', 'admin.category.controller:deleteAction')
    ->assert('id', '\d+')
    ->bind('admin_category_delete')
;

/* ARTICLE */
$admin
    ->get('/articles/', 'admin.article.controller:listAction')
    ->bind('admin_articles')
;

$admin
    ->match('/article/edition/{id}', 'admin.article.controller:editAction')
    ->value('id', null)
    ->bind('admin_article_edit')
;

$admin
    ->get('/article/suppression/{id}', 'admin.article.controller:deleteAction')
    ->assert('id', '\d+')
    ->bind('admin_article_delete')
;

/*
 * Créer la partie admin pour les aticles :
 * - créer le Controller Admin\ArticleController qui hérite de ControllerAbstract
 * le définir en service dans src/app.php
 * y ajouter la méthode listeAction() qui va rendre la vue admin/article/list.html.twig
 * créer la vue
 * créer la route qui pointe sur l'action de controller
 * ajouter un lien vers cette route dans la navbar admin
 * Créer l'entity Article et le repository ArticleRepository qui hérite de RepositoryAbstract
 * Déclarer le repository en service dans src/app.php
 * remplir la méthode listAction() en utilisant ArticleRepository
 * Faire l'affichage en tableau HTML dans la vue
 */

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
