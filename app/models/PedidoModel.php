<?php
namespace app\models;

use \DataBase;

class PedidoModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance()->getConnection();
    }

    public function guardarPedido($mesaId, $mozoId, $total, $productos)
    {
        try {
            $this->db->beginTransaction();

            $cajaModel = new \app\models\CajaModel();
            $cajaId = $cajaModel->obtenerCajaIdActual();

            $stmt = $this->db->prepare("INSERT INTO pedidos (mesa_id, mozo_id, total, cerrado, caja_id) VALUES (?, ?, ?, 0, ?)");
            $stmt->execute([$mesaId, $mozoId, $total, $cajaId]);

            $pedidoId = $this->db->lastInsertId();

            $stmtDetalle = $this->db->prepare(
                "INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad, estado)
                 VALUES (?, ?, ?, 'pendiente')"
            );

            foreach ($productos as $p) {
                $stmtDetalle->execute([$pedidoId, $p['id'], $p['cantidad']]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function cerrarPedidosDeHoyPorMesa($mesaId, $medioPago)
    {
        $db = \DataBase::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $sqlTotal = "
                SELECT SUM(pd.cantidad * p.precio) as total_real
                FROM pedidos pe
                JOIN pedido_detalle pd ON pe.id = pd.pedido_id
                JOIN productos p ON pd.producto_id = p.id
                WHERE pe.mesa_id = ? AND pe.cerrado = 0 AND DATE(pe.fecha) = CURDATE()
            ";
            $stmtTotal = $db->prepare($sqlTotal);
            $stmtTotal->execute([$mesaId]);
            $resultado = $stmtTotal->fetch(\PDO::FETCH_ASSOC);
            $totalReal = $resultado['total_real'] ?? 0;

            $stmt = $db->prepare("
                UPDATE pedidos 
                SET cerrado = 1, metodo_pago = ?, total = ? 
                WHERE mesa_id = ? AND cerrado = 0 AND DATE(fecha) = CURDATE()
            ");
            $stmt->execute([$medioPago, $totalReal, $mesaId]);

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    /**
     * Devuelve los detalles y si el pedido está cerrado o no
     */
    public function obtenerDetalleCuentaPorPedido($pedidoId)
    {
        $db = $this->db;
        $stmt = $db->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$pedidoId]);
        $pedido = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$pedido) {
            return ['mesa' => [], 'productos' => [], 'total' => 0, 'cerrado' => 1];
        }

        $mesaId = $pedido['mesa_id'];
        $stmtMesa = $db->prepare("SELECT * FROM mesas WHERE id = ?");
        $stmtMesa->execute([$mesaId]);
        $mesa = $stmtMesa->fetch(\PDO::FETCH_ASSOC);
        if (!$mesa) $mesa = [];

        $stmtProd = $db->prepare(
            "SELECT pd.*, pr.nombre, pr.descripcion, pr.precio
             FROM pedido_detalle pd
             JOIN productos pr ON pd.producto_id = pr.id
             WHERE pd.pedido_id = ?"
        );
        $stmtProd->execute([$pedidoId]);
        $productos = $stmtProd->fetchAll(\PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($productos as $prod) {
            $total += $prod['precio'] * $prod['cantidad'];
        }
        return [
            'mesa' => $mesa,
            'productos' => $productos,
            'total' => $total,
            'cerrado' => $pedido['cerrado']
        ];
    }

    /**
     * Devuelve detalles del pedido abierto de una mesa (si existe).
     * Si hay pedido, trae pedido_id y cerrado.
     */
    public function obtenerDetalleCuentaPorMesa($mesaId)
    {
        $db = \DataBase::getInstance()->getConnection();

        // Buscar pedido (abierto o cerrado) de hoy, el último
        $stmtPedido = $db->prepare("SELECT id, cerrado FROM pedidos WHERE mesa_id = ? AND DATE(fecha) = CURDATE() ORDER BY id DESC LIMIT 1");
        $stmtPedido->execute([$mesaId]);
        $pedido = $stmtPedido->fetch(\PDO::FETCH_ASSOC);

        if (!$pedido) {
            $mesa = $db->prepare("SELECT * FROM mesas WHERE id = ?");
            $mesa->execute([$mesaId]);
            return [
                'mesa' => $mesa->fetch(),
                'productos' => [],
                'total' => 0,
                'cerrado' => 1
            ];
        }

        $pedidoId = $pedido['id'];
        $cerrado = $pedido['cerrado'];

        $stmtProd = $db->prepare(
            "SELECT pd.*, pr.nombre, pr.descripcion, pr.precio
             FROM pedido_detalle pd
             JOIN productos pr ON pd.producto_id = pr.id
             WHERE pd.pedido_id = ?"
        );
        $stmtProd->execute([$pedidoId]);
        $productos = $stmtProd->fetchAll(\PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($productos as $prod) {
            $total += $prod['precio'] * $prod['cantidad'];
        }

        $mesa = $db->prepare("SELECT * FROM mesas WHERE id = ?");
        $mesa->execute([$mesaId]);

        return [
            'mesa' => $mesa->fetch(),
            'productos' => $productos,
            'total' => $total,
            'cerrado' => $cerrado,
            'pedido_id' => $pedidoId
        ];
    }

    /**
     * Obtiene todos los pedidos del día con detalle de productos
     */
    public function obtenerPedidosDelDiaConDetalle()
    {
        $sql = "
            SELECT
                p.id AS pedido_id,
                m.numero AS mesa_numero,
                COALESCE(u.nombre, 'Cliente') AS mozo_nombre,
                u.apellido AS mozo_apellido,
                pd.id AS detalle_id,
                pd.estado AS detalle_estado,
                pd.cantidad,
                pr.nombre AS producto_nombre,
                DATE_FORMAT(p.fecha, '%H:%i') AS hora
            FROM pedidos p
            JOIN mesas m ON p.mesa_id = m.id
            LEFT JOIN usuarios u ON p.mozo_id = u.id
            JOIN pedido_detalle pd ON pd.pedido_id = p.id
            JOIN productos pr ON pr.id = pd.producto_id
            WHERE DATE(p.fecha) = CURDATE()
            ORDER BY p.id DESC, pd.id ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Actualiza el estado general del pedido (no de cada producto)
     */
    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }

    /**
     * Actualiza el estado de un producto dentro del pedido
     */
    public function actualizarEstadoProducto($detalleId, $estado)
    {
        $stmt = $this->db->prepare("UPDATE pedido_detalle SET estado = :estado WHERE id = :id");
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $detalleId
        ]);
    }

    /**
     * Obtiene todos los pedidos abiertos (no cerrados) por mesa
     */
    public function obtenerPedidosActivosPorMesa($mesaId)
    {
        $db = \DataBase::getInstance()->getConnection();
        $query = "SELECT p.id as pedido_id
                  FROM pedidos p
                  WHERE p.mesa_id = ? AND p.cerrado = 0";

        $stmt = $db->prepare($query);
        $stmt->execute([$mesaId]);
        $pedidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($pedidos as &$pedido) {
            $queryDetalle = "SELECT pd.cantidad, pr.nombre, pr.descripcion, pr.precio
                             FROM pedido_detalle pd
                             JOIN productos pr ON pd.producto_id = pr.id
                             WHERE pd.pedido_id = ?";
            $stmtDetalle = $db->prepare($queryDetalle);
            $stmtDetalle->execute([$pedido['pedido_id']]);
            $pedido['detalles'] = $stmtDetalle->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $pedidos;
    }

}