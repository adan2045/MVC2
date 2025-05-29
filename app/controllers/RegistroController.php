<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

Class RegistroController extends Controller {
    public function __construct()
	{

	}
    public function actionRegistro()
    {	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		Response::render($this->viewDir(__NAMESPACE__), "registro", [
			"title" => $this->title . "Registro",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
}

Class RegistroUserController extends Controller {
    public function __construct()
	{

	}
    public function actionRegistroUser()
    {	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		Response::render($this->viewDir(__NAMESPACE__), "registroUser", [
			"title" => $this->title . "RegistroUser",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
}