<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $nuevaContrasena = $_POST['nuevaContrasena'] ?? '';

    if (empty($codigo) || empty($nuevaContrasena)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $host = 'localhost';
    $db = 'login';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE codigo_verificacion = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $hashPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET password = :password, codigo_verificacion = NULL WHERE codigo_verificacion = :codigo");
            $stmt->bindParam(':password', $hashPassword);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->execute();

            // Mostrar mensaje y redirigir automáticamente
            echo "<script>
                alert('¡Tu clave ha sido restablecida correctamente! Volviendo a inicio...');
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 1000);
            </script>";
        } else {
            echo "<script>
                alert('Código inválido.');
                window.history.back();
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Error al conectar con la base de datos.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('Método no permitido.');
        window.history.back();
    </script>";
}
?>
