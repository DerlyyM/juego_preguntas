<?php
session_start();
require '../config/conexion.php';

// Solo el admin puede entrar
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🎮 Panel Admin</a>
    <div class="d-flex">
        <span class="text-white me-3">👋 Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
        <a href="../logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Panel Principal de Administración</h2>

    <div class="row g-4 justify-content-center">

        <!-- Gestionar Preguntas -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">📘 Banco de Preguntas</h5>
                    <p class="card-text">Agrega, edita o elimina preguntas de los diferentes niveles.</p>
                    <a href="preguntas.php" class="btn btn-primary w-100">Gestionar Preguntas</a>
                </div>
            </div>
        </div>

        <!-- Aprobar Jugadores -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">👥 Aprobar Jugadores</h5>
                    <p class="card-text">Revisa los jugadores registrados y aprueba o desactiva cuentas.</p>
                    <a href="aprobar_usuarios.php" class="btn btn-success w-100">Ver Jugadores</a>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">📊 Reportes de Jugadas</h5>
                    <p class="card-text">Consulta resultados de las partidas por jugador o por fecha.</p>
                    <a href="reportes.php" class="btn btn-warning w-100">Ver Reportes</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center mt-5 text-muted">
    <p>© <?= date('Y') ?> Sistema de Juego de Preguntas - Proyecto Formativo</p>
</footer>

</body>
</html>
