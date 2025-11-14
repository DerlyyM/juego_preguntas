<?php
session_start();
require '../config/conexion.php';

// Verificar que el usuario esté autenticado y sea administrador
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

// Obtener nombre del admin actual
$stmt = $pdo->prepare("SELECT usuario FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$nombreAdmin = $admin ? $admin['usuario'] : 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #4e73df, #1cc88a);
      min-height: 100vh;
      padding: 40px 20px;
      font-family: "Poppins", sans-serif;
    }

    .panel {
      background: #ffffffdd;
      backdrop-filter: blur(6px);
      padding: 35px;
      border-radius: 25px;
      max-width: 850px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      animation: fadeIn 0.6s ease-out;
    }

    .panel h2 {
      font-weight: 900;
      color: #2e2e2e;
      margin-bottom: 10px;
    }

    .subtitulo {
      color: #555;
      font-size: 18px;
      margin-bottom: 25px;
    }

    .card-admin {
      background: #fff;
      padding: 25px;
      border-radius: 18px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: center;
      transition: 0.2s;
      border: none;
    }

    .card-admin:hover {
      transform: scale(1.04);
    }

    .icon-admin {
      font-size: 50px;
      margin-bottom: 10px;
    }

    .btn-basic {
      text-decoration: none;
      display: block;
      padding: 10px;
      font-size: 16px;
      border-radius: 12px;
      margin-top: 10px;
      font-weight: bold;
      color: white;
    }

    /* Animación */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<div class="panel text-center">
  
  <h2> PANEL ADMINISTRATIVO</h2>
  <p class="subtitulo">Bienvenido, <strong><?= htmlspecialchars($nombreAdmin) ?></strong></p>

  <div class="row g-4 mt-4">

    <!-- Gestionar Preguntas -->
    <div class="col-md-6">
      <div class="card-admin">
        <div class="icon-admin">📚</div>
        <h5>Gestionar Preguntas</h5>
        <a href="gestionar_preguntas.php" class="btn-basic" style="background:#4e73df;">
          Entrar
        </a>
      </div>
    </div>

    <!-- Aprobar Usuarios -->
    <div class="col-md-6">
      <div class="card-admin">
        <div class="icon-admin">✔</div>
        <h5>Aprobar Usuarios</h5>
        <a href="aprobar_usuarios.php" class="btn-basic" style="background:#1cc88a;">
          Entrar
        </a>
      </div>
    </div>

    <!-- Reportes -->
    <div class="col-md-6">
      <div class="card-admin">
        <div class="icon-admin">📊</div>
        <h5>Ver Reportes</h5>
        <a href="reportes.php" class="btn-basic" style="background:#f6c23e; color:black;">
          Entrar
        </a>
      </div>
    </div>

    <!-- Cerrar Sesión -->
    <div class="col-md-6">
      <div class="card-admin">
        <div class="icon-admin">🚪</div>
        <h5>Cerrar Sesión</h5>
        <a href="../logout.php" class="btn-basic" style="background:#e74a3b;">
          Salir
        </a>
      </div>
    </div>

  </div>

</div>

</body>
</html>

