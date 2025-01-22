<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

// Obtener el JWT si está configurado
$token = isset($_SESSION['jwt']) ? $_SESSION['jwt'] : null;

// Conexión a la base de datos para obtener la lista de usuarios
$host = 'localhost';
$db = 'login';
$user = 'root';
$password = '';
$usuarios = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar la lista de usuarios ordenada por correo
    $stmt = $pdo->query("SELECT id, correo, rol FROM usuarios ORDER BY correo ASC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SUPER USUARIO</title>
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
            width: 900px;
            max-height: 80vh;
            overflow: hidden;
            text-align: center;
        }

        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .btn-danger {
            background-color: #ff4d4d;
            border-color: #ff0000;
        }

        .btn-danger:hover {
            background-color: #cc0000;
            border-color: #b30000;
        }

        .btn-success {
            margin-top: 10px;
            align; flex;
            height: 40px;
            width: 200px;
            background-color: #28a745;
            border-color: #218838;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
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
        <h1>Bienvenido al Dashboard de Super Usuario</h1>
        <p>Aquí podrás gestionar los roles de los usuarios.</p>

        <!-- Tabla de usuarios -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Correo</th>
                        <th>Rol Actual</th>
                        <th>Cambiar Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                            <td>
                                <select class="form-select form-select-sm change-role" data-id="<?php echo $usuario['id']; ?>">
                                    <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="usuario" <?php echo $usuario['rol'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                    <option value="superusuario" <?php echo $usuario['rol'] === 'superusuario' ? 'selected' : ''; ?>>Superusuario</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
    <button id="apply-changes" class="btn btn-success">Aplicar Cambios</button>
    <br>
        <a href="logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
    </div>

    <script>
        $(document).ready(function () {
            // Recoger cambios
            const roleChanges = {};

            $('.change-role').on('change', function () {
                const userId = $(this).data('id');
                const newRole = $(this).val();
                roleChanges[userId] = newRole; // Guardar los cambios en el objeto
            });

            // Aplicar cambios al presionar el botón
            $('#apply-changes').on('click', function () {
                if (Object.keys(roleChanges).length === 0) {
                    alert('No hay cambios para aplicar.');
                    return;
                }

                $.post('update-role.php', { changes: roleChanges }, function (response) {
                    if (response.success) {
                        alert('Roles actualizados con éxito.');
                        location.reload(); // Recargar la página
                    } else {
                        alert('Error al actualizar los roles: ' + response.message);
                    }
                }, 'json').fail(function () {
                    alert('Error al comunicarse con el servidor.');
                });
            });
        });
    </script>
</body>
</html>
