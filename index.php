<?php
session_start();

// Si ya hay sesión activa, redirigir según el rol
if (isset($_SESSION['rol_id'])) {
    if ($_SESSION['rol_id'] == 1) {
        header("Location: administracion/index.php"); // Admin
    } else {
        header("Location: jugar.php"); // Jugador
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego de Preguntas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .card-custom {
            position: relative;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.75);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            color: #fff;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 450px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #f8f9fa;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.8);
        }

        .btn-lg {
            font-size: 1.2rem;
            padding: 12px 0;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .btn-lg:hover {
            transform: scale(1.05);
        }

        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #ccc;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="card-custom">
        <h1 class="mb-4">🎮 Juego de Preguntas</h1>
        <p class="mb-4">Pon a prueba tus conocimientos en análisis, diseño y programación.</p>
        <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        <a href="registro.php" class="btn btn-success">Registrarse</a>
    </div>

    <footer>© <?= date('Y') ?> Sistema de Juego de Preguntas | Proyecto Formativo</footer>
</body>
</html>
