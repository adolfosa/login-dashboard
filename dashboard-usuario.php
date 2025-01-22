<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

// Obtener el JWT si está configurado
$token = isset($_SESSION['jwt']) ? $_SESSION['jwt'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background: linear-gradient(to bottom right, #397bdf, #25f8ff); /* Degradado de azules */
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            max-width: 100px;
            max-height: 100px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .btn-danger {
            background-color: #ff4d4d;
            border-color: #ff0000;
        }

        .btn-danger:hover {
            background-color: #cc0000;
            border-color: #b30000;
        }
    </style>
</head>
<body>
    <!-- Logo -->
    <div class="logo">
        <img src="wit.png" alt="Logo">
    </div>

    <!-- Dashboard Card -->
    <div class="card p-4">
        <h1>Bienvenido al Dashboard de USUARIO</h1>
        <p>Estás autenticado. Aquí puedes realizar consultas.</p>
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>

    <script>
        // Mostrar el token JWT en la consola
        const jwtToken = <?php echo json_encode($token); ?>;
        if (jwtToken) {
            console.log('JWT Token:', jwtToken);
        } else {
            console.warn('No se encontró un JWT Token.');
        }
    </script>
</body>
</html>