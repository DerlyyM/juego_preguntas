<?php
$host = "localhost";
$dbname = "juego_preguntas";
$user = "root";
$pass = ""; // (o tu contraseña si tienes)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
