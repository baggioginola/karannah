<?php
/**
 * Created by PhpStorm.
 * User: mariocue
 * Date: 02/01/2017
 * Time: 10:45 AM
 */

define('ENVIRONMENT', 'test'); # must be production or test.
define('__ROOT__', dirname(__FILE__));
define('PROJECT', 'github/karannah/panel-control/');
define('DOMAIN', 'http://' . $_SERVER['HTTP_HOST'] . '/' . PROJECT);

define('FRAMEWORK', __ROOT__ . '/core/framework/');
define('CLASSES', __ROOT__ . '/core/classes/');

define('__CONTROLLER__', __ROOT__ . '/app/controller/');
define('__MODEL__', __ROOT__ . '/app/model/');
define('__VIEW__', __ROOT__ . '/app/view/');

define('TWIG_TEMPLATES', __ROOT__ . '/app/view');

define('FONTS', DOMAIN . 'includes/public/fonts/');
define('CSS', DOMAIN . 'includes/public/css/');
define('JS', DOMAIN . 'includes/public/js/');
define('FILES', __ROOT__ . '/includes/public/files/');

define('IMG', DOMAIN . 'includes/public/img/');
define('PRODUCT_IMG', __ROOT__ . '/includes/public/imagenes_productos/');
define('CATEGORY_IMG', __ROOT__ . '/includes/public/imagenes_categorias/');
define('PROJECT_IMG', __ROOT__ . '/includes/public/imagenes_proyectos/');
define('BANNER_IMG', __ROOT__ . '/includes/public/imagenes_banner/');

define('BANNER_BIG_IMG', __ROOT__ . '/includes/public/imagenes_banner/banner_big/');
define('BANNER_TOP_IMG', __ROOT__ . '/includes/public/imagenes_banner/banner_top/');
define('BANNER_BRANDS_IMG', __ROOT__ . '/includes/public/imagenes_banner/banner_brands/');

define('DATATABLE',DOMAIN . 'includes/public/dataTable/');
define('FILEINPUT',DOMAIN . 'includes/public/bootstrap-fileinput-master/');

#Database
define('DBHOST', 'localhost');
define('DBNAME', 'karannah');
define('DBUSER', 'root');
define('DBPASS', '');

#Response codes
define('STATUS_SUCCESS', 200);
define('STATUS_FAILURE_CLIENT', 404);
define('STATUS_FAILURE_INTERNAL', 500);

define('MESSAGE_SUCCESS', 'La transaccion fue exitosa');
define('MESSAGE_ERROR', 'La transaccion fue fallida, intente mas tarde');

