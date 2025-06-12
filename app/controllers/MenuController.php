<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

class MenuController extends Controller{
    // Constructor
	public function __construct()
	{

	}

	public function actionInicio()
	{	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		$path = static::path();
		Response::render($this->viewDir(__NAMESPACE__), "inicio", [
			"title" => $this->title . "Inicio",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
	public function actionMenu()
{
    $productoModel = new \app\models\ProductoModel();

    $pizzas = $productoModel->obtenerComidaActiva();   // 'comida' -> se muestra como Pizzas
    $bebidas = $productoModel->obtenerBebidasActivas(); // 'bebida' -> se muestra como Bebidas

    $head = \app\controllers\SiteController::head();
    $nav = \app\controllers\SiteController::nav();
    $footer = \app\controllers\SiteController::footer();

    \Response::render('menu/', 'menu', [
        'title' => 'Carta Digital',
        'head' => $head,
        'nav' => $nav,
        'footer' => $footer,
        'pizzas' => $pizzas,
        'bebidas' => $bebidas
    ]);
}
}