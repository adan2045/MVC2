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
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		Response::render($this->viewDir(__NAMESPACE__), "menu", [
			"title" => $this->title . "menu",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
}