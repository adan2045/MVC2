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

    /**
     * Obtener todas las mesas ordenadas por ID descendente.
     */
    public function obtenerTodas()
    {
        $sql = "SELECT * FROM mesas ORDER BY id DESC";
        return $this->db->query($sql, [], true);
    }

    /**
     * Insertar nueva mesa.
     */
    public function guardar($qr_code, $estado, $link_qr, $numero)
    {
        $sql = "INSERT INTO mesas (qr_code, estado, link_qr, numero) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$qr_code, $estado, $link_qr, $numero]);
    }

    /**
     * Obtener una mesa por su ID.
     */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM mesas WHERE id = ?";
        $resultado = $this->db->query($sql, [$id], true);
        return $resultado[0] ?? null;
    }

    /**
     * Actualizar los datos de una mesa.
     */
    public function actualizar($id, $qr_code, $estado, $link_qr, $numero)
    {
        $sql = "UPDATE mesas SET qr_code = ?, estado = ?, link_qr = ?, numero = ? WHERE id = ?";
        return $this->db->execute($sql, [$qr_code, $estado, $link_qr, $numero, $id]);
    }

    /**
     * Eliminar una mesa por su ID.
     */
    public function eliminar($id)
    {
        $sql = "DELETE FROM mesas WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}