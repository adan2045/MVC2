<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;
use app\models\UsuarioModel;

class UsuarioController extends Controller
{
    public function actionFormulario()
    {
        $head = \app\controllers\SiteController::head();
        $footer = \app\controllers\SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), "formulario", [
            "title" => "Alta de Usuario",
            "head" => $head,
            "footer" => $footer,
        ]);
    }
    public function actionListado()
{
   
    $head = \app\controllers\SiteController::head();
    $footer = \app\controllers\SiteController::footer();
    $usuarioModel = new UsuarioModel();
    $usuarios = $usuarioModel->obtenerTodos();

    Response::render($this->viewDir(__NAMESPACE__), "listado", [
        "title" => "Listado de Usuarios",
        "head" => $head,
        "footer" => $footer,
        "usuarios" => $usuarios
    ]);
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

                header("Location: /usuario/formulario"); // redirige donde prefieras
                exit;
            } else {
                echo "Faltan campos requeridos.";
            }
        }
    }
}