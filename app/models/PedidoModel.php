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

            // Insertar en pedidos (encabezado)
            $stmt = $this->db->prepare("INSERT INTO pedidos (mesa_id, mozo_id, total) VALUES (?, ?, ?)");
            $stmt->execute([$mesaId, $mozoId, $total]);
            $pedidoId = $this->db->lastInsertId();

            // Insertar en pedido_detalle
            $stmtDetalle = $this->db->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)");

            foreach ($productos as $prod) {
                $stmtDetalle->execute([$pedidoId, $prod['id'], $prod['cantidad']]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    public function obtenerPedidosDelDiaConDetalle()
{
    $sql = "
        SELECT
            p.id AS pedido_id,
            m.numero AS mesa_numero,
            u.nombre AS mozo_nombre,
            u.apellido AS mozo_apellido,
            pd.cantidad,
            pr.nombre AS producto_nombre,
            p.estado,
            DATE_FORMAT(p.fecha, '%H:%i') AS hora
        FROM pedidos p
        JOIN mesas m ON p.mesa_id = m.id
        LEFT JOIN usuarios u ON p.mozo_id = u.id
        JOIN pedido_detalle pd ON pd.pedido_id = p.id
        JOIN productos pr ON pr.id = pd.producto_id
        WHERE DATE(p.fecha) = CURDATE()
        ORDER BY p.id DESC
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
}