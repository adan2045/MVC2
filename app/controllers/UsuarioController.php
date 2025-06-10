<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;
use \App;
use app\models\UsuarioModel;

class UsuarioController extends Controller
{
    public function actionFormulario()
    {
        static::path();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Nuevo Usuario',
            'head' => $head,
            'ruta' => App::baseUrl(),
            'nav' => $nav,
            'footer' => $footer
        ]);
    }

    public function actionModificar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID inválido";
            return;
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->obtenerPorId($id);

        if (!$usuario) {
            echo "Usuario no encontrado";
            return;
        }

        static::path();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
            'title' => 'Modificar Usuario',
            'head' => $head,
            'ruta' => App::baseUrl(),
            'nav' => $nav,
            'footer' => $footer,
            'usuario' => $usuario
        ]);
    }

    public function actionListado()
    {
        static::path();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $footer = \app\controllers\SiteController::footer();
        $usuarioModel = new UsuarioModel();
        $usuarios = $usuarioModel->obtenerTodos();

        Response::render($this->viewDir(__NAMESPACE__), "listado", [
            "title" => "Listado de Usuarios",
            "head" => $head,
            "ruta" => App::baseUrl(),
            "nav" => $nav,
            "footer" => $footer,
            "usuarios" => $usuarios
        ]);
    }

    public function actionEliminar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $usuarioModel = new UsuarioModel();
            $usuarioModel->eliminar($id);
        }

        header("Location: " . App::baseUrl() . "/usuario/listado");
        exit;
    }

    public function actionGuardar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = $_POST["nombre"] ?? null;
            $apellido = $_POST["apellido"] ?? null;
            $email = $_POST["email"] ?? null;
            $password = $_POST["password"] ?? null;
            $dni = $_POST["dni"] ?? null;
            $rol = $_POST["rol"] ?? null;
            $terminos = $_POST["terminos"] ?? null;

            if ($nombre && $apellido && $email && $password && $dni && $rol && $terminos) {
                $usuarioModel = new UsuarioModel();
                $usuarioModel->guardar($nombre, $apellido, $email, $password, $dni, $rol);

                echo "<p style='color: green; font-weight: bold;'>✅ Usuario guardado correctamente.</p>";
                echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/usuario/listado', 1500);</script>";
                exit;
            } else {
                echo "<p style='color: red; font-weight: bold;'>⚠️ Faltan campos requeridos.</p>";
                echo "<a href='" . App::baseUrl() . "/usuario/formulario'>Volver al formulario</a>";
            }
        } else {
            echo "<p style='color: red;'>Solicitud inválida.</p>";
        }
    }

    public function actionActualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? null;
            $dni = $_POST['dni'] ?? '';
            $rol = $_POST['rol'] ?? '';

            $usuarioModel = new UsuarioModel();

            if ($id) {
                $usuarioModel->actualizar($id, $nombre, $apellido, $email, $password, $dni, $rol);

                echo "<p style='color: green; font-weight: bold;'>✅ Usuario actualizado correctamente.</p>";
                echo "<script>setTimeout(() => window.location.href = '" . App::baseUrl() . "/usuario/listado', 1500);</script>";
                exit;
            } else {
                echo "<p style='color: red;'>ID inválido para actualización.</p>";
            }
        } else {
            echo "<p style='color: red;'>Solicitud no válida.</p>";
        }
    }

    public function actionIndex($var = null)
    {
        self::action404();
    }
}