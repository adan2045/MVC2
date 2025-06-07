<?php
namespace app\models;

use \DataBase;

class UsuarioModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function guardar($nombre, $apellido, $email, $password, $dni, $rol)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, dni, rol)
                VALUES (?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $nombre,
            $apellido,
            $email,
            $passwordHash,
            $dni,
            $rol
        ]);
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";

        // Devuelve los resultados como arrays asociativos para usar $usuario['campo']
        return $this->db->query($sql, [], true);
    }

    // Más funciones: editar, listar, eliminar...
}