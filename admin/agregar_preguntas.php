<?php
session_start();
require '../config/conexion.php';

// Verificar que sea administrador
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

$niveles = $pdo->query('SELECT id, nombre FROM niveles')->fetchAll();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nivel_id = $_POST['nivel_id'];
    $tema = $_POST['tema'];
    $texto = $_POST['texto'];
    $opcion_a = $_POST['opcion_a'];
    $opcion_b = $_POST['opcion_b'];
    $opcion_c = $_POST['opcion_c'];
    $opcion_d = $_POST['opcion_d'];
    $opcion_correcta = $_POST['opcion_correcta'];
    $puntos = $_POST['puntos'];

    // Manejo de imagen (opcional)
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $ruta = "../public/assets/img/";
        $nombreArchivo = uniqid() . "_" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta . $nombreArchivo);
        $imagen = $nombreArchivo;
    }

    $sql = "INSERT INTO preguntas 
            (nivel_id, tema, texto, opcion_a, opcion_b, opcion_c, opcion_d, opcion_correcta, puntos, imagen, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nivel_id, $tema, $texto, $opcion_a, $opcion_b, $opcion_c, $opcion_d, $opcion_correcta, $puntos, $imagen, $_SESSION['usuario_id']]);

    $mensaje = "Pregunta agregada correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Pregunta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Agregar Pregunta</h2>
  <?php if ($mensaje): ?>
    <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Nivel</label>
      <select class="form-select" name="nivel_id" required>
        <option value="">Seleccione...</option>
        <?php foreach ($niveles as $nivel): ?>
          <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Tema</label>
      <input class="form-control" type="text" name="tema" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Texto de la pregunta</label>
      <textarea class="form-control" name="texto" required></textarea>
    </div>

    <div class="row">
      <div class="col-md-6 mb-2"><input class="form-control" name="opcion_a" placeholder="Opción A" required></div>
      <div class="col-md-6 mb-2"><input class="form-control" name="opcion_b" placeholder="Opción B" required></div>
      <div class="col-md-6 mb-2"><input class="form-control" name="opcion_c" placeholder="Opción C" required></div>
      <div class="col-md-6 mb-2"><input class="form-control" name="opcion_d" placeholder="Opción D" required></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Respuesta correcta (a, b, c o d)</label>
      <input class="form-control" name="opcion_correcta" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Puntos</label>
      <input class="form-control" type="number" name="puntos" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagen (opcional)</label>
      <input class="form-control" type="file" name="imagen" accept="image/*">
    </div>

    <button class="btn btn-success">Guardar</button>
    <a href="listado_preguntas.php" class="btn btn-secondary">Volver</a>
  </form>
</div>
</body>
</html>
