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


            // CONSEGUÍ EL CAJA_ID ACTUAL
            $cajaModel = new \app\models\CajaModel();
            $cajaId = $cajaModel->obtenerCajaIdActual();

            // Ahora insertamos cerrado = 0 sí o sí, y el caja_id
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

    public function crearPedido($mesaId, $mozoId)
    {
        $db = \DataBase::getInstance()->getConnection();

        // Busca si ya hay uno abierto hoy para esta mesa
        $stmt = $db->prepare("SELECT id FROM pedidos WHERE mesa_id = ? AND cerrado = 0 AND DATE(fecha) = CURDATE()");
        $stmt->execute([$mesaId]);
        $pedido = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($pedido) {
            // Ya hay un pedido abierto, devolvé su ID
            return $pedido['id'];
        } else {
            // Crear nuevo pedido...
            // ...
        }
    }

    // MÉTODO CORREGIDO: Solo cierra los pedidos ABIERTOS de hoy para la mesa
    public function cerrarPedidosDeHoyPorMesa($mesaId, $medioPago)
    {
        $db = \DataBase::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Primero, calculamos el total real basado en los productos del pedido
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

            // Ahora actualizamos el total y cerramos SOLO los pedidos abiertos de hoy
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

    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }

    public function obtenerDetalleCuentaPorMesa($mesaId)
    {
        $db = \DataBase::getInstance()->getConnection();

        $sql = "
            SELECT p.id AS pedido_id, pr.nombre AS nombre, pr.nombre AS producto, pr.descripcion, pd.cantidad, pr.precio, (pd.cantidad * pr.precio) AS subtotal
            FROM pedidos p
            JOIN pedido_detalle pd ON p.id = pd.pedido_id
            JOIN productos pr ON pd.producto_id = pr.id
            WHERE p.mesa_id = ? AND DATE(p.fecha) = CURDATE() AND p.cerrado = 0
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$mesaId]);
        $productos = $stmt->fetchAll();

        $total = 0;
        foreach ($productos as $p) {
            $total += $p['subtotal'];
        }

        $mesa = $db->prepare("SELECT * FROM mesas WHERE id = ?");
        $mesa->execute([$mesaId]);

        return [
            'mesa' => $mesa->fetch(),
            'productos' => $productos,
            'total' => $total
        ];
    }

    public function actualizarEstadoProducto($detalleId, $estado)
    {
        $stmt = $this->db->prepare("UPDATE pedido_detalle SET estado = :estado WHERE id = :id");
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $detalleId
        ]);
    }

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