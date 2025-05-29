<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

Class LoginController extends Controller {
    public function __construct()
	{

	}
    public function actionLogin()
    {	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		Response::render($this->viewDir(__NAMESPACE__), "login", [
			"title" => $this->title . "Login",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
	
}