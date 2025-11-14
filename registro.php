<?php
require 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$usuario || !$email || !$password) {
        echo "<script>alert('Por favor completa todos los campos.'); window.history.back();</script>";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Verificar si el correo ya existe
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            echo "<script>alert('⚠️ Este correo ya está registrado.'); window.history.back();</script>";
            exit;
        }

        // Insertar usuario
        $sql = "INSERT INTO usuarios (usuario, email, password_hash, rol_id, aprobado, puntos)
                VALUES (?, ?, ?, 2, 0, 0)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$usuario, $email, $password_hash])) {
            echo "<script>alert('✅ Registro exitoso. Espera la aprobación del administrador.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('❌ No se pudo registrar el usuario.');</script>";
        }
    } catch (PDOException $e) {
        echo "<pre>Error SQL: " . $e->getMessage() . "</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #1cc88a, #4e73df);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      font-family: "Poppins", sans-serif;
    }

    .card-registro {
      width: 450px;
      background: #ffffffdd;
      backdrop-filter: blur(6px);
      padding: 35px 40px;
      border-radius: 25px;
      box-shadow: 0px 10px 35px rgba(0,0,0,0.2);
      animation: fadeIn 0.6s ease-out;
    }

    .titulo {
      font-size: 28px;
      font-weight: 800;
      text-align: center;
      margin-bottom: 10px;
      color: #2c2c2c;
    }

    .icono-top {
      font-size: 70px;
      text-align: center;
      display: block;
      margin-bottom: 8px;
      animation: float 2s infinite ease-in-out;
    }

    label {
      font-weight: 600;
      margin-top: 10px;
    }

    .btn-registrar {
      background: #1cc88a;
      border: none;
      padding: 12px;
      font-size: 18px;
      border-radius: 14px;
      font-weight: bold;
      width: 100%;
      margin-top: 15px;
      transition: .2s;
      color: white;
    }

    .btn-registrar:hover {
      transform: scale(1.03);
    }

    .btn-volver {
      display: block;
      text-align: center;
      margin-top: 15px;
      background: #e74a3b;
      color: white;
      padding: 10px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: bold;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-weight: 500;
    }

    .login-link a {
      font-weight: bold;
      text-decoration: none;
      color: #4e73df;
    }

    .alert {
      font-size: 15px;
      margin-top: 10px;
    }

    /* Animaciones */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
      0%,100% { transform: translateY(0); }
      50%     { transform: translateY(-6px); }
    }
  </style>
</head>

<body>

<div class="card-registro">

  <span class="icono-top">📝</span>
  <h3 class="titulo">Crear Cuenta</h3>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">

    <label>Nombre de usuario</label>
    <input type="text" name="usuario" class="form-control form-control-lg" placeholder="Ej: juan23" required>

    <label>Correo electrónico</label>
    <input type="email" name="email" class="form-control form-control-lg" placeholder="usuario@gmail.com" required>

    <label>Contraseña</label>
    <input type="password" name="password" class="form-control form-control-lg" placeholder="Escribe tu contraseña" required>

    <button type="submit" class="btn-registrar">🚀 Registrarme</button>

  </form>

  <p class="login-link">¿Ya tienes cuenta?  
     <a href="login.php">Inicia sesión</a>
  </p>

  <a href="index.php" class="btn-volver">⬅ Volver</a>

</div>

</body>
</html>
