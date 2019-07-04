<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 13/ene/2017
 * Time: 19:39
 */


#require_once __CONTROLLER__ . 'CCartController.class.inc.php';
require_once CLASSES . 'CSession.class.inc.php';
require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';
#require_once __CONTROLLER__ . 'CImagesController.class.inc.php';

$categories_all = Categories::singleton()->getAll();

$app->get('/', function ($request, $response, $args) {
    global $settings, $categories_all;
    return $this->view->render($response, 'main.twig', array('settings' => $settings, 'categories_all' => $categories_all));
});


$app->get('/categoria/{id}', function ($request, $response, $args) {
    global $settings, $categories_all;
    require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';
    require_once __CONTROLLER__ . 'CImagesController.class.inc.php';


    $category = Categories::singleton()->getById($args);

    $result = Products::singleton()->getByCategory($args);
    $result_image = Images::singleton()->getProductsUrl($result);

    $result_category_image = Images::singleton()->getCategoryUrl($category);

    return $this->view->render($response, 'products.twig', array('settings' => $settings,
        'categories_all' => $categories_all,
        'category' => $result_category_image,
        'result' => $result_image
    ));
});

$app->get('/producto/{id}', function ($request, $response, $args) {
    global $settings, $categories_all;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';
    require_once __CONTROLLER__ . 'CImagesController.class.inc.php';

    $result = Products::singleton()->getById($args);
    $result_image = Images::singleton()->getProductUrl($result);

    return $this->view->render($response, 'product.twig', array('settings' => $settings, 'categories_all' => $categories_all, 'result' => $result_image));
});

$app->get('/novedades', function ($request, $response, $args) {
    global $settings, $categories_all;

    return $this->view->render($response, 'novedades.twig', array('settings' => $settings, 'categories_all' => $categories_all));
});

$app->get('/promociones', function ($request, $response, $args) {
    global $settings, $categories_all;

    return $this->view->render($response, 'promociones.twig', array('settings' => $settings, 'categories_all' => $categories_all));
});

$app->get('/quienes-somos', function ($request, $response, $args) {
    global $settings, $categories_all;

    return $this->view->render($response, 'quienes_somos.twig', array('settings' => $settings, 'categories_all' => $categories_all));
});

$app->get('/videos', function ($request, $response, $args) {
    global $settings, $categories_all;

    require_once __CONTROLLER__ . 'CVideosController.class.inc.php';
    require_once __CONTROLLER__ . 'CImagesController.class.inc.php';

    $result = Videos::singleton()->getAll();

    $result_videos = Images::singleton()->getVideosUrl($result);

    return $this->view->render($response, 'videos.twig', array('settings' => $settings, 'categories_all' => $categories_all, 'result' => $result_videos));
});