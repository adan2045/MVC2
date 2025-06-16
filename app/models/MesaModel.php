<?php
namespace app\models;

use \DataBase;

class MesaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerTodas()
    {
        $sql = "SELECT * FROM mesas ORDER BY id DESC";
        return $this->db->query($sql, [], true);
    }

    public function obtenerPorNumero($numero)
{
    $sql = "SELECT * FROM mesas WHERE numero = ?";
    $resultado = $this->db->query($sql, [$numero], true);
    return $resultado[0] ?? null;
}

    public function obtenerConTotales()
{
    $sql = "
        SELECT 
            m.id,
            m.numero,
            m.estado,
            COALESCE(SUM(p.precio * pd.cantidad), 0) AS total
        FROM mesas m
        LEFT JOIN pedidos pe ON pe.mesa_id = m.id 
            AND DATE(pe.fecha) = CURDATE()
            AND pe.cerrado = 0
        LEFT JOIN pedido_detalle pd ON pd.pedido_id = pe.id
        LEFT JOIN productos p ON p.id = pd.producto_id
        GROUP BY m.id, m.numero, m.estado
        ORDER BY m.numero;
    ";
    return $this->db->query($sql, [], true);
}
    
    public function existeNumero($numero)
    {
        $sql = "SELECT COUNT(*) FROM mesas WHERE numero = ?";
        $stmt = DataBase::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([$numero]);
        return $stmt->fetchColumn() > 0;
    }

    public function existeNumeroEnOtraMesa($numero, $idActual)
    {
        $sql = "SELECT COUNT(*) FROM mesas WHERE numero = ? AND id != ?";
        $stmt = DataBase::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([$numero, $idActual]);
        return $stmt->fetchColumn() > 0;
    }
// guardar por numero de mesa 
    public function guardar($qr_code, $estado, $link_qr, $numero)
{
    // ✅ Incluir la librería con ruta segura
    require_once APP_PATH . 'librerias/php/phpqrcode/phpqrcode.php';

    // Generar el link personalizado para la mesa
    $linkMesa = "http://localhost/MVC2/menu/menu?mesa=" . $numero;

    // Ruta absoluta donde guardar la imagen
    $rutaCarpeta = APP_PATH . "../public/img/imagenes_qr/";
    $nombreArchivo = $numero . ".png";
    $rutaQR = $rutaCarpeta . $nombreArchivo;

    // Crear carpeta si no existe
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    // Generar el QR
    \QRcode::png($linkMesa, $rutaQR, QR_ECLEVEL_L, 5);

    // Ruta pública para mostrar desde el navegador
    $rutaPublicaQR = "/public/img/imagenes_qr/" . $nombreArchivo;

    // Insertar en la base de datos
    $sql = "INSERT INTO mesas (qr_code, estado, link_qr, numero) VALUES (?, ?, ?, ?)";
    return $this->db->execute($sql, [$rutaPublicaQR, $estado, $linkMesa, $numero]);
}
/*crea el qr por id 
   public function guardar($qr_code, $estado, $link_qr, $numero)
{
    // Incluir la librería correctamente
    require_once APP_PATH . 'librerias/php/phpqrcode/phpqrcode.php';

    // Primero insertamos la mesa sin el QR para obtener su ID
    $sql = "INSERT INTO mesas (qr_code, estado, link_qr, numero) VALUES ('', ?, '', ?)";
    $this->db->execute($sql, [$estado, $numero]);

    // Obtener el ID recién insertado
    $db = \DataBase::getInstance()->getConnection();
    $idInsertado = $db->lastInsertId();

    // Generar el link usando el ID
    $linkMesa = "http://localhost/MVC2/menu/menu?mesa=" . $idInsertado;

    // Ruta donde guardar el QR
    $rutaCarpeta = APP_PATH . "../public/img/imagenes_qr/";
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    $nombreArchivo = 'mesa_' . $idInsertado . ".png";
    $rutaQR = $rutaCarpeta . $nombreArchivo;

    // Generar QR
    \QRcode::png($linkMesa, $rutaQR, QR_ECLEVEL_L, 5);

    // Ruta pública del QR
    $rutaPublicaQR = "/public/img/imagenes_qr/" . $nombreArchivo;

    // Actualizar la mesa insertada con los valores de QR y link
    $updateSql = "UPDATE mesas SET qr_code = ?, link_qr = ? WHERE id = ?";
    $this->db->execute($updateSql, [$rutaPublicaQR, $linkMesa, $idInsertado]);

    return true;
}*/

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM mesas WHERE id = ?";
        $resultado = $this->db->query($sql, [$id], true);
        return $resultado[0] ?? null;
    }

    public function actualizar($id, $qr_code, $estado, $link_qr, $numero)
    {
        $sql = "UPDATE mesas SET qr_code = ?, estado = ?, link_qr = ?, numero = ? WHERE id = ?";
        return $this->db->execute($sql, [$qr_code, $estado, $link_qr, $numero, $id]);
    }

    public function cambiarEstado($id, $estado)
{
    $conn = \DataBase::getInstance()->getConnection();
    $sql = "UPDATE mesas SET estado = :estado WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['estado' => $estado, 'id' => $id]);
}

    public function eliminar($id)
    {
        $sql = "DELETE FROM mesas WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    public function actualizarEstado($id, $estado)
{
    $db = \DataBase::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE mesas SET estado = ? WHERE id = ?");
    return $stmt->execute([$estado, $id]);
}

// ⬇️ Pegá justo aquí
public function cerrarMesaYSolicitarCuenta($mesaId)
{
    try {
        $db = \DataBase::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE mesas SET estado = 'cuenta_solicitada' WHERE id = ?");
        $stmt->execute([$mesaId]);

        // 🧾 Aquí podrías imprimir el ticket si lo necesitás más adelante
        // echo "<script>window.print();</script>";

        return true;
    } catch (\Exception $e) {
        return false;
    }
}
}