<?php
session_start();
require '../config/conexion.php';

// Verificar acceso del administrador
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

// --- Agregar pregunta ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $texto = $_POST['texto'];
    $nivel_id = $_POST['nivel_id'];
    $opcion_a = $_POST['opcion_a'];
    $opcion_b = $_POST['opcion_b'];
    $opcion_c = $_POST['opcion_c'];
    $opcion_d = $_POST['opcion_d'];
    $opcion_correcta = $_POST['opcion_correcta'];
    $puntos = $_POST['puntos'];

    // Subida de imagen opcional
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImg = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $ruta = "../uploads/" . $nombreImg;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            $imagen = $nombreImg;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO preguntas (texto, nivel_id, opcion_a, opcion_b, opcion_c, opcion_d, opcion_correcta, puntos, imagen)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$texto, $nivel_id, $opcion_a, $opcion_b, $opcion_c, $opcion_d, $opcion_correcta, $puntos, $imagen]);

    header("Location: gestionar_preguntas.php");
    exit;
}

// --- Eliminar pregunta ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $pdo->prepare("DELETE FROM preguntas WHERE id = ?")->execute([$id]);
    header("Location: gestionar_preguntas.php");
    exit;
}

// Consultar preguntas y niveles
$preguntas = $pdo->query("SELECT p.*, n.nombre AS nivel FROM preguntas p JOIN niveles n ON p.nivel_id = n.id ORDER BY p.nivel_id")->fetchAll();
$niveles = $pdo->query("SELECT * FROM niveles")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Preguntas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>

    body {
      background: linear-gradient(135deg, #4e73df, #36b9cc);
      min-height: 100vh;
      padding: 30px;
      font-family: "Poppins", sans-serif;
    }

    .contenedor {
      background: #ffffffee;
      backdrop-filter: blur(8px);
      padding: 35px;
      border-radius: 22px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
      animation: fadeIn 0.5s ease-out;
    }

    h2 {
      font-weight: 900;
      color: #2c2c2c;
      text-align: center;
      margin-bottom: 20px;
    }

    .card {
      border-radius: 18px;
      box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
      border: none;
    }

    label {
      font-weight: 600;
    }

    .btn-guardar {
      background: #1cc88a;
      border: none;
      font-weight: bold;
      padding: 12px;
      border-radius: 12px;
      width: 100%;
      margin-top: 10px;
      transition: .2s;
    }

    .btn-guardar:hover {
      transform: scale(1.03);
    }

    .btn-volver {
      background: #e74a3b;
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 12px 20px;
      border-radius: 12px;
      display: block;
      width: 150px;
      margin: 20px auto;
      text-align: center;
    }

    /* Tabla */
    table {
      border-radius: 15px;
      overflow: hidden;
      margin-top: 25px;
    }

    thead {
      background: #4e73df;
      color: white;
    }

    tbody tr:hover {
      background: #eef2ff;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }

  </style>
</head>

<body>

<div class="contenedor">

  <h2>📚 Gestión de Preguntas</h2>

  <!-- FORMULARIO CREAR / EDITAR -->
  <div class="card p-4 mb-4">

    <h4 class="mb-3">➕ Agregar Nueva Pregunta</h4>

    <form method="POST" enctype="multipart/form-data">

      <div class="row g-3">

        <!-- Nivel -->
        <div class="col-md-4">
          <label>Nivel</label>
          <select name="nivel_id" class="form-select" required>
            <option value="">Seleccione nivel...</option>
            <option value="1">Básico</option>
            <option value="2">Intermedio</option>
            <option value="3">Alto</option>
          </select>
        </div>

        <!-- Tema -->
        <div class="col-md-8">
          <label>Tema</label>
          <input type="text" name="tema" class="form-control" placeholder="Ej: Pruebas, Diseño, Python..." required>
        </div>

        <!-- Texto principal -->
        <div class="col-12">
          <label>Texto de la pregunta</label>
          <textarea name="texto" class="form-control" rows="2" placeholder="Escribe la pregunta..." required></textarea>
        </div>

        <!-- Opciones -->
        <div class="col-md-6">
          <label>Opción A</label>
          <input type="text" name="opcion_a" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label>Opción B</label>
          <input type="text" name="opcion_b" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label>Opción C (opcional)</label>
          <input type="text" name="opcion_c" class="form-control">
        </div>
        <div class="col-md-6">
          <label>Opción D (opcional)</label>
          <input type="text" name="opcion_d" class="form-control">
        </div>

        <!-- Respuesta correcta -->
        <div class="col-md-4">
          <label>Respuesta correcta</label>
          <select name="opcion_correcta" class="form-select" required>
            <option value="a">A</option>
            <option value="b">B</option>
            <option value="c">C</option>
            <option value="d">D</option>
          </select>
        </div>

        <!-- Puntos -->
        <div class="col-md-4">
          <label>Puntaje</label>
          <input type="number" name="puntos" class="form-control" value="100" required>
        </div>

        <!-- Imagen -->
        <div class="col-md-4">
          <label>Imagen (opcional)</label>
          <input type="file" name="imagen" class="form-control">
        </div>

      </div>

      <button type="submit" class="btn-guardar">💾 Guardar Pregunta</button>

    </form>

  </div>


  <!-- TABLA PREGUNTAS -->
  <h4 class="mb-3">📋 Lista de preguntas registradas</h4>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nivel</th>
          <th>Tema</th>
          <th>Pregunta</th>
          <th>Puntos</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($preguntas)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No hay preguntas registradas.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($preguntas as $p): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td><?= $p['nivel_id'] ?></td>
              <td><?= htmlspecialchars($p['tema']) ?></td>
              <td><?= htmlspecialchars($p['texto']) ?></td>
              <td><?= $p['puntos'] ?></td>
              <td>
                <a href="eliminar_pregunta.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger">🗑 Eliminar</a>
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
