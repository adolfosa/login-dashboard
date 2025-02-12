<?php
session_start();
header('Content-Type: application/json');

// Requerir la librería JWT
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Configuración de la conexión a la base de datos
$host = 'localhost';
$db = 'login';
$user = 'root';
$password = '';

try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Error de conexión a la base de datos: ' . $e->getMessage()); // Registrar error en el log
    echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
    exit;
}

// Configuración del JWT
$secretKey = 'TU_SECRETO_COMPARTIDO'; // Cambia esto por una clave secreta segura
$issuer = 'localhost'; // Emisor del token (puede ser tu dominio o sistema)
$audience = 'tu_aplicacion'; // Destinatario del token (por ejemplo, tu API)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar el correo electrónico
    $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
    if (!$correo) {
        echo json_encode(['success' => false, 'message' => 'Por favor, ingrese un correo electrónico válido.']);
        exit;
    }
    $correo = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');

    // Validar la contraseña
    $password = $_POST['password'] ?? '';
    if (empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, ingrese una contraseña.']);
        exit;
    }

    // Verificar el usuario en la base de datos
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                // Generar el JWT
                $issuedAt = time();
                $expirationTime = $issuedAt + 3600; // 1 hora de duración
                $payload = [
                    'iat' => $issuedAt,           // Fecha de emisión
                    'exp' => $expirationTime,     // Fecha de expiración
                    'iss' => $issuer,             // Emisor
                    'aud' => $audience,           // Audiencia
                    'data' => [
                        'id' => $user['id'],
                        'correo' => $user['correo']
                    ]
                ];

                $jwt = JWT::encode($payload, $secretKey, 'HS256'); // Generar el token

                // Guardar el ID del usuario y el token en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['jwt'] = $jwt;

                // Determinar redirección según el rol
                $rol = $user['rol']; // Asegúrate de que la columna 'rol' exista en la tabla 'usuarios'
                $redirect = '';

                if ($rol === 'admin') {
                    $redirect = 'dashboard-admin.php';
                } elseif ($rol === 'usuario') {
                    $redirect = 'dashboard-usuario.php';
                } elseif ($rol === 'superusuario') {
                    $redirect = 'dashboard-SupUser.php';
                } else {
                    $redirect = 'dashboard-default.php'; // Redirección por defecto
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso.',
                    'token' => $jwt,
                    'redirect' => $redirect // Redirección según el rol
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
    } catch (PDOException $e) {
        error_log('Error en la consulta SQL: ' . $e->getMessage()); // Registrar error en el log
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>