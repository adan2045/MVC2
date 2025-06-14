<?php
namespace app\controllers;

use \Controller;
use \Response;
use \App;
use app\models\MesaModel;
use app\controllers\SesionController;

class MesaController extends Controller
{
    public function actionListado()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        $modelo = new MesaModel();
        $mesas = $modelo->obtenerTodas();

        Response::render($this->viewDir(__NAMESPACE__), 'listado', [
            'title' => 'Listado de Mesas',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'ruta' => App::baseUrl(),
            'mesas' => $mesas
        ]);
    }

    public function actionFormulario()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Nueva Mesa',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'ruta' => App::baseUrl()
        ]);
    }

    public function actionGuardar()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qr_code = $_POST['qr_code'] ?? '';
            $estado = $_POST['estado'] ?? 'libre';
            $link_qr = $_POST['link_qr'] ?? '';
            $numero = $_POST['numero'] ?? 0;

            $modelo = new MesaModel();

            if ($modelo->existeNumero($numero)) {
                echo "<p style='color: red; font-weight: bold; text-align: center;'>❌ Ya existe una mesa con ese número.</p>";
                echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/mesa/formulario', 2000);</script>";
                exit;
            }

            $modelo->guardar($qr_code, $estado, $link_qr, $numero);

            echo "<p style='color: green; font-weight: bold;'>✅ Mesa guardada correctamente.</p>";
            echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/mesa/listado', 1500);</script>";
            exit;
        }
    }

    public function actionModificar()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo "<p style='color:red;'>ID inválido.</p>";
            return;
        }

        $modelo = new MesaModel();
        $mesa = $modelo->obtenerPorId($id);

        if (!$mesa) {
            echo "<p style='color:red;'>Mesa no encontrada.</p>";
            return;
        }

        $mesa = (array) $mesa;

        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'modificar', [
            'title' => 'Editar Mesa',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'mesa' => $mesa,
            'ruta' => App::baseUrl()
        ]);
    }

    public function actionActualizar()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $qr_code = $_POST['qr_code'] ?? '';
            $estado = $_POST['estado'] ?? 'libre';
            $link_qr = $_POST['link_qr'] ?? '';
            $numero = $_POST['numero'] ?? 0;

            if ($id) {
                $modelo = new MesaModel();

                if ($modelo->existeNumeroEnOtraMesa($numero, $id)) {
                    echo "<p style='color: red; font-weight: bold; text-align: center;'>❌ Ya existe otra mesa con ese número.</p>";
                    echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/mesa/modificar?id=$id', 2000);</script>";
                    exit;
                }

                $modelo->actualizar($id, $qr_code, $estado, $link_qr, $numero);

                echo "<p style='color: green; font-weight: bold;'>✅ Mesa actualizada correctamente.</p>";
                echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/mesa/listado', 1500);</script>";
                exit;
            }
        }
    }

    public function actionCambiarEstado()
{
    $id = $_GET['id'] ?? null;
    $estado = $_GET['estado'] ?? null;

    if ($id && $estado) {
        $mesaModel = new \app\models\MesaModel();
        $mesaModel->cambiarEstado($id, $estado);
        echo "ok";
    } else {
        http_response_code(400);
        echo "Error de parámetros";
    }
}

    public function actionEliminar()
    {
        static::path();
        SesionController::redirigirSiNoAutenticado();
        $id = $_GET['id'] ?? null;

        if ($id) {
            $modelo = new MesaModel();
            $modelo->eliminar($id);
        }

        header('Location: ' . App::baseUrl() . '/mesa/listado');
        exit;
    }
}
