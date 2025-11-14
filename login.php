<?php
session_start();
require 'config/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login_input = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        $error = "Por favor completa todos los campos.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? OR usuario = ? LIMIT 1");
        $stmt->execute([$login_input, $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Usuario o correo no encontrado.";
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = "Contraseña incorrecta.";
        } elseif ((int)$user['aprobado'] !== 1) {
            $error = "Tu cuenta aún no ha sido aprobada.";
        } else {

            // Crear sesión
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['usuario'];
            $_SESSION['rol_id'] = (int)$user['rol_id'];

            // Redirección correcta
            if ($_SESSION['rol_id'] === 1) {
                header("Location: admin/panel_admin.php");
            } else {
                header("Location: jugar.php");
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Poppins", sans-serif;
        padding: 20px;
    }

    .card-login {
        width: 420px;
        background: #ffffffee;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 35px rgba(0,0,0,0.2);
        animation: fadeIn 0.5s ease-in-out;
    }

    .titulo {
        font-weight: 700;
        font-size: 28px;
        text-align: center;
        margin-bottom: 15px;
        color: #2e2e2e;
    }

    .icono {
        font-size: 60px;
        color: #1cc88a;
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }

    .btn-primary {
        padding: 12px;
        font-size: 17px;
        border-radius: 12px;
        transition: 0.2s;
    }

    .btn-primary:hover {
        transform: scale(1.03);
    }

    .volver-btn {
        display: block;
        text-align: center;
        background: #f6c23e;
        padding: 10px;
        border-radius: 12px;
        margin-top: 10px;
        text-decoration: none;
        color: #222;
        font-weight: bold;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

  </style>
</head>

<body>

<div class="card-login">

  <span class="icono">🎮</span>
  <h3 class="titulo">Iniciar Sesión</h3>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label fw-bold">Usuario o correo</label>
      <input type="text" name="login" class="form-control form-control-lg" placeholder="Ej: juan23 o juan@gmail.com" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Contraseña</label>
      <input type="password" name="password" class="form-control form-control-lg" placeholder="Ingresa tu contraseña" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">🚀 Entrar</button>
  </form>

  <p class="text-center mt-3">
      ¿No tienes cuenta?
      <a href="registro.php" class="fw-bold text-primary">Regístrate aquí</a>
  </p>

  <a href="index.php" class="volver-btn">⬅ Volver</a>

</div>

</body>
</html>


</body>
</html>
