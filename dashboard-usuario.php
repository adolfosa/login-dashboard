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
    <title>Dashboard Universitario</title>
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
            flex-direction: column; /* Alinear verticalmente */
        }

        .logo {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Espacio entre el logo y el contenido */
        }

        .logo img {
            max-width: 80%;
            max-height: 80%;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%; /* Ancho adaptable */
            max-width: 600px; /* Máximo ancho */
            text-align: center;
            padding: 20px; /* Espaciado interno */
        }

        h1 {
            font-size: 1.5rem; /* Tamaño de fuente para el título */
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Grilla adaptable */
            gap: 20px; /* Espacio entre recuadros */
            margin-top: 20px; /* Margen superior para separación */
        }

        .grid-item {
            background-color: white; /* Fondo blanco para los recuadros */
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            padding: 20px; /* Espaciado interno */
            text-align: center; /* Centrar texto */
            transition: transform 0.3s ease; /* Animación de transformación */
        }

        .grid-item:hover {
            transform: scale(1.05); /* Aumentar tamaño al pasar el mouse */
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
    <div class="card">
        <h1>Bienvenido al Dashboard de USUARIO</h1>
        <p>Estás autenticado. Aquí puedes realizar consultas.</p>

        <!-- Grilla de Opciones -->
        <div class="grid-container">
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 1</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 2</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 3</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 4</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 5</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
            <div class="grid-item" onclick="location.href='#';">
                <h2>Opción 6</h2>
                <p>Descripción breve de la opción.</p>
                <!-- El enlace se maneja con onclick -->
            </div>
        </div>

        <!-- Botón de Cierre de Sesión -->
        <a href="logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
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
