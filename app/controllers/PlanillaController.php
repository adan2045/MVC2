<?php
namespace app\controllers;

use \Controller;
use \App;
use \Response;
use app\models\CajaModel;

class PlanillaController extends Controller
{
    public function actionIndex()
    {
        $fechaHoy = date('Y-m-d');
        $cajaModel = new CajaModel();

        // Obtener los totales del día usando el CajaModel
        $datos = $cajaModel->obtenerTotalesDelDia($fechaHoy);
        
        // Obtener el resumen por producto
        $productos = $cajaModel->resumenPorProducto($fechaHoy);

        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render('planilla', 'index', [
            'title' => 'Planilla de Caja',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'ruta' => App::baseUrl(),
            'fecha' => $fechaHoy,
            'datos' => $datos,      // Aquí están todos los totales por método de pago
            'productos' => $productos  // Aquí está el resumen por producto
        ]);
    }
}