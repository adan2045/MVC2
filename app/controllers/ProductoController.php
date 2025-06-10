<?php
namespace app\controllers;

use \Controller;
use \Response;
use app\models\ProductoModel;
use app\controllers\SesionController;

class ProductoController extends Controller
{
    public function actionListado()
    {
        $path = static::path();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        $model = new ProductoModel();
        $productos = $model->obtenerTodos();

        Response::render($this->viewDir(__NAMESPACE__), 'listado', [
            'title' => 'Listado de Productos',
            'head' => $head,
            'ruta'=>self::$ruta,
            'nav' => $nav,
            'footer' => $footer,
            'productos' => $productos
        ]);
    }

    public function actionFormulario()
    {
        $path = static::path();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Formulario Producto',
            'head' => $head,
            'ruta'=>self::$ruta,
            'nav' => $nav,
            'footer' => $footer
        ]);
    }

    public function actionGuardar()
    {
        $path = static::path();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = $_POST["nombre"] ?? '';
            $descripcion = $_POST["descripcion"] ?? '';
            $precio = $_POST["precio"] ?? 0;
            $categoria = $_POST["categoria"] ?? '';

            if ($nombre && $descripcion && $precio && $categoria) {
                $model = new ProductoModel();
                $model->guardar($nombre, $descripcion, $precio, $categoria);
                header("Location: /producto/listado");
                exit;
            } else {
                echo "Faltan campos obligatorios.";
            }
        }
    }

    public function actionModificar()
    {
        $path = static::path();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "ID inválido.";
            return;
        }

        $model = new ProductoModel();
        $producto = $model->obtenerPorId($id);

        if (!$producto) {
            echo "Producto no encontrado.";
            return;
        }

        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Editar Producto',
            'head' => $head,
            'ruta'=>self::$ruta,
            'nav' => $nav,
            'footer' => $footer,
            'producto' => $producto
        ]);
    }

    public function actionActualizar()
    {
        $path = static::path();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"] ?? null;
            $nombre = $_POST["nombre"] ?? '';
            $descripcion = $_POST["descripcion"] ?? '';
            $precio = $_POST["precio"] ?? 0;
            $categoria = $_POST["categoria"] ?? '';

            if ($id && $nombre && $descripcion && $precio && $categoria) {
                $model = new ProductoModel();
                $model->actualizar($id, $nombre, $descripcion, $precio, $categoria);
                header("Location: /producto/listado");
                exit;
            } else {
                echo "Faltan campos obligatorios.";
            }
        }
    }

    public function actionEliminar()
    {
        
        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new ProductoModel();
            $model->eliminar($id);
        }

        header("Location: /producto/listado");
        exit;
    }
}