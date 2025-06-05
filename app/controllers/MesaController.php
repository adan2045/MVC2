<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

Class RegistroMesaController extends Controller {
    public function __construct()
	{

	}
    public function actionMesa()
    {	
		$footer = SiteController::footer();
		$head = SiteController::head();
		$nav = SiteController::nav();
		Response::render($this->viewDir(__NAMESPACE__), "Mesa", [
			"title" => $this->title . "Mesa",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
	}
}