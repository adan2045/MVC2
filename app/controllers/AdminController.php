<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

class AdminController extends Controller{
    // Constructor
	public function __construct()
	{

	}
	public function actionGestion()
	{	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		$path = static::path();
		Response::render($this->viewDir(__NAMESPACE__), "gestion", [
			"title" => $this->title . "gestion de locales",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
}