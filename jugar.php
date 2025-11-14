<?php
session_start();
require 'config/conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// REGLAS
$umbral_intermedio = 500;
$umbral_alto = 1200;

// verificar login
if (empty($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['usuario_id'];

// datos del usuario
$stmt = $pdo->prepare("SELECT usuario, puntos FROM usuarios WHERE id = ?");
$stmt->execute([$userId]);
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userRow) { die("Usuario no encontrado"); }

$nombreUsuario = $userRow['usuario'];
$acumulado = (int)$userRow['puntos'];
$_SESSION['nombre'] = $nombreUsuario;

// determinar nivel
if ($acumulado >= $umbral_alto) {
    $nivel = 3;  $nivelNombre = "Alto";
} elseif ($acumulado >= $umbral_intermedio) {
    $nivel = 2;  $nivelNombre = "Intermedio";
} else {
    $nivel = 1;  $nivelNombre = "Básico";
}

$puedeSubir = false;
if ($nivel == 1 && $acumulado >= 500)  $puedeSubir = true;
if ($nivel == 2 && $acumulado >= 1200) $puedeSubir = true;

// subir nivel manual
if (isset($_GET['subir']) && $puedeSubir) {
    $nuevoNivel = $nivel + 1;
    unset($_SESSION['partida']);
    header("Location: jugar.php?start=1&nivel=$nuevoNivel");
    exit;
}

// preguntas requeridas
$porNivel = [1 => 10, 2 => 13, 3 => 15];

// nivel solicitado desde GET
if (isset($_GET['nivel']) && is_numeric($_GET['nivel'])) {
    $nivel = (int)$_GET['nivel'];
}

//  CREAR PARTIDA
if (isset($_GET['start']) || !isset($_SESSION['partida']) || $_SESSION['partida']['nivel'] != $nivel) {

    unset($_SESSION['partida']);

    $required = $porNivel[$nivel];

    // obtener preguntas
  $sql = "SELECT id, texto, opcion_a, opcion_b, opcion_c, opcion_d, opcion_correcta, puntos, imagenes
        FROM preguntas
        WHERE nivel_id = ?
        ORDER BY RAND()
        LIMIT ?";

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $nivel, PDO::PARAM_INT);
  $stmt->bindValue(2, (int)$required, PDO::PARAM_INT);
  $stmt->execute();

$seleccion = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (empty($seleccion)) {
        die("No hay preguntas disponibles para este nivel.");
    }

    $_SESSION['partida'] = [
        'nivel' => $nivel,
        'preguntas' => $seleccion,
        'indice' => 0,
        'puntos' => 0
    ];
}


// PROCESAR RESPUESTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {

    if (!isset($_SESSION['partida']) ||
        !isset($_SESSION['partida']['preguntas']) ||
        !is_array($_SESSION['partida']['preguntas']) ||
        count($_SESSION['partida']['preguntas']) === 0) {

        unset($_SESSION['partida']);
        header("Location: jugar.php?start=1&nivel=$nivel");
        exit;
    }

    $partida = &$_SESSION['partida'];
    $indice = $partida['indice'];

    if (!isset($partida['preguntas'][$indice])) {
        unset($_SESSION['partida']);
        header("Location: jugar.php?start=1");
        exit;
    }

    $preg = $partida['preguntas'][$indice];
    $resp = $_POST['respuesta'];

    if ($resp === $preg['opcion_correcta']) {
        $partida['puntos'] += (int)$preg['puntos'];
    }

    $partida['indice']++;

    // terminó el juego
    if ($partida['indice'] >= count($partida['preguntas'])) {

        $puntaje_total = $partida['puntos'];

        // guardar reporte
        $stmt = $pdo->prepare("INSERT INTO reportes (jugador_id, puntaje_total, nivel_alcanzado, fecha)
                               VALUES (?, ?, ?, NOW())");
        $stmt->execute([$userId, $puntaje_total, $nivelNombre]);

        // sumar puntos al usuario
        $nuevoTotal = $acumulado + $puntaje_total;
        $upd = $pdo->prepare("UPDATE usuarios SET puntos = ? WHERE id = ?");
        $upd->execute([$nuevoTotal, $userId]);

        unset($_SESSION['partida']);
        header("Location: jugar.php?final=1&puntaje=$puntaje_total");
        exit;
    }

    header("Location: jugar.php");
    exit;
}

// MOSTRAR PREGUNTA

if (!isset($_SESSION['partida']) ||
    !isset($_SESSION['partida']['preguntas']) ||
    !is_array($_SESSION['partida']['preguntas'])) {

    header("Location: jugar.php?start=1&nivel=$nivel");
    exit;
}

$partida = $_SESSION['partida'];
$indice = $partida['indice'];

// si índice se desborda → reiniciar
if ($indice >= count($partida['preguntas'])) {
    unset($_SESSION['partida']);
    header("Location: jugar.php?start=1&nivel=$nivel");
    exit;
}

$pregunta = $partida['preguntas'][$indice];
$total = count($partida['preguntas']);

// =============================
//  PANTALLA FINAL
// =============================
if (isset($_GET['final'])) {

    $puntajeGanado = (int)$_GET['puntaje'];
    $userId = $_SESSION['usuario_id'];

    // Obtener puntos totales del usuario
    $stmt = $pdo->prepare("SELECT puntos FROM usuarios WHERE id = ?");
    $stmt->execute([$userId]);
    $totalUser = (int)$stmt->fetchColumn();

    ?>
    
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultado Final</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background: #f5f7ff;
                text-align: center;
                padding: 40px;
            }
            .card-final {
                max-width: 600px;
                margin: auto;
                background: white;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 0 25px rgba(0,0,0,0.1);
            }
            .puntos {
                font-size: 60px;
                font-weight: bold;
                color: #4e73df;
                opacity: 0;
                transform: scale(0.6);
                transition: all 1s;
            }
            .final-btn {
                margin-top: 20px;
            }
        </style>
    </head>

    <body>

        <div class="card-final">
            <h2 class="fw-bold text-success">¡Juego Finalizado! 🎉</h2>

            <p class="fs-4 mt-3">Ganaste:</p>
            <div id="puntosAnim" class="puntos"><?= $puntajeGanado ?></div>

            <p class="mt-4 fs-5">
                <strong>Total acumulado:</strong> <?= $totalUser ?> ⭐
            </p>

            <div class="final-btn">
                <a href="jugar.php?start=1" class="btn btn-primary btn-lg">🔁 Volver a Jugar</a>
                <a href="logout.php" class="btn btn-secondary btn-lg">🚪 Salir</a>
            </div>
        </div>

        <script>
            // Animación del puntaje
            setTimeout(() => {
                const puntos = document.getElementById("puntosAnim");
                puntos.style.opacity = 1;
                puntos.style.transform = "scale(1)";
            }, 300);
        </script>

    </body>
    </html>

    <?php
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pregunta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.top-bar { background: #4e73df; color: white; padding: 15px; border-radius: 15px; margin-bottom: 25px;}
.btn-nivel { background: #f6c23e; font-weight: bold; }
</style>
</head>
<body class="p-4">

<div class="top-bar">
  <div class="row">
    <div class="col-md-4">👤 <strong><?= htmlspecialchars($nombreUsuario) ?></strong></div>
    <div class="col-md-4 text-center">⭐ <strong><?= $acumulado ?> puntos</strong></div>
    <div class="col-md-4 text-end">🏆 <strong><?= $nivelNombre ?></strong></div>
  </div>

  <?php if ($puedeSubir): ?>
  <div class="text-center mt-2">
      <a href="jugar.php?subir=1" class="btn btn-warning btn-nivel">🚀 Subir de nivel</a>
  </div>
  <?php endif; ?>
</div>


<div class="card shadow p-4" style="border-radius:20px; max-width:700px; margin:auto;">
  <h4 class="fw-bold text-primary text-center">Pregunta <?= $indice+1 ?> de <?= $total ?></h4>

  <p class="mt-3 fs-5 text-center"><?= htmlspecialchars($pregunta['texto']) ?></p>

  <?php if (!empty($pregunta['imagenes'])): ?>
      <img src="img/<?= htmlspecialchars($pregunta['imagenes']) ?>" class="img-fluid mb-3">
  <?php endif; ?>

  <form method="POST">
    <div class="d-grid gap-2">
      <button name="respuesta" value="a" class="btn btn-outline-primary btn-lg">
          <?= htmlspecialchars($pregunta['opcion_a']) ?>
      </button>

      <button name="respuesta" value="b" class="btn btn-outline-primary btn-lg">
          <?= htmlspecialchars($pregunta['opcion_b']) ?>
      </button>

      <?php if (!empty($pregunta['opcion_c'])): ?>
      <button name="respuesta" value="c" class="btn btn-outline-primary btn-lg">
          <?= htmlspecialchars($pregunta['opcion_c']) ?>
      </button>
      <?php endif; ?>

      <?php if (!empty($pregunta['opcion_d'])): ?>
      <button name="respuesta" value="d" class="btn btn-outline-primary btn-lg">
          <?= htmlspecialchars($pregunta['opcion_d']) ?>
      </button>
      <?php endif; ?>
    </div>
  </form>
</div>

</body>
</html>
