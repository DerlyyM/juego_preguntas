<?php
session_start();
require '../config/conexion.php';

// Solo el admin puede acceder
if (empty($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    die('Acceso denegado.');
}

// Insertar o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $texto = $_POST['texto'];
    $nivel_id = $_POST['nivel_id'];
    $opcion_a = $_POST['opcion_a'];
    $opcion_b = $_POST['opcion_b'];
    $opcion_c = $_POST['opcion_c'];
    $opcion_d = $_POST['opcion_d'];
    $opcion_correcta = $_POST['opcion_correcta'];
    $puntos = $_POST['puntos'] ?? 50;
    $imagen = null;

    // Subida de imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = '../uploads/' . $nombreArchivo;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        $imagen = $nombreArchivo;
    }

    if ($id) {
        // Actualizar pregunta
        $sql = "UPDATE preguntas SET texto=?, nivel_id=?, opcion_a=?, opcion_b=?, opcion_c=?, opcion_d=?, opcion_correcta=?, puntos=?";
        if ($imagen) {
            $sql .= ", imagen=?";
        }
        $sql .= " WHERE id=?";
        $params = [$texto, $nivel_id, $opcion_a, $opcion_b, $opcion_c, $opcion_d, $opcion_correcta, $puntos];
        if ($imagen) $params[] = $imagen;
        $params[] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        // Nueva pregunta
        $stmt = $pdo->prepare("INSERT INTO preguntas (texto, nivel_id, opcion_a, opcion_b, opcion_c, opcion_d, opcion_correcta, puntos, imagen)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$texto, $nivel_id, $opcion_a, $opcion_b, $opcion_c, $opcion_d, $opcion_correcta, $puntos, $imagen]);
    }
    header("Location: preguntas.php");
    exit;
}

// Eliminar pregunta
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $pdo->prepare("DELETE FROM preguntas WHERE id=?")->execute([$id]);
    header("Location: preguntas.php");
    exit;
}

// Obtener preguntas y niveles
$preguntas = $pdo->query("SELECT p.*, n.nombre AS nivel FROM preguntas p JOIN niveles n ON p.nivel_id = n.id ORDER BY p.nivel_id")->fetchAll();
$niveles = $pdo->query("SELECT * FROM niveles")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Preguntas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
    <h2 class="text-center mb-4">📘 Administración de Preguntas</h2>

    <!-- Formulario de nueva pregunta -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Agregar / Editar Pregunta</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="id" id="id">

                <div class="col-md-12">
                    <label class="form-label">Texto de la pregunta</label>
                    <textarea class="form-control" name="texto" id="texto" required></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nivel</label>
                    <select name="nivel_id" id="nivel_id" class="form-select" required>
                        <option value="">Seleccione nivel</option>
                        <?php foreach ($niveles as $n): ?>
                            <option value="<?= $n['id'] ?>"><?= $n['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Puntos</label>
                    <input type="number" name="puntos" id="puntos" class="form-control" value="50">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Opción A</label>
                    <input type="text" name="opcion_a" id="opcion_a" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Opción B</label>
                    <input type="text" name="opcion_b" id="opcion_b" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Opción C</label>
                    <input type="text" name="opcion_c" id="opcion_c" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Opción D</label>
                    <input type="text" name="opcion_d" id="opcion_d" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Correcta</label>
                    <select name="opcion_correcta" id="opcion_correcta" class="form-select" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>

                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success">💾 Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado de preguntas -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Banco de Preguntas</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Pregunta</th>
                            <th>Nivel</th>
                            <th>Puntos</th>
                            <th>Correcta</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preguntas as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['texto']) ?></td>
                                <td><?= $p['nivel'] ?></td>
                                <td><?= $p['puntos'] ?></td>
                                <td><?= strtoupper($p['opcion_correcta']) ?></td>
                                <td>
                                    <?php if ($p['imagen']): ?>
                                        <img src="/img/<?= $p['imagen'] ?>" width="60" height="60" class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="preguntas.php?editar=<?= $p['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                                    <a href="preguntas.php?eliminar=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta pregunta?')">🗑 Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
