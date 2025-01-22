<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'send-email.php'; // Incluir la función de envío de correos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';

    if (empty($correo)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, ingresa tu correo electrónico.']);
        exit;
    }

    $host = 'localhost';
    $db = 'login';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si el correo existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Generar un código de verificación
            $codigo = rand(100000, 999999);

            // Guardar el código en la base de datos
            $stmt = $pdo->prepare("UPDATE usuarios SET codigo_verificacion = :codigo WHERE correo = :correo");
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            // Enviar el código al correo
            $resultadoCorreo = enviarCorreoVerificacion($correo, $codigo);

            if ($resultadoCorreo['success']) {
                echo json_encode(['success' => true, 'message' => 'Código enviado a tu correo electrónico.']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultadoCorreo['message']]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'El correo ingresado no está registrado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
