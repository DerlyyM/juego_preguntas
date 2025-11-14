<?php
session_start();
require '../config/conexion.php';

$datos = json_decode(file_get_contents("php://input"), true);
$pregunta_id = $datos['pregunta_id'];
$opcion = $datos['opcion'];
$jugador_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT opcion_correcta, puntos FROM preguntas WHERE id = ?");
$stmt->execute([$pregunta_id]);
$p = $stmt->fetch();

if (!$p) die(json_encode(['error' => 'Pregunta no encontrada']));

$correcto = strtolower($opcion) === strtolower($p['opcion_correcta']);
$puntos = $correcto ? $p['puntos'] : 0;

// Actualizar puntaje
if ($correcto) {
    $pdo->prepare("UPDATE jugadores SET puntaje = puntaje + ? WHERE id = ?")->execute([$puntos, $jugador_id]);
}

// Obtener puntaje total actual
$total = $pdo->query("SELECT puntaje FROM jugadores WHERE id = $jugador_id")->fetchColumn();

// Actualizar nivel si aplica
if ($total >= 1200) {
    $pdo->exec("UPDATE jugadores SET nivel_id = 3 WHERE id = $jugador_id");
} elseif ($total >= 500) {
    $pdo->exec("UPDATE jugadores SET nivel_id = 2 WHERE id = $jugador_id");
}

header('Content-Type: application/json');
echo json_encode(['correcto' => $correcto, 'puntos' => $puntos]);
