<?php
namespace app\controllers;

use \Controller;
use \Response;
use app\models\MesaModel;

class MesaController extends Controller
{
    public function actionListado()
    {
        $head = \app\controllers\SiteController::head();
        $footer = \app\controllers\SiteController::footer();

        $modelo = new MesaModel();
        $mesas = $modelo->obtenerTodas();

        Response::render($this->viewDir(__NAMESPACE__), 'listado', [
            'title' => 'Listado de Mesas',
            'head' => $head,
            'footer' => $footer,
            'mesas' => $mesas
        ]);
    }

    public function actionFormulario()
    {
        $head = \app\controllers\SiteController::head();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Nueva Mesa',
            'head' => $head,
            'footer' => $footer
        ]);
    }

    public function actionGuardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qr_code = $_POST['qr_code'] ?? '';
            $estado = $_POST['estado'] ?? 'libre';
            $link_qr = $_POST['link_qr'] ?? '';
            $numero = $_POST['numero'] ?? 0;

            $modelo = new MesaModel();
            $modelo->guardar($qr_code, $estado, $link_qr, $numero);
        }

        header('Location: /mesa/listado');
        exit;
    }

    public function actionModificar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID no válido";
            return;
        }

        $modelo = new MesaModel();
        $mesa = $modelo->obtenerPorId($id);

        if (!$mesa) {
            echo "Mesa no encontrada";
            return;
        }

        $head = \app\controllers\SiteController::head();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Editar Mesa',
            'head' => $head,
            'footer' => $footer,
            'mesa' => $mesa
        ]);
    }

    public function actionActualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $qr_code = $_POST['qr_code'] ?? '';
            $estado = $_POST['estado'] ?? 'libre';
            $link_qr = $_POST['link_qr'] ?? '';
            $numero = $_POST['numero'] ?? 0;

            if ($id) {
                $modelo = new MesaModel();
                $modelo->actualizar($id, $qr_code, $estado, $link_qr, $numero);
            }
        }

        header('Location: /mesa/listado');
        exit;
    }

    public function actionEliminar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $modelo = new MesaModel();
            $modelo->eliminar($id);
        }

        header('Location: /mesa/listado');
        exit;
    }
}