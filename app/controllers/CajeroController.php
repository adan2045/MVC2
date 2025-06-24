<?php
namespace app\controllers;

use \Controller;
use \Response;
use \DataBase;
use app\controllers\SesionController;
use app\models\MesaModel;

class CajeroController extends Controller {
    public $nombre = '';
    public $apellido;

    public function __construct() {}

    public function actionIndex($var = null)
    {
        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();
        $path = static::path();

        Response::render($this->viewDir(__NAMESPACE__), "inicio", [
            "title" => $this->title . "Inicio",
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
        ]);
    }

    public function actionVistaCajero()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();
        $path = static::path();

        $mesaModel = new MesaModel();
        $mesas = $mesaModel->obtenerConTotales();

        $pedidoModel = new \app\models\PedidoModel();
        $pedidos = $pedidoModel->obtenerPedidosDelDiaConDetalle();

        Response::render($this->viewDir(__NAMESPACE__), "vistaCajero", [
            "title" => $this->title . "Mesas",
            'ruta' => self::$ruta,
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
            "mesas" => $mesas,
            "cajero" => $_SESSION['user_email'] ?? 'Sin sesión',
            "pedidos" => $pedidos
        ]);
    }

    public function actionPagarMesa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mesa_id'])) {
            $mesaId = intval($_POST['mesa_id']);
            $db = \DataBase::getInstance()->getConnection();

            try {
                $db->beginTransaction();

                $stmt = $db->prepare("UPDATE pedidos SET cerrado = 1 WHERE mesa_id = ? AND DATE(fecha) = CURDATE()");
                $stmt->execute([$mesaId]);

                $stmt2 = $db->prepare("UPDATE mesas SET estado = 'libre' WHERE id = ?");
                $stmt2->execute([$mesaId]);

                $db->commit();
                echo 'ok';
            } catch (\Exception $e) {
                $db->rollBack();
                http_response_code(500);
                echo 'error';
            }
        } else {
            http_response_code(400);
            echo 'error';
        }
    }

    public function actionRegistrarGasto()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivo = $_POST['motivo'] ?? '';
            $monto = $_POST['monto'] ?? 0;
            $autorizado_por = $_POST['autorizado_por'] ?? '';
            $usuario_id = $_SESSION['user_id'] ?? null;
            $caja_id = $_POST['caja_id'] ?? null;

            if ($motivo != '' && $monto > 0 && $autorizado_por != '' && $caja_id) {
                $db = \DataBase::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO gastos (fecha, monto, motivo, autorizado_por, usuario_id, caja_id) VALUES (NOW(), ?, ?, ?, ?, ?)");
                $stmt->execute([$monto, $motivo, $autorizado_por, $usuario_id, $caja_id]);
                header("Location: /MVC2/cajero/planillaCaja");
                exit;
            } else {
                echo "<script>alert('Faltan datos obligatorios para registrar el gasto'); window.history.back();</script>";
                exit;
            }
        }
    }

    public function actionCuentaMesa()
    {
        if (!isset($_GET['id'])) {
            echo "Mesa no especificada";
            return;
        }

        $mesaId = intval($_GET['id']);

        $pedidoModel = new \app\models\PedidoModel();
        $pedidos = $pedidoModel->obtenerPedidosActivosPorMesa($mesaId);

        $productos = [];
        $total = 0;

        foreach ($pedidos as $pedido) {
            foreach ($pedido['detalles'] as $detalle) {
                $subtotal = $detalle['precio'] * $detalle['cantidad'];
                $productos[] = [
                    'nombre' => $detalle['nombre'],
                    'descripcion' => $detalle['descripcion'],
                    'precio' => $detalle['precio'],
                    'cantidad' => $detalle['cantidad'],
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        $mesaModel = new \app\models\MesaModel();
        $mesa = $mesaModel->obtenerPorId($mesaId);

        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();

        Response::render('cajero', 'cuenta', [
            'productos' => $productos,
            'total' => $total,
            'mesa' => $mesa,
            'ruta' => \App::baseUrl(),
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer
        ]);
    }

    public function actionCuenta()
    {
        if (!isset($_GET['id'])) {
            echo "Mesa no especificada.";
            return;
        }

        $mesaId = intval($_GET['id']);
        $pedidoModel = new \app\models\PedidoModel();
        $datos = $pedidoModel->obtenerDetalleCuentaPorMesa($mesaId);

        $footer = SiteController::footer();
        $head = SiteController::head();
        $nav = SiteController::nav();

        Response::render('cajero', 'cuenta', [
            'head' => $head,
            'title' => 'Cuenta de Mesa',
            'nav' => $nav,
            'footer' => $footer,
            'mesa' => $datos['mesa'],
            'productos' => $datos['productos'],
            'total' => $datos['total'],
            'ruta' => \App::baseUrl(),
        ]);
    }

    public function actionCerrarMesa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mesaId = $_POST['mesa_id'] ?? null;

            if ($mesaId) {
                $mesaModel = new \app\models\MesaModel();
                $mesaModel->cerrarMesaYSolicitarCuenta($mesaId);
                echo 'ok';
            } else {
                http_response_code(400);
                echo 'Faltan datos';
            }
        }
    }

    public function actionMarcarPagado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mesa_id = $_POST['mesa_id'] ?? null;
            $medio_pago = $_POST['medio_pago'] ?? null;

            if ($mesa_id && $medio_pago) {
                $pedidoModel = new \app\models\PedidoModel();
                $pedidoModel->cerrarPedidosDeHoyPorMesa($mesa_id, $medio_pago);

                $mesaModel = new \app\models\MesaModel();
                $mesaModel->actualizarEstado($mesa_id, 'disponible');

                echo 'ok';
            } else {
                http_response_code(400);
                echo 'Faltan datos';
            }
        }
    }

    public function actionPlanillaCaja()
    {
        $footer = \app\controllers\SiteController::footer();
        $head = \app\controllers\SiteController::head();
        $nav = \app\controllers\SiteController::nav();
        $path = static::path();

        $cajaId = $_SESSION['caja_id'] ?? null;

        if ($cajaId) {
            $cajaModel = new \app\models\CajaModel();
            $datos = $cajaModel->obtenerTotalesDelDia();
            $productos = $cajaModel->resumenPorProducto();
            $gastos = $cajaModel->obtenerGastosDelDia();
            $datos['total_gastos'] = $gastos['total'];
            $datos['cantidad_gastos'] = $gastos['cantidad'];
        } else {
            $datos = [
                'venta_bruta' => 0, 'cantidad_pedidos' => 0,
                'efectivo_total' => 0, 'efectivo_cantidad' => 0,
                'qr' => 0, 'qr_cantidad' => 0,
                'mercadopago' => 0, 'mercadopago_cantidad' => 0,
                'tarjetas' => 0, 'tarjetas_cantidad' => 0,
                'inicio_caja' => 0, 'efectivo_ventas' => 0,
                'caja_fuerte' => 0, 'cantidad_caja_fuerte' => 0,
                'total_gastos' => 0, 'cantidad_gastos' => 0,
                'saldo' => 0
            ];
            $productos = [];
        }

        \Response::render($this->viewDir(__NAMESPACE__), "planillaCaja", [
            "title" => "Planilla de Caja",
            "head" => $head,
            "nav" => $nav,
            "footer" => $footer,
            "datos" => $datos,
            "productos" => $productos,
            "ruta" => $path,
        ]);
    }

    public function actionRegistrarCajaFuerte()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $monto = $_POST['monto'] ?? 0;
            $responsable = $_POST['responsable'] ?? '';
            $caja_id = $_POST['caja_id'] ?? null;

            if ($monto > 0 && !empty($responsable) && !empty($caja_id)) {
                $db = \DataBase::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO caja_fuerte (fecha, monto, responsable, caja_id) VALUES (NOW(), ?, ?, ?)");
                $stmt->execute([$monto, $responsable, $caja_id]);
                header("Location: /MVC2/cajero/planillaCaja");
                exit;
            } else {
                $_SESSION['mensaje_error'] = "Faltan datos obligatorios para registrar el movimiento de Caja Fuerte.";
                header("Location: /MVC2/cajero/planillaCaja");
                exit;
            }
        }
    }

    public function actionAbrirCaja()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $monto = isset($_GET['monto']) ? floatval($_GET['monto']) : 0.0;
        if ($monto < 0 || $monto > 10000000) {
            $_SESSION['mensaje_error'] = "El monto debe ser entre 0 y 10.000.000.";
            header("Location: /MVC2/cajero/planillaCaja");
            exit;
        }

        $hoy = date('Y-m-d');
        $db = \DataBase::getInstance()->getConnection();

        // Busca si hay caja abierta hoy
        $stmt = $db->prepare("SELECT * FROM cajas WHERE DATE(fecha_apertura) = ? AND fecha_cierre IS NULL");
        $stmt->execute([$hoy]);
        $cajaAbierta = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($cajaAbierta) {
            $_SESSION['caja_abierta'] = true;
            $_SESSION['caja_id'] = $cajaAbierta['id'];
            $_SESSION['caja_apertura'] = $cajaAbierta['fecha_apertura'];
            $_SESSION['saldo_inicial'] = $cajaAbierta['saldo_inicial'];
            header("Location: /MVC2/cajero/planillaCaja");
            exit;
        }

        // Inserta nueva caja
        $stmt = $db->prepare("INSERT INTO cajas (fecha_apertura, saldo_inicial) VALUES (NOW(), ?)");
        $stmt->execute([$monto]);
        $cajaId = $db->lastInsertId();

        $_SESSION['caja_abierta'] = true;
        $_SESSION['caja_id'] = $cajaId;
        $_SESSION['caja_apertura'] = date('Y-m-d H:i:s');
        $_SESSION['saldo_inicial'] = $monto;

        header("Location: /MVC2/cajero/planillaCaja");
        exit;
    }

    public function actionCerrarCaja() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        try {
            $cajaModel = new \app\models\CajaModel();
            $usuarioId = $_SESSION['user_id'] ?? null;
            $datos = $cajaModel->obtenerTotalesDelDia();
            $saldoCierre = $datos['saldo'];

            $exito = $cajaModel->cerrarCajaDelDia($usuarioId, null, $saldoCierre);

            if ($exito) {
                $_SESSION['mensaje_exito'] = "¡Caja cerrada con éxito!";
                unset($_SESSION['caja_abierta']);
                unset($_SESSION['caja_id']);
                unset($_SESSION['caja_apertura']);
                unset($_SESSION['saldo_inicial']);
            } else {
                $_SESSION['mensaje_error'] = "No se encontró una caja abierta para cerrar.";
            }
        } catch (\Exception $e) {
            $_SESSION['mensaje_error'] = "Error al cerrar la caja: " . $e->getMessage();
        }

        header("Location: " . \App::baseUrl() . "/cajero/planillaCaja");
        exit;
    }
}
?>