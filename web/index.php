<?php
require_once __DIR__.'/../vendor/autoload.php';
require __DIR__.'/model/article.php';

$app = new Silex\Application();
$loader = new Twig_Loader_String();
$twig = new Twig_Environment($loader);
use Symfony\Component\HttpFoundation\Request;

// デバッグモード有効
$app['debug'] = true;

// template
$app->before(function () use ($app) {
	$app['twig']->addGlobal('template', $app['twig']->loadTemplate('front/template.php'));
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

$app->get('/', function () use ($app) {
	$article_model = $app['paris']->getModel('Articles');
	$articles = $article_model->find_many();

	return $app['twig']->render('front/index.php', array(
		'articles' => $articles,
	));
});

$app->get('/create', function () use ($app) {
	$article = $app['paris']->getModel('Articles')->create();
	$article->set($article->_properties);

	return $app['twig']->render('front/create/index.php', array(
		'name' => '新規投稿',
		'article' => $article,
		'title' => 'タイトル',
		'content' => '本文',
	));
});

$app->get('/create/{id}', function ($id) use ($app) {
	$name = $id ? '編集' : '新規投稿';

	$article_model = $app['paris']->getModel('Articles');
	$article = $article_model->find_one($id);

	return $app['twig']->render('front/create/index.php', array(
		'name' => $name,
		'article' => $article,
		'title' => 'タイトル',
		'content' => '本文',
	));
});

// 新規投稿
$app->post('/create/complete', function (Request $request) use ($app) {
	$article = $app['paris']->getModel('Articles')->create();
	$article->set_properties($request);
	$article->set($article->_properties);
	$article->save();

	return $app['twig']->render('front/create/complete.php', array(
		'message' => '新規投稿が完了しました！',
	));
});

// 編集
$app->post('/create/complete/{id}', function (Request $request, $id = null) use ($app) {
	$article_model = $app['paris']->getModel('Articles');
	$article = $article_model->find_one($id);

	$article->set_properties($request);
	$article->set($article->_properties);
	$article->save();

	return $app['twig']->render('front/create/complete.php', array(
		'message' => '編集が完了しました！',
	));
});

$app->run();
