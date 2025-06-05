<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;

class LoginController extends Controller 
{
    public function __construct() 
    {
    }

    public function actionLogin()
    {
        // Variables para mantener los valores en el formulario
        $mail = '';
        $password = '';
        
        // Variables para los errores
        $error_mail = '';
        $error_pass = '';
        $general_error = '';
        
        // Usuario de prueba (basado en las validaciones del profesor)
        $usuarioPrueba_user = 'usuario@prueba.ts';
        $usuarioPrueba_pass = 'password';
        $usuarioPrueba_passHash = password_hash($usuarioPrueba_pass, PASSWORD_DEFAULT);

        // VALIDACIONES DEL PROFESOR - INICIO DE SESIÓN
        if (isset($_POST['ingreso'])) {
            $errorFlag = false;

            // VALIDACIONES MAIL
            if (!isset($_POST['mail'])) {
                $error_mail = "No existe mail";
                $errorFlag = true;
            } else {
                $mail = trim($_POST['mail']);
            }

            // ¿Está vacío?
            if (empty($error_mail)) {
                if (empty($mail)) {
                    $error_mail = 'No puede estar vacío';
                    $errorFlag = true;
                }
            }

            // Cantidad caracteres
            if (empty($error_mail)) {
                if (strlen($mail) < 5 || strlen($mail) > 120) {
                    $error_mail = 'Por favor ingreso un mail entre 5 y 120 caracteres';
                    $errorFlag = true;
                }
            }

            // Formato válido
            if (empty($error_mail)) {
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $error_mail = 'Formato no válido';
                    $errorFlag = true;
                }
            }

            // VALIDACIONES PASSWORD
            if (!isset($_POST['password'])) {
                $error_pass = 'No existe contraseña';
                $errorFlag = true;
            } else {
                $password = trim($_POST['password']);
            }

            // ¿Vacío?
            if (empty($error_pass)) {
                if (empty($password)) {
                    $error_pass = 'No puede estar vacío';
                    $errorFlag = true;
                }
            }

            // Caracteres válidos
            if (empty($error_pass)) {
                if (strlen($password) < 3 || strlen($password) > 10) {
                    $error_pass = 'Por favor ingrese una contraseña entre 3 y 10 caracteres';
                    $errorFlag = true;
                }
            }

            // VALIDACION BD (usando el ejemplo del profesor por ahora)
            if (empty($error_pass) && empty($error_mail)) {
                if ($mail === $usuarioPrueba_user) {
                    $verificar = password_verify($password, $usuarioPrueba_passHash);
                    if ($verificar === false) {
                        $error_pass = 'Contraseña incorrecta';
                        $errorFlag = true;
                    } else {
                        // Login exitoso - aquí puedes redirigir o iniciar sesión
                        // Ejemplo: header('Location: /dashboard');
                        // exit();
                        $general_error = 'Login exitoso'; // Temporal para testing
                    }
                } else {
                    $error_mail = 'Usuario incorrecto';
                    $errorFlag = true;
                }
            }

            /* VALIDACION CON TU BASE DE DATOS - DESCOMENTA Y ADAPTA CUANDO TENGAS LA ESTRUCTURA
            if (empty($error_pass) && empty($error_mail)) {
                try {
                    // Usar tu clase DataBase
                    $db = new DataBase();
                    $pdo = $db->getConnection(); // Adapta según tu implementación
                    
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
                    $stmt->bindParam(':email', $mail);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        if (password_verify($password, $user['password'])) {
                            // Login exitoso
                            session_start();
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_email'] = $user['email'];
                            $_SESSION['user_rol'] = $user['rol'];
                            header('Location: /dashboard');
                            exit();
                        } else {
                            $error_pass = 'Contraseña incorrecta';
                            $errorFlag = true;
                        }
                    } else {
                        $error_mail = 'Usuario no encontrado';
                        $errorFlag = true;
                    }
                } catch (PDOException $e) {
                    $general_error = 'Error de conexión a la base de datos';
                    $errorFlag = true;
                }
            }
            */
        }

        // Renderizar la vista
        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();
        
        Response::render($this->viewDir(__NAMESPACE__), "login", [
            "title" => $this->title . "Login",
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
            // Variables para el formulario
            "mail" => $mail,
            "password" => $password,
            "error_mail" => $error_mail,
            "error_pass" => $error_pass,
            "general_error" => $general_error,
        ]);
    }
}