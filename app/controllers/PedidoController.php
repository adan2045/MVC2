<?php
namespace app\controllers;

use \Controller;
use \App;
use \Response;
use app\models\PedidoModel;

class PedidoController extends Controller
{
    public function actionGuardar()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        $mesaId = $data['mesa_id'] ?? null;
        $productos = $data['productos'] ?? [];

        if (!$mesaId || empty($productos)) {
            http_response_code(400);
            echo 'Faltan datos';
            return;
        }

        $mozoId = $_SESSION['usuario']['id'] ?? null;
        $total = 0;

        $productoModel = new \app\models\ProductoModel();
        foreach ($productos as $p) {
            $producto = $productoModel->obtenerPorId($p['id']);
            if ($producto) {
                $total += $producto['precio'] * $p['cantidad'];
            }
        }

        $model = new \app\models\PedidoModel();
        $exito = $model->guardarPedido($mesaId, $mozoId, $total, $productos);

        echo $exito ? 'ok' : 'error';
    }
}

    public function actionListado()
{
    static::path();
    $head = \app\controllers\SiteController::head();
    $nav = \app\controllers\SiteController::nav();
    $footer = \app\controllers\SiteController::footer();

    $pedidoModel = new \app\models\PedidoModel();
    $pedidos = $pedidoModel->obtenerPedidosDelDiaConDetalle();

    \Response::render('pedido/', 'listado', [
        'title' => 'Listado de Pedidos',
        'head' => $head,
        'ruta' => \App::baseUrl(),
        'nav' => $nav,
        'footer' => $footer,
        'pedidos' => $pedidos
    ]);
}

    public function actionActualizarEstado()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if ($id && in_array($estado, ['pendiente', 'en_proceso', 'completado'])) {
            $model = new \app\models\PedidoModel();
            $exito = $model->actualizarEstado($id, $estado);
            echo $exito ? 'ok' : 'error desde modelo';
        } else {
            http_response_code(400);
            echo 'Datos inválidos: id=' . $id . ', estado=' . $estado;
        }
    } else {
        echo 'Método no permitido';
    }
}

public function actionActualizarEstadoProducto()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $detalleId = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if ($detalleId && in_array($estado, ['pendiente', 'en_proceso', 'completado'])) {
            $model = new \app\models\PedidoModel();
            $exito = $model->actualizarEstadoProducto($detalleId, $estado);
            echo $exito ? 'ok' : 'error';
        } else {
            http_response_code(400);
            echo 'Datos inválidos';
        }
    }
}

}