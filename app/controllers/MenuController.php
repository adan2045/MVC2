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