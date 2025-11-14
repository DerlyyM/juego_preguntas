<?php
session_start();
require '../config/conexion.php';

// Verificar acceso del admin
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

// Filtros
$where = "1";
$params = [];

if (!empty($_GET['jugador_id'])) {
    $where .= " AND u.id = ?";
    $params[] = $_GET['jugador_id'];
}
if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
    $where .= " AND r.fecha BETWEEN ? AND ?";
    $params[] = $_GET['desde'];
    $params[] = $_GET['hasta'];
}

// Consulta principal de reportes
$sql = "SELECT 
            r.id,
            u.usuario, 
            r.puntaje_total, 
            r.nivel_alcanzado, 
            r.fecha
        FROM reportes r
        JOIN usuarios u ON r.jugador_id = u.id
        WHERE $where 
        ORDER BY r.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener jugadores para el filtro
$jugadores = $pdo->query("SELECT id, usuario FROM usuarios WHERE rol_id != 1 ORDER BY usuario ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes de Jugadores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #36b9cc, #4e73df);
      min-height: 100vh;
      padding: 30px;
      font-family: "Poppins", sans-serif;
    }

    .contenedor {
      background: #ffffffdd;
      backdrop-filter: blur(8px);
      padding: 30px;
      border-radius: 25px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      animation: fadeIn 0.6s ease-out;
    }

    h2 {
      font-weight: 900;
      color: #2e2e2e;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: 600;
    }

    table {
      margin-top: 20px;
      border-radius: 12px;
      overflow: hidden;
    }

    thead {
      background: #4e73df;
      color: white;
    }

    tbody tr:hover {
      background: #eef3ff;
    }

    .btn-filtrar {
      background: #1cc88a;
      color: white;
      font-weight: bold;
      border-radius: 12px;
      padding: 10px 15px;
      width: 100%;
    }

    .btn-volver {
      display: block;
      width: 180px;
      margin: 20px auto 0;
      text-align: center;
      background: #e74a3b;
      color: white;
      padding: 12px;
      font-weight: bold;
      border-radius: 12px;
      text-decoration: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<div class="contenedor">

  <h2>📊 REPORTES DE JUGADAS</h2>

  <!-- FILTROS -->
  <form class="row g-3 mb-4" method="GET">

    <div class="col-md-4">
      <label class="form-label">Jugador</label>
      <select class="form-select" name="jugador_id">
        <option value="">Todos</option>
        <?php foreach ($jugadores as $j): ?>
          <option value="<?= $j['id'] ?>"
            <?= (isset($_GET['jugador_id']) && $_GET['jugador_id'] == $j['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($j['usuario']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Desde</label>
      <input class="form-control" type="date" name="desde" 
             value="<?= $_GET['desde'] ?? '' ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hasta</label>
      <input class="form-control" type="date" name="hasta" 
             value="<?= $_GET['hasta'] ?? '' ?>">
    </div>

    <div class="col-md-2 d-flex align-items-end">
      <button class="btn-filtrar">Filtrar</button>
    </div>
  </form>

  <!-- TABLA -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Jugador</th>
          <th>Puntaje Total</th>
          <th>Nivel Alcanzado</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($reportes) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No se encontraron resultados</td>
            </tr>
        <?php else: ?>
          <?php foreach ($reportes as $r): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['usuario']) ?></td>
              <td><strong><?= $r['puntaje_total'] ?></strong></td>
              <td><?= htmlspecialchars($r['nivel_alcanzado']) ?></td>
              <td><?= $r['fecha'] ?></td>
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
