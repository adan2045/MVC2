php
<?php
$host = 'localhost';      
$dbname = 'bar_db';       
$user = 'root';           
$password = '';           

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Conexión exitosa";  
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>