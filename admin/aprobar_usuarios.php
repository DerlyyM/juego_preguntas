<?php
session_start();
require '../config/conexion.php';

// Solo puede acceder el administrador
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

// Si el admin aprueba un usuario
if (isset($_GET['aprobar'])) {
    $id = (int)$_GET['aprobar'];
    $stmt = $pdo->prepare("UPDATE usuarios SET aprobado = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: aprobar_usuarios.php");
    exit;
}

// Si el admin rechaza o bloquea un usuario
if (isset($_GET['rechazar'])) {
    $id = (int)$_GET['rechazar'];
    $stmt = $pdo->prepare("UPDATE usuarios SET aprobado = 0 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: aprobar_usuarios.php");
    exit;
}

// Obtener todos los usuarios (menos el admin)
$usuarios = $pdo->query("SELECT id, usuario, email, rol_id, aprobado FROM usuarios WHERE rol_id != 1 ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aprobar Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>

    body {
      background: linear-gradient(135deg, #1cc88a, #36b9cc);
      min-height: 100vh;
      padding: 30px;
      font-family: "Poppins", sans-serif;
    }

    .contenedor {
      background: #ffffffee;
      backdrop-filter: blur(6px);
      padding: 35px;
      border-radius: 25px;
      max-width: 900px;
      margin: auto;
      box-shadow: 0 10px 35px rgba(0,0,0,0.2);
      animation: fadeIn 0.5s ease-out;
    }

    h2 {
      font-weight: 900;
      color: #2c2c2c;
      text-align: center;
      margin-bottom: 15px;
    }

    p.subtitulo {
      color: #555;
      text-align: center;
      font-size: 17px;
      margin-bottom: 28px;
    }

    table {
      border-radius: 15px;
      overflow: hidden;
    }

    thead {
      background: #4e73df;
      color: white;
    }

    tbody tr:hover {
      background: #eef2ff;
      cursor: pointer;
    }

    .btn-aprobar {
      background: #1cc88a;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 10px;
      font-weight: bold;
    }

    .btn-denegar {
      background: #e74a3b;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 10px;
      font-weight: bold;
    }

    .estado-ok {
      font-weight: bold;
      color: #1cc88a;
    }

    .estado-no {
      font-weight: bold;
      color: #e74a3b;
    }

    .btn-volver {
      display: block;
      width: 160px;
      margin: 25px auto 0;
      text-align: center;
      font-weight: 700;
      background: #4e73df;
      color: white;
      padding: 12px;
      border-radius: 12px;
      text-decoration: none;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }

  </style>
</head>

<body>

<div class="contenedor">

  <h2>✔ APROBAR USUARIOS</h2>
  <p class="subtitulo">Autoriza o rechaza a los usuarios registrados para permitir su acceso al sistema.</p>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>

      <?php if (empty($usuarios)): ?>
        <tr>
          <td colspan="6" class="text-muted text-center">No hay usuarios registrados.</td>
        </tr>

      <?php else: ?>
        <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['usuario']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= ($u['rol_id'] == 1 ? 'Administrador' : 'Jugador') ?></td>

          <td>
            <?php if ($u['aprobado'] == 1): ?>
              <span class="estado-ok">Aprobado</span>
            <?php else: ?>
              <span class="estado-no">Pendiente</span>
            <?php endif; ?>
          </td>

          <td>
            <?php if ($u['aprobado'] == 0): ?>
              <a href="aprobar_usuarios.php?aprobar=<?= $u['id'] ?>" class="btn-aprobar">Aprobar</a>
            <?php endif; ?>
            <a href="aprobar_usuarios.php?rechazar=<?= $u['id'] ?>" class="btn-denegar">Denegar</a>
          </td>

        </tr>
        <?php endforeach; ?>
      <?php endif; ?>

      </tbody>
    </table>
  </div>

  <a href="panel_admin.php" class="btn-volver">⬅ Volver</a>

</div>

</body>
</html>
