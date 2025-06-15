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

		Response::render($this->viewDir(__NAMESPACE__), "vistaCajero", [
			"title" => $this->title . "Mesas",
			'ruta' => self::$ruta,
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
			"mesas" => $mesas,
			"cajero" => $_SESSION['user_email'] ?? 'Sin sesión'
		]);
	}

	

	public function actionPagarMesa()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mesa_id'])) {
        $mesaId = intval($_POST['mesa_id']);
        $db = \DataBase::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Marcar como cerrados los pedidos de hoy
            $stmt = $db->prepare("UPDATE pedidos SET cerrado = 1 WHERE mesa_id = ? AND DATE(fecha) = CURDATE()");
            $stmt->execute([$mesaId]);

            // Cambiar estado de la mesa a 'disponible'
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

    // 💡 Estas variables son claves para que no dé error la vista
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

    Response::render('cajero', 'cuenta', [  // 👈 CAMBIADO de 'cuenta_mesa' a 'cuenta'
        'head' => $head,
        'title' => 'Cuenta de Mesa',
        'nav' => $nav,
        'footer' => $footer,
        'mesa' => $datos['mesa'],
        'productos' => $datos['productos'],
        'total' => $datos['total'],
        'ruta' => \App::baseUrl(), // 👈 AGREGADO para que los botones funcionen
    ]);
}
public function actionCerrarMesa()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mesaId = $_POST['mesa_id'] ?? null;

        if ($mesaId) {
            $mesaModel = new \app\models\MesaModel();
            $mesaModel->cerrarMesaYSolicitarCuenta($mesaId); // Usa el nuevo método
            echo 'ok';

            // 🧾 Podés imprimir el ticket acá en el futuro:
            // echo "<script>window.print();</script>";

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

        if ($mesa_id) {
            $pedidoModel = new \app\models\PedidoModel();
            $pedidoModel->cerrarPedidosDeHoyPorMesa($mesa_id);

            $mesaModel = new \app\models\MesaModel();
            $mesaModel->actualizarEstado($mesa_id, 'disponible');

            echo 'ok';
        } else {
            http_response_code(400);
            echo 'Faltan datos';
        }
    }
}
}

/*


class CajeroController extends Controller{
    private $mesaModel;
    private $cuentaModel;
    private $mozoModel;
    private $pedidoModel;

    public function __construct() {
        // Inicializar modelos necesarios
        $this->mesaModel = new MesaModel();
        $this->cuentaModel = new CuentaModel();
        $this->mozoModel = new MozoModel();
        $this->pedidoModel = new PedidoModel();
    }

    // Vista principal del cajero
    public function actionIndex() {
        Response::render($this->viewDir(__NAMESPACE__), "vistaCajero", [
			"title" => $this->title . "Mesas",
			"head" => $head,
			"nav" => $nav,
			"footer" => $footer,
		]);
    }

    // Ver detalles de una mesa específica
    public function verMesa($mesaId) {
        $mesa = $this->mesaModel->getMesaById($mesaId);
        $cuenta = $this->cuentaModel->getCuentaByMesa($mesaId);
        $pedidos = $this->pedidoModel->getPedidosByMesa($mesaId);

        $data = [
            'title' => 'Detalles Mesa ' . $mesaId,
            'mesa' => $mesa,
            'cuenta' => $cuenta,
            'pedidos' => $pedidos
        ];

        return $this->view->render('cajero/detalle-mesa', $data);
    }

    // Cerrar una mesa (finalizar servicio)
    public function cerrarMesa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        $mesaId = $_POST['mesa_id'] ?? null;
        $metodoPago = $_POST['metodo_pago'] ?? null;
        $propina = $_POST['propina'] ?? 0;

        if (!$mesaId || !$metodoPago) {
            return json_encode(['error' => 'Datos incompletos']);
        }

        try {
            // Iniciar transacción
            $this->db->beginTransaction();

            // Obtener cuenta actual
            $cuenta = $this->cuentaModel->getCuentaByMesa($mesaId);
            
            // Calcular total final con propina
            $totalFinal = $cuenta['total'] + $propina;

            // Registrar pago
            $pago = [
                'cuenta_id' => $cuenta['id'],
                'metodo_pago' => $metodoPago,
                'monto' => $totalFinal,
                'propina' => $propina,
                'fecha' => date('Y-m-d H:i:s')
            ];
            
            $this->cuentaModel->registrarPago($pago);

            // Actualizar estado de la mesa
            $this->mesaModel->actualizarEstado($mesaId, 'disponible');

            // Cerrar cuenta
            $this->cuentaModel->cerrarCuenta($cuenta['id']);

            $this->db->commit();
            return json_encode(['success' => true, 'message' => 'Mesa cerrada exitosamente']);

        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode(['error' => 'Error al cerrar la mesa: ' . $e->getMessage()]);
        }
    }

    // Modificar items de una cuenta
    public function modificarCuenta() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        $cuentaId = $_POST['cuenta_id'] ?? null;
        $items = $_POST['items'] ?? null; // Array de items modificados

        if (!$cuentaId || !$items) {
            return json_encode(['error' => 'Datos incompletos']);
        }

        try {
            $this->db->beginTransaction();

            foreach ($items as $item) {
                switch ($item['accion']) {
                    case 'agregar':
                        $this->pedidoModel->agregarItem($cuentaId, $item);
                        break;
                    case 'quitar':
                        $this->pedidoModel->quitarItem($item['pedido_id']);
                        break;
                    case 'modificar':
                        $this->pedidoModel->modificarCantidad($item['pedido_id'], $item['cantidad']);
                        break;
                }
            }

            // Recalcular total de la cuenta
            $this->cuentaModel->actualizarTotal($cuentaId);

            $this->db->commit();
            return json_encode(['success' => true, 'message' => 'Cuenta actualizada exitosamente']);

        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode(['error' => 'Error al modificar la cuenta: ' . $e->getMessage()]);
        }
    }

    // Asignar mesa a mozo
    public function asignarMesa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        $mesaId = $_POST['mesa_id'] ?? null;
        $mozoId = $_POST['mozo_id'] ?? null;

        if (!$mesaId || !$mozoId) {
            return json_encode(['error' => 'Datos incompletos']);
        }

        try {
            // Verificar disponibilidad del mozo
            $mesasActivas = $this->mozoModel->getMesasActivas($mozoId);
            $maximoMesas = $this->mozoModel->getMaximoMesas($mozoId);

            if (count($mesasActivas) >= $maximoMesas) {
                return json_encode(['error' => 'El mozo ha alcanzado su límite de mesas']);
            }

            // Asignar mesa
            $this->mesaModel->asignarMozo($mesaId, $mozoId);
            
            return json_encode([
                'success' => true, 
                'message' => 'Mesa asignada exitosamente'
            ]);

        } catch (Exception $e) {
            return json_encode(['error' => 'Error al asignar mesa: ' . $e->getMessage()]);
        }
    }

    // Obtener estadísticas de ventas
    public function getEstadisticas() {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        try {
            $stats = [
                'ventas_total' => $this->cuentaModel->getVentasTotal($fecha),
                'propinas_total' => $this->cuentaModel->getPropinasTotal($fecha),
                'mesas_atendidas' => $this->mesaModel->getMesasAtendidas($fecha),
                'ticket_promedio' => $this->cuentaModel->getTicketPromedio($fecha),
                'metodos_pago' => $this->cuentaModel->getMetodosPago($fecha),
                'horas_pico' => $this->cuentaModel->getHorasPico($fecha)
            ];

            return json_encode(['success' => true, 'data' => $stats]);

        } catch (Exception $e) {
            return json_encode(['error' => 'Error al obtener estadísticas: ' . $e->getMessage()]);
        }
    }

    // Generar comprobante de pago
    public function generarComprobante($cuentaId) {
        try {
            $cuenta = $this->cuentaModel->getCuentaById($cuentaId);
            $items = $this->pedidoModel->getPedidosByCuenta($cuentaId);
            $mesa = $this->mesaModel->getMesaById($cuenta['mesa_id']);
            $mozo = $this->mozoModel->getMozoById($mesa['mozo_id']);

            $data = [
                'cuenta' => $cuenta,
                'items' => $items,
                'mesa' => $mesa,
                'mozo' => $mozo,
                'fecha' => date('Y-m-d H:i:s')
            ];

            // Generar comprobante (PDF o impresión)
            $comprobante = $this->generatePDF('templates/comprobante', $data);
            
            return $comprobante;

        } catch (Exception $e) {
            return json_encode(['error' => 'Error al generar comprobante: ' . $e->getMessage()]);
        }
    }

    // Dividir cuenta
    public function dividirCuenta() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        $cuentaId = $_POST['cuenta_id'] ?? null;
        $divisiones = $_POST['divisiones'] ?? null; // Array con la distribución de items

        if (!$cuentaId || !$divisiones) {
            return json_encode(['error' => 'Datos incompletos']);
        }

        try {
            $this->db->beginTransaction();

            $cuentaOriginal = $this->cuentaModel->getCuentaById($cuentaId);
            
            foreach ($divisiones as $division) {
                // Crear nueva cuenta
                $nuevaCuentaId = $this->cuentaModel->crearCuenta([
                    'mesa_id' => $cuentaOriginal['mesa_id'],
                    'estado' => 'pendiente'
                ]);

                // Asignar items a la nueva cuenta
                foreach ($division['items'] as $item) {
                    $this->pedidoModel->asignarACuenta($item['pedido_id'], $nuevaCuentaId, $item['cantidad']);
                }

                // Actualizar totales
                $this->cuentaModel->actualizarTotal($nuevaCuentaId);
            }

            $this->db->commit();
            return json_encode(['success' => true, 'message' => 'Cuenta dividida exitosamente']);

        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode(['error' => 'Error al dividir la cuenta: ' . $e->getMessage()]);
        }
    }
}

}*/
?>