<?php
namespace app\models;

use \DataBase;

class CajaModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance()->getConnection();
    }

    // Totales del día agrupados por método de pago, usando SOLO pedidos cerrados
    public function obtenerTotalesDelDia($fecha = null)
    {
        $fechaHoy = $fecha ?: date('Y-m-d');

        // SUMA todos los pedidos cerrados, discriminando por método de pago
        $sql = "SELECT metodo_pago, SUM(total) as total, COUNT(*) as cantidad
                FROM pedidos
                WHERE cerrado = 1 AND DATE(fecha) = :fechaHoy
                GROUP BY metodo_pago";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['fechaHoy' => $fechaHoy]);
        $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $pagos = [
            'efectivo'     => ['total' => 0, 'cantidad' => 0],
            'tarjeta'      => ['total' => 0, 'cantidad' => 0],
            'mercadopago'  => ['total' => 0, 'cantidad' => 0],
            'qr'           => ['total' => 0, 'cantidad' => 0]
        ];

        foreach ($resultados as $row) {
            $metodo = strtolower(trim($row['metodo_pago'] ?? ''));
            if (isset($pagos[$metodo])) {
                $pagos[$metodo]['total'] = $row['total'];
                $pagos[$metodo]['cantidad'] = $row['cantidad'];
            }
        }

        // Total general del día
        $sqlTotal = "SELECT COALESCE(SUM(total), 0) as venta_bruta, COUNT(*) as cantidad_pedidos
                     FROM pedidos
                     WHERE cerrado = 1 AND DATE(fecha) = :fechaHoy";
        $stmt = $this->db->prepare($sqlTotal);
        $stmt->execute(['fechaHoy' => $fechaHoy]);
        $datos = $stmt->fetch(\PDO::FETCH_ASSOC);

        $datos['efectivo_total']    = $pagos['efectivo']['total'];
        $datos['efectivo_cantidad'] = $pagos['efectivo']['cantidad'];
        $datos['mercadopago']       = $pagos['mercadopago']['total'];
        $datos['mercadopago_cantidad'] = $pagos['mercadopago']['cantidad'];
        $datos['tarjetas']          = $pagos['tarjeta']['total'];
        $datos['tarjetas_cantidad'] = $pagos['tarjeta']['cantidad'];
        $datos['qr']                = $pagos['qr']['total'];
        $datos['qr_cantidad']       = $pagos['qr']['cantidad'];
        $datos['inicio_caja']       = 0;
        $datos['efectivo_ventas']   = $pagos['efectivo']['total'];
        $datos['caja_fuerte']       = 0;
        $datos['saldo']             = $pagos['efectivo']['total'];

        return $datos;
    }

    // Resumen por producto
    public function resumenPorProducto($fecha = null)
    {
        $fechaHoy = $fecha ?: date('Y-m-d');
        $sql = "SELECT p.nombre, SUM(pd.cantidad) as cantidad, SUM(pd.cantidad * p.precio) as total
                FROM pedido_detalle pd
                JOIN pedidos pe ON pd.pedido_id = pe.id
                JOIN productos p ON pd.producto_id = p.id
                WHERE pe.cerrado = 1 AND DATE(pe.fecha) = :fechaHoy
                GROUP BY p.nombre
                ORDER BY total DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['fechaHoy' => $fechaHoy]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}