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
    public function obtenerSaldoInicial($fecha = null)
{
    // Tomar la fecha del día consultado
    $fechaHoy = $fecha ?: date('Y-m-d');
    // Buscar el saldo_inicial de la última caja abierta ese día (por si se abrió varias veces)
    $sql = "SELECT saldo_inicial FROM cajas WHERE DATE(fecha_apertura) = :fechaHoy ORDER BY fecha_apertura DESC LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['fechaHoy' => $fechaHoy]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $fila ? floatval($fila['saldo_inicial']) : 0.0;
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

    // Nuevos cálculos
    $inicioCaja = $this->obtenerSaldoInicial($fecha);
    $cajaFuerte = $this->obtenerCajaFuerteDelDia($fecha);
    $gastos     = $this->obtenerGastosDelDia($fecha);

    $datos['efectivo_total']    = $pagos['efectivo']['total'];
    $datos['efectivo_cantidad'] = $pagos['efectivo']['cantidad'];
    $datos['mercadopago']       = $pagos['mercadopago']['total'];
    $datos['mercadopago_cantidad'] = $pagos['mercadopago']['cantidad'];
    $datos['tarjetas']          = $pagos['tarjeta']['total'];
    $datos['tarjetas_cantidad'] = $pagos['tarjeta']['cantidad'];
    $datos['qr']                = $pagos['qr']['total'];
    $datos['qr_cantidad']       = $pagos['qr']['cantidad'];
    $datos['inicio_caja']       = $inicioCaja;
    $datos['efectivo_ventas']   = $pagos['efectivo']['total'];
    $datos['caja_fuerte'] = $this->obtenerCajaFuerteDelDia($fecha);
    $datos['gastos']            = $gastos;
    $datos['saldo'] = $pagos['efectivo']['total'] + $datos['inicio_caja'] - $datos['caja_fuerte'];

    return $datos;
}
   public function obtenerCajaFuerteDelDia($fecha = null)
{
    $fechaHoy = $fecha ?: date('Y-m-d');
    $sql = "SELECT COALESCE(SUM(monto), 0) as total FROM caja_fuerte WHERE DATE(fecha) = :fechaHoy";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['fechaHoy' => $fechaHoy]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $fila ? floatval($fila['total']) : 0.0;
}

public function obtenerGastosDelDia($fecha = null)
{
    $fechaHoy = $fecha ?: date('Y-m-d');
    $sql = "SELECT COALESCE(SUM(monto),0) as total FROM gastos WHERE DATE(fecha) = :fechaHoy";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['fechaHoy' => $fechaHoy]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $fila ? floatval($fila['total']) : 0.0;
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