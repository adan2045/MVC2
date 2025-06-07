<?php
namespace app\models;

use \DataBase;

class ProductoModel
{
    private $db;

    public function __construct()
{
    // ✅ Esta es la forma correcta de obtener la instancia PDO
    $this->db = DataBase::getInstance()->getConnection();
}

    public function obtenerTodos()
{
    $stmt = $this->db->prepare("SELECT * FROM productos");
    $stmt->execute();
    return $stmt->fetchAll();
}

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function guardar($nombre, $descripcion, $precio, $categoria)
    {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria]);
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $categoria)
    {
        $stmt = $this->db->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria = ? WHERE id = ?");
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria, $id]);
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
