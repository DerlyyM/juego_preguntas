<?php
session_start();
require '../config/conexion.php';
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) die('Acceso denegado.');

if (isset($_POST['eliminar_id'])) {
    $id = $_POST['eliminar_id'];
    $pdo->prepare("DELETE FROM preguntas WHERE id = ?")->execute([$id]);
    $mensaje = "Pregunta eliminada correctamente.";
}

$preguntas = $pdo->query("SELECT p.*, n.nombre AS nivel FROM preguntas p JOIN niveles n ON p.nivel_id=n.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Preguntas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Listado de Preguntas</h2>
  <?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= $mensaje ?></div><?php endif; ?>
  <a href="agregar_pregunta.php" class="btn btn-primary mb-3">Agregar Nueva Pregunta</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nivel</th>
        <th>Tema</th>
        <th>Texto</th>
        <th>Puntos</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($preguntas as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nivel'] ?></td>
        <td><?= $p['tema'] ?></td>
        <td><?= substr($p['texto'],0,60) ?>...</td>
        <td><?= $p['puntos'] ?></td>
        <td><?= $p['imagen'] ? 'Sí' : 'No' ?></td>
        <td>
          <a href="editar_pregunta.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
          <form method="POST" style="display:inline-block">
            <input type="hidden" name="eliminar_id" value="<?= $p['id'] ?>">
            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta pregunta?')">Eliminar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
