<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$loader = new Twig_Loader_String();
$twig = new Twig_Environment($loader);
use Symfony\Component\HttpFoundation\Request;

// デバッグモードを有効にする
$app['debug'] = true;

// templateのパス
$app->before(function () use ($app) {
	$app['twig']->addGlobal('front_template', $app['twig']->loadTemplate('front/template.php'));
	// $app['twig']->addGlobal('admin_template', $app['twig']->loadTemplate('admin/template.php'));
});

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views/',
	'twig.class.path' => __DIR__.'/../vendor/twig/lib',
));

$app->register(new FranMoreno\Silex\Provider\ParisServiceProvider());
$app['idiorm.config'] = array(
	'connection_string' => 'sqlite:'.__DIR__.'/../blog_01.sqlite',
);
$app['paris.model.prefix'] = '';

$app->get('/', function (Request $request) use ($app) {
	$userFactory = $app['paris']->getModel('Articles');
	$results = $userFactory->find_many();

	return $app['twig']->render('front/index.php', array(
		'articles' => $results
	));
});

$app->run();
