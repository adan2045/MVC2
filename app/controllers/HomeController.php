<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

class HomeController extends Controller
{

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


	public function actionCrear()
	{
		$head = SiteController::head();
		$nav = SiteController::nav();
		$path = static::path();

		Response::render($this->viewDir(__NAMESPACE__), "crear", [
			"title" => $this->title . "Crear usuario",
			"head" => $head,
			"nav" => $nav,
		]);
	}


	public function action404()
	{
		$head = SiteController::head();
		Response::render($this->viewDir(__NAMESPACE__), "404", [
			"title" => $this->title . ' 404 se rompio todo!s',
			"head" => $head,
		]);
	}

	private function actionHola()
	{
		echo 'hola';
	}
}