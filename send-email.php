<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Carga automáticamente las dependencias de Composer

function enviarCorreoVerificacion($correoDestino, $codigo) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'alfred.hernan321@gmail.com'; // Tu correo Gmail
        $mail->Password = 'yhmb grjj lfff trwj'; // Tu contraseña o App Password de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación TLS
        $mail->Port = 587; // Puerto SMTP para TLS

        // Configuración del remitente y destinatario
        $mail->setFrom('alfred.hernan321@gmail.com', 'Tu Nombre o Empresa'); // Remitente
        $mail->addAddress($correoDestino); // Destinatario

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificación';
        $mail->Body    = "Tu código de verificación es: <b>$codigo</b>";
        $mail->AltBody = "Tu código de verificación es: $codigo"; // Texto plano

        // Enviar correo
        $mail->send();
        return ['success' => true, 'message' => 'Correo enviado con éxito.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "Error al enviar el correo: {$mail->ErrorInfo}"];
    }
}
