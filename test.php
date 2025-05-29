<?php
require 'config/database.php';
$stmt = $pdo->query("SELECT * FROM mesas");  // Consulta de prueba
print_r($stmt->fetchAll());
?>