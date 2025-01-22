<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario tiene permiso
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
    exit;
}

// Verificar datos recibidos
$changes = $_POST['changes'] ?? null;

if (!$changes || !is_array($changes)) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron cambios válidos.']);
    exit;
}

// Conexión a la base de datos
$host = 'localhost';
$db = 'login';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar transacción
    $pdo->beginTransaction();

    foreach ($changes as $userId => $newRole) {
        // Validar roles permitidos
        $validRoles = ['admin', 'usuario', 'superusuario'];
        if (!in_array($newRole, $validRoles)) {
            throw new Exception('Rol no válido: ' . $newRole);
        }

        // Actualizar el rol del usuario
        $stmt = $pdo->prepare("UPDATE usuarios SET rol = :rol WHERE id = :id");
        $stmt->bindParam(':rol', $newRole);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Confirmar transacción
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Roles actualizados con éxito.']);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al actualizar los roles: ' . $e->getMessage()]);
}
