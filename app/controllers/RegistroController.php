<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;

class RegistroController extends Controller 
{
    public function __construct() 
    {
    }

    public function actionRegistro()
    {
        // Variables para mantener los valores en el formulario
        $nombre = '';
        $apellido = '';
        $DNI = '';
        $fechaNac = '';
        $genero = '';
        $telefono = '';
        $provincia = '';
        $mail = '';
        $password = '';
        $password2 = '';
        $termCond = '';
        $rol = '';

        // Variables para fechas (del profesor)
        $fechaMin = date('1920-01-01');
        $fechaMax = date('2007-01-01');
        $fechaValida = '';

        // Variables para errores
        $msg = '';
        $errorNombre = '';
        $errorApellido = '';
        $errorDNI = '';
        $errorFechaNac = '';
        $errorGenero = '';
        $errorTel = '';
        $errorProvincia = '';
        $errorMail = '';
        $errorPass = '';
        $errorPass2 = '';
        $errorTerm = '';
        $errorRol = '';

        // VALIDACIONES DEL PROFESOR - REGISTRO
        if (isset($_POST['registro'])) {
            $errorFlag = false;

            // FUNCIÓN DE VALIDACIÓN DEL PROFESOR
            function validacion($campo, $min, $max, $campoName) {
                $msg = '';
                $error = false;
                $campo2 = '';

                if (!isset($_POST[$campo])) {
                    $msg = "No existe campo ".$campoName;
                    $error = true;
                } else {
                    $campo2 = trim($_POST[$campo]);
                    if (empty($campo2)) {
                        $msg = 'No puede estar vacío el campo '.$campoName;
                        $error = true;
                    } else {
                        if (strlen($campo2) < $min || strlen($campo2) > $max) {
                            $msg = 'Por favor ingrese entre '.$min.' y '.$max.' caracteres';
                            $error = true;
                        }
                    }
                }
                $resultado['msg'] = $msg;
                $resultado['error'] = $error;
                $resultado['campo2'] = $campo2;

                return $resultado;
            }

            // FUNCIÓN PARA VALIDAR CHECKS DEL PROFESOR
            function validarChecks($campo, $nombreCampo, $array) {
                $error = false;
                $msg = '';
                $campo2 = '';
                
                if (!isset($_POST[$campo])) {
                    $msg = "El campo ".$nombreCampo." no existe";
                    $error = true;
                } else {
                    $campo2 = trim($_POST[$campo]);
                    $campoValido = false;
                    foreach ($array as $valid) {
                        if ($campo2 === $valid) {
                            $campoValido = true;
                            break;
                        }
                    }
                    if (!$campoValido) {
                        $msg = "Debe seleccionar campo ".$nombreCampo." válido";
                        $error = true;
                    }
                }
                $resultado['msg'] = $msg;
                $resultado['error'] = $error;
                $resultado['campo2'] = $campo2;

                return $resultado;
            }

            // FUNCIÓN PARA VALIDAR CAMPO ALFABÉTICO DEL PROFESOR
            function validarCampoAlfabetico($campo) {
                $expresionRegular = '/^[a-zA-ZÀ-ÿ\u00f1\u00d1 ]+$/u';
                return preg_match($expresionRegular, $campo) === 1;
            }

            // VALIDACIONES NOMBRE
            $valNombre = validacion('nombre', 3, 100, 'nombre');
            if ($valNombre['error']) {
                $errorNombre = $valNombre['msg'];
            } else {
                $nombre = $valNombre['campo2'];
                // Validar que sea alfabético
                if (!validarCampoAlfabetico($nombre)) {
                    $errorNombre = 'Debe contener solo caracteres alfabéticos';
                    $errorFlag = true;
                }
            }

            // VALIDACIONES APELLIDO
            $valApellido = validacion('apellido', 3, 100, 'apellido');
            if ($valApellido['error']) {
                $errorApellido = $valApellido['msg'];
            } else {
                $apellido = $valApellido['campo2'];
                // Validar que sea alfabético
                if (!validarCampoAlfabetico($apellido)) {
                    $errorApellido = 'Debe contener solo caracteres alfabéticos';
                    $errorFlag = true;
                }
            }

            // VALIDACIONES DNI
            $valDNI = validacion('DNI', 7, 11, 'DNI');
            if ($valDNI['error']) {
                $errorDNI = $valDNI['msg'];
            } else {
                if (!is_numeric($valDNI['campo2'])) {
                    $errorDNI = 'Por favor ingrese solo números';
                    $errorFlag = true;
                } else {
                    $DNI = $valDNI['campo2'];
                }
            }

            /* VALIDACIONES FECHA NACIMIENTO - Comentado por ahora
            $valFechaNac = validacion('fechaNac', 10, 10, 'fecha de nacimiento');
            if ($valFechaNac['error']) {
                $errorFechaNac = $valFechaNac['msg'];
            } else {
                $fechaValida = strtotime($valFechaNac['campo2']);
                if ($fechaValida === false) {
                    $errorFechaNac = 'Formato de fecha inválido';
                    $errorFlag = true;
                } else {
                    $fechaNac = $valFechaNac['campo2'];
                }
            }
            */

            /* VALIDACIONES GENERO - Comentado por ahora
            $campoValidoGenero = array('M', 'F', 'O');
            $valGen = validarChecks('genero', 'genero', $campoValidoGenero);
            if ($valGen['error']) {
                $errorGenero = $valGen['msg'];
            } else {
                $genero = $valGen['campo2'];
            }
            */

            /* VALIDACIONES TELEFONO - Comentado por ahora
            $valTel = validacion('telefono', 9, 18, 'teléfono');
            if ($valTel['error']) {
                $errorTel = $valTel['msg'];
            } else {
                $telefono = $valTel['campo2'];
            }
            */

            /* VALIDACIONES PROVINCIA - Comentado por ahora
            $provinciasValidas = array("BuenosAires", "Catamarca", "Chaco", "Chubut", "Córdoba", "Corrientes",
            "EntreRíos", "Formosa", "Jujuy", "LaPampa", "LaRioja", "Mendoza", "Misiones", "Neuquén", "RíoNegro",
            "Salta", "SanJuan", "SanLuis", "SantaCruz", "SantaFe", "SantiagoDelEstero", "TierraDelFuego", "Tucumán");
            
            $valProv = validarChecks('provincia', 'provincia', $provinciasValidas);
            if ($valProv['error']) {
                $errorProvincia = $valProv['msg'];
            } else {
                $provincia = $valProv['campo2'];
            }
            */

            // VALIDACIONES MAIL
            $valMail = validacion('mail', 5, 120, 'e-mail');
            if ($valMail['error']) {
                $errorMail = $valMail['msg'];
            } else {
                if (!filter_var($valMail['campo2'], FILTER_VALIDATE_EMAIL)) {
                    $errorMail = 'Formato no válido';
                    $errorFlag = true;
                } else {
                    $mail = $valMail['campo2'];
                }
            }

            // VALIDACIONES PASSWORD
            $valPass = validacion('password', 5, 10, 'contraseña');
            if ($valPass['error']) {
                $errorPass = $valPass['msg'];
            } else {
                $password = $valPass['campo2'];
            }

            // VALIDACIONES SEGUNDA PASSWORD
            $valPass2 = validacion('password2', 5, 10, 'contraseña');
            if ($valPass2['error']) {
                $errorPass2 = $valPass2['msg'];
            } else {
                $password2 = $valPass2['campo2'];
            }

            // ¿Es la misma que la anterior?
            if (empty($errorPass) && empty($errorPass2)) {
                if ($password !== $password2) {
                    $errorPass = 'Por favor ingrese la misma contraseña en ambos campos';
                    $errorPass2 = 'Por favor ingrese la misma contraseña en ambos campos';
                    $errorFlag = true;
                }
            }

            // VALIDACIONES ROL
            $rolesValidos = array("mozo", "cajero", "admin");
            $valRol = validarChecks('rol', 'rol', $rolesValidos);
            if ($valRol['error']) {
                $errorRol = $valRol['msg'];
            } else {
                $rol = $valRol['campo2'];
            }

            // VALIDACIONES TÉRMINOS Y CONDICIONES
            if (!isset($_POST['termCond'])) {
                $errorTerm = "Debe aceptar los términos y condiciones";
                $errorFlag = true;
            } else {
                $termCond = $_POST['termCond'];
            }

            // SI NO HAY ERRORES, PROCEDER A GUARDAR EN BD
            if (!$errorFlag) {
                /* GUARDAR EN TU BASE DE DATOS - DESCOMENTA Y ADAPTA
                try {
                    $db = new DataBase();
                    $pdo = $db->getConnection();
                    
                    // Verificar si el email ya existe
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
                    $stmt->bindParam(':email', $mail);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $errorMail = 'El correo electrónico ya está registrado';
                    } else {
                        // Hash de la contraseña
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        
                        // Insertar nuevo usuario
                        $insertStmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, dni, rol) VALUES (:nombre, :apellido, :email, :password, :dni, :rol)");
                        $insertStmt->bindParam(':nombre', $nombre);
                        $insertStmt->bindParam(':apellido', $apellido);
                        $insertStmt->bindParam(':email', $mail);
                        $insertStmt->bindParam(':password', $passwordHash);
                        $insertStmt->bindParam(':dni', $DNI);
                        $insertStmt->bindParam(':rol', $rol);
                        
                        if ($insertStmt->execute()) {
                            $msg = 'Usuario registrado con éxito';
                            // Limpiar campos
                            $nombre = $apellido = $mail = $password = $password2 = $DNI = $rol = '';
                        } else {
                            $msg = 'Error al registrar usuario';
                        }
                    }
                } catch (PDOException $e) {
                    $msg = 'Error de conexión a la base de datos';
                }
                */
                $msg = 'Registro exitoso (modo prueba)'; // Temporal para testing
            }
        }

        // Renderizar la vista
        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();
        
        Response::render($this->viewDir(__NAMESPACE__), "registro", [
            "title" => $this->title . "Registro",
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
            // Variables para el formulario
            "nombre" => $nombre,
            "apellido" => $apellido,
            "DNI" => $DNI,
            "fechaNac" => $fechaNac,
            "genero" => $genero,
            "telefono" => $telefono,
            "provincia" => $provincia,
            "mail" => $mail,
            "password" => $password,
            "password2" => $password2,
            "termCond" => $termCond,
            "rol" => $rol,
            // Variables para errores
            "msg" => $msg,
            "errorNombre" => $errorNombre,
            "errorApellido" => $errorApellido,
            "errorDNI" => $errorDNI,
            "errorFechaNac" => $errorFechaNac,
            "errorGenero" => $errorGenero,
            "errorTel" => $errorTel,
            "errorProvincia" => $errorProvincia,
            "errorMail" => $errorMail,
            "errorPass" => $errorPass,
            "errorPass2" => $errorPass2,
            "errorTerm" => $errorTerm,
            "errorRol" => $errorRol,
            "fechaMin" => $fechaMin,
            "fechaMax" => $fechaMax,
        ]);
    }
}

class RegistroUserController extends Controller 
{
    public function __construct() 
    {
    }

    public function actionRegistroUser()
    {
        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();
        Response::render($this->viewDir(__NAMESPACE__), "registroUser", [
            "title" => $this->title . "RegistroUser",
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
        ]);
    }
}