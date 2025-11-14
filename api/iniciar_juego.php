<?php
session_start();
require '../config/conexion.php';

if (empty($_SESSION['usuario_id'])) {
    die(json_encode(['error' => 'No autenticado']));
}

$jugador_id = $_SESSION['usuario_id'];

// Obtener nivel actual del jugador
$stmt = $pdo->prepare("SELECT nivel_id FROM jugadores WHERE id = ?");
$stmt->execute([$jugador_id]);
$jugador = $stmt->fetch();

if (!$jugador) die(json_encode(['error' => 'Jugador no encontrado']));

$nivel_id = $jugador['nivel_id'];

// Cantidad de preguntas según nivel
$limites = [1 => 10, 2 => 13, 3 => 15];
$limite = $limites[$nivel_id] ?? 10;

// Seleccionar preguntas aleatorias
$sql = "SELECT id, texto, opcion_a, opcion_b, opcion_c, opcion_d, imagen FROM preguntas 
        WHERE nivel_id = ? ORDER BY RAND() LIMIT ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nivel_id, $limite]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($preguntas);
