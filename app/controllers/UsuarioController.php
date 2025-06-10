<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;
use app\models\UsuarioModel;
use app\controllers\SesionController;

class UsuarioController extends Controller
{
    public function actionFormulario()
    {
        static::path();
     $head = \app\controllers\SiteController::head();
     $nav = \app\controllers\SiteController::nav(); // agregar esta línea
     $footer = \app\controllers\SiteController::footer();


        Response::render($this->viewDir(__NAMESPACE__), 'formulario', [
        'title' => 'Nuevo Usuario',
        'head' => $head,
        'ruta'=>self::$ruta,
        'nav' => $nav, // agregar esta línea
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
        'ruta'=>self::$ruta,
        'nav' => $nav,
        'footer' => $footer,
        'usuario' => $usuario
    ]);
}

    public function actionIndex($var = null){
		self::action404();
	}

	/*obtiene todos los datos de un usuarios por id o por email según dato ingresado*/
	public static function GetUser($emailOrId){
		if (filter_var($emailOrId, FILTER_VALIDATE_EMAIL)) {
			# obtener datos de usuario por Email
			$userData = UserModel::findEmail($emailOrId);
			// var_dump($userData);
		}else{
			# obtener datos de usuario por Id
			$userData = UserModel::findId($emailOrId);
			// var_dump($userData);
		}
		return $userData;
	}
    public function actionListado()
{
   static::path();
    $head = \app\controllers\SiteController::head();
    $nav = \app\controllers\SiteController::nav();  // <-- Línea nueva
    $footer = \app\controllers\SiteController::footer();
    $usuarioModel = new UsuarioModel();
    $usuarios = $usuarioModel->obtenerTodos();

    Response::render($this->viewDir(__NAMESPACE__), "listado", [
    "title" => "Listado de Usuarios",
    "head" => $head,
    'ruta'=>self::$ruta,
    "nav" => $nav,  // <-- Línea nueva
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

    header('Location: /usuario/listado');
    exit;
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
        }

        header("Location: /usuario/listado");
        exit;
    }
}
    public function actionGuardar()
    {echo "Entró a actionGuardar<br>";
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
                header("Location: /usuario/listado");
                exit;
            } else {
                echo "Faltan campos requeridos.";
            }
        }
    }
}