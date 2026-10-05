<?php
/**
 * Sukha - Módulo de Envío de Correos con PHPMailer y SMTP de Gmail
 * Las credenciales sensibles se cargan exclusivamente desde .env
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/conexion.php';

/**
 * Crea y configura una instancia de PHPMailer con SMTP de Gmail
 */
function crearMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = getenv('MAIL_HOST') ?: ($_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('MAIL_USERNAME') ?: ($_ENV['MAIL_USERNAME'] ?? '');
    $mail->Password   = getenv('MAIL_PASSWORD') ?: ($_ENV['MAIL_PASSWORD'] ?? '');

    $port = (int)(getenv('MAIL_PORT') ?: ($_ENV['MAIL_PORT'] ?? 587));
    $mail->Port = $port;

    $encryption = strtolower((string)(getenv('MAIL_ENCRYPTION') ?: ($_ENV['MAIL_ENCRYPTION'] ?? 'tls')));
    if ($port === 465 || $encryption === 'ssl' || $encryption === 'smtps') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Remitente predeterminado
    $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: ($_ENV['MAIL_FROM_ADDRESS'] ?? $mail->Username);
    $fromName    = getenv('MAIL_FROM_NAME') ?: ($_ENV['MAIL_FROM_NAME'] ?? 'Sukha — Tu espacio de calma');
    $mail->setFrom($fromAddress, $fromName);

    return $mail;
}

/**
 * Plantilla HTML base para correos con la identidad visual de Sukha
 */
function plantillaCorreoSukha(string $titulo, string $contenidoHtml): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$titulo}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F0EDE4; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2C3E35;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #F0EDE4; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="580" cellspacing="0" cellpadding="0" border="0" style="max-width: 580px; background-color: #FAF7F0; border-radius: 24px; overflow: hidden; border: 1px solid #D4E5D2; box-shadow: 0 8px 24px rgba(44, 62, 53, 0.06);">
                    <!-- Barra de acento superior -->
                    <tr>
                        <td height="6" style="background: linear-gradient(90deg, #7D9B76 0%, #B5C9B3 50%, #C4856A 100%);"></td>
                    </tr>
                    <!-- Encabezado con marca -->
                    <tr>
                        <td align="center" style="padding: 35px 25px 20px 25px;">
                            <div style="font-family: Georgia, 'Playfair Display', serif; font-size: 28px; font-weight: bold; color: #2C3E35; letter-spacing: 0.5px;">
                                Sukha
                            </div>
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #7D9B76; margin-top: 4px; font-weight: 600;">
                                Tu espacio de calma
                            </div>
                        </td>
                    </tr>
                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 10px 35px 35px 35px; line-height: 1.6; font-size: 15px; color: #2C3E35;">
                            {$contenidoHtml}
                        </td>
                    </tr>
                    <!-- Pie de correo -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #F5F0E8; border-top: 1px solid #E5E0D5; text-align: center; font-size: 12px; color: #6B7B6E;">
                            <p style="margin: 0 0 6px 0;"><strong>Sukha</strong> — Herramienta de bienestar emocional y apoyo preventivo.</p>
                            <p style="margin: 0; font-size: 11px; color: #8F9E92;">Este es un mensaje automático. Por favor no respondas a este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}

/**
 * Envía un correo electrónico a través de la API REST de EmailJS
 * Ideal para entornos donde los puertos SMTP salientes están bloqueados (como InfinityFree)
 */
function enviarCorreoEmailJS(string $destinatario, string $nombreDestinatario, string $asunto, string $mensaje, array $extraParams = []): array {
    $serviceId  = getenv('EMAILJS_SERVICE_ID') ?: ($_ENV['EMAILJS_SERVICE_ID'] ?? 'service_058nvbe');
    $templateId = getenv('EMAILJS_TEMPLATE_ID') ?: ($_ENV['EMAILJS_TEMPLATE_ID'] ?? 'template_vroinvg');
    $publicKey  = getenv('EMAILJS_PUBLIC_KEY') ?: ($_ENV['EMAILJS_PUBLIC_KEY'] ?? 'dXAXbdIRn0wh9d4F2');

    if (empty($serviceId) || empty($templateId) || empty($publicKey)) {
        return ['success' => false, 'error' => 'EmailJS no está configurado en .env'];
    }

    $templateParams = array_merge([
        'to_name'    => $nombreDestinatario ?: $destinatario,
        'user_name'  => $nombreDestinatario ?: $destinatario,
        'name'       => $nombreDestinatario ?: $destinatario,
        'to_email'   => $destinatario,
        'email'      => $destinatario,
        'user_email' => $destinatario,
        'reply_to'   => $destinatario,
        'subject'    => $asunto,
        'message'    => $mensaje,
        'mensaje'    => $mensaje,
    ], $extraParams);

    $payload = [
        'service_id'      => $serviceId,
        'template_id'     => $templateId,
        'user_id'         => $publicKey,
        'template_params' => $templateParams,
    ];

    $origin = !empty($_SERVER['HTTP_ORIGIN']) 
        ? $_SERVER['HTTP_ORIGIN'] 
        : (!empty($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : 'http://localhost');

    $options = [
        'http' => [
            'header'        => "Content-Type: application/json\r\n" .
                               "Origin: {$origin}\r\n" .
                               "User-Agent: Sukha-Client/1.0\r\n",
            'method'        => 'POST',
            'content'       => json_encode($payload),
            'ignore_errors' => true,
            'timeout'       => 10,
        ],
        'ssl' => [
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ]
    ];

    try {
        $context = stream_context_create($options);
        $response = @file_get_contents('https://api.emailjs.com/api/v1.0/email/send', false, $context);

        $statusLine = $http_response_header[0] ?? '';
        if (str_contains($statusLine, '200') || $response === 'OK') {
            return ['success' => true, 'error' => null];
        }

        $errorMsg = "EmailJS ({$statusLine}): " . ($response ?: 'Error de conexión');
        error_log($errorMsg);
        return ['success' => false, 'error' => $errorMsg];
    } catch (\Throwable $e) {
        $errorMsg = "Excepción al conectar con EmailJS: " . $e->getMessage();
        error_log($errorMsg);
        return ['success' => false, 'error' => $errorMsg];
    }
}

/**
 * Envía un correo electrónico general (PHPMailer con fallback transparente a EmailJS)
 * 
 * @return array ['success' => bool, 'error' => ?string, 'provider' => ?string]
 */
function enviarCorreoSukha(string $destinatario, string $nombreDestinatario, string $asunto, string $cuerpoHtml, string $cuerpoTexto = '', array $emailJsParams = []): array {
    $mailPass = getenv('MAIL_PASSWORD') ?: ($_ENV['MAIL_PASSWORD'] ?? '');
    $hasRealSmtp = !empty($mailPass) && $mailPass !== 'tu_app_password_16_chars' && $mailPass !== 'tu_clave_de_aplicacion_16_caracteres';
    $emailJsActive = !empty(getenv('EMAILJS_SERVICE_ID') ?: ($_ENV['EMAILJS_SERVICE_ID'] ?? ''));

    // 1. Intentar con PHPMailer si el usuario configuró SMTP de Gmail real
    if ($hasRealSmtp) {
        try {
            $mail = crearMailer();
            $mail->addAddress($destinatario, $nombreDestinatario);
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpoHtml;
            $mail->AltBody = $cuerpoTexto ?: strip_tags(str_replace(['<br>', '<br/>', '<p>', '</p>'], ["\n", "\n", "\n\n", ""], $cuerpoHtml));

            $mail->send();
            return ['success' => true, 'error' => null, 'provider' => 'phpmailer'];
        } catch (\Throwable $e) {
            error_log("PHPMailer SMTP falló, recurriendo a EmailJS: " . $e->getMessage());
        }
    }

    // 2. Si no hay SMTP o falló, enviar vía EmailJS (API HTTP compatible con InfinityFree y XAMPP)
    if ($emailJsActive) {
        $textoMensaje = $cuerpoTexto ?: strip_tags(str_replace(['<br>', '<br/>', '<p>', '</p>'], ["\n", "\n", "\n\n", ""], $cuerpoHtml));
        $res = enviarCorreoEmailJS($destinatario, $nombreDestinatario, $asunto, $textoMensaje, $emailJsParams);
        $res['provider'] = 'emailjs';
        return $res;
    }

    return ['success' => false, 'error' => 'No hay proveedor de correo configurado (SMTP ni EmailJS)', 'provider' => null];
}

/**
 * Envía correo de bienvenida tras el registro exitoso
 */
function enviarCorreoBienvenida(string $email, string $nombre): array {
    $asunto = "Tu cuenta en Sukha · Un espacio para tu calma y bienestar";
    $primerNombre = explode(' ', trim($nombre))[0];
    $nombreMostrar = !empty($nombre) ? $nombre : $primerNombre;

    $contenido = <<<HTML
        <h2 style="font-family: Georgia, 'Playfair Display', serif; font-size: 22px; color: #2C3E35; margin-top: 0; margin-bottom: 16px;">
            Hola, {$nombreMostrar}:
        </h2>
        <p style="margin-bottom: 16px; color: #4A6A55; line-height: 1.6;">
            Queremos darte la bienvenida a <strong>Sukha</strong>.
        </p>
        <p style="margin-bottom: 16px; color: #4A6A55; line-height: 1.6;">
            Sabemos que el día a día puede traer momentos de sobrecarga, tensión o prisa. Por eso creamos este espacio: una herramienta pensada para acompañarte a hacer una pausa consciente, regular la ansiedad y reencontrarte con la serenidad a tu propio ritmo.
        </p>
        <div style="background-color: #EAF2E9; border-left: 4px solid #7D9B76; padding: 14px 18px; border-radius: 10px; margin: 20px 0; font-size: 14px; color: #2C3E35;">
            Tu cuenta ha quedado registrada con la dirección: <strong>{$email}</strong>
        </div>
        <p style="margin-top: 24px; color: #6B7B6E; font-size: 14px; line-height: 1.5;">
            Con aprecio y serenidad,<br>
            <strong style="color: #2C3E35;">El equipo de Sukha</strong>
        </p>
HTML;

    $mensajeTexto = "Hola, {$nombreMostrar}:\n\nQueremos darte la bienvenida a Sukha.\n\nSabemos que el día a día puede traer momentos de sobrecarga, tensión o prisa. Por eso creamos este espacio: una herramienta pensada para acompañarte a hacer una pausa consciente, regular la ansiedad y reencontrarte con la serenidad a tu propio ritmo.\n\nTu cuenta ha quedado registrada con la dirección: {$email}\n\nCon aprecio y serenidad,\nEl equipo de Sukha";

    $html = plantillaCorreoSukha($asunto, $contenido);
    return enviarCorreoSukha($email, $nombre, $asunto, $html, $mensajeTexto, [
        'name'      => $nombreMostrar,
        'user_name' => $nombreMostrar,
        'code'      => '',
        'codigo'    => '',
    ]);
}

/**
 * Envía el código de 6 dígitos para la recuperación de contraseña
 */
function enviarCodigoRecuperacion(string $email, string $codigo): array {
    $asunto = "Código de recuperación de contraseña — Sukha";

    $contenido = <<<HTML
        <h2 style="font-family: Georgia, 'Playfair Display', serif; font-size: 22px; color: #2C3E35; margin-top: 0; margin-bottom: 16px;">
            Recuperación de contraseña
        </h2>
        <p style="margin-bottom: 18px; color: #4A6A55;">
            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Sukha</strong>.
        </p>
        <p style="margin-bottom: 12px; color: #2C3E35; font-size: 14px;">
            Usa el siguiente código de verificación de 6 dígitos:
        </p>
        
        <!-- Caja destacada del código -->
        <div style="background: linear-gradient(135deg, #FAF7F0 0%, #EAF2E9 100%); border: 2px dashed #7D9B76; border-radius: 16px; padding: 22px; text-align: center; margin: 24px 0;">
            <span style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: bold; letter-spacing: 10px; color: #2C3E35; display: inline-block;">
                {$codigo}
            </span>
            <div style="margin-top: 8px; font-size: 12px; color: #7D9B76; font-weight: 600;">
                Válido por 15 minutos • Un solo uso
            </div>
        </div>

        <p style="margin-bottom: 16px; color: #4A6A55; font-size: 14px;">
            Ingresa a la página de restablecimiento de contraseña en Sukha y digita este código junto con tu nueva contraseña.
        </p>
        <div style="background-color: #FFF9F2; border-left: 4px solid #C4856A; padding: 12px 16px; border-radius: 8px; margin: 20px 0; font-size: 13px; color: #7A5340;">
            <strong>Aviso de seguridad:</strong> Si no solicitaste este código, puedes ignorar este correo con total tranquilidad. Tu contraseña actual seguirá siendo la misma y nadie puede acceder sin este código.
        </div>
HTML;

    $mensajeTexto = "Recuperación de contraseña — Sukha\n\nTu código de verificación de 6 dígitos es: {$codigo}\n\nEste código es válido por 15 minutos y es de un solo uso.\n\nSi no solicitaste este restablecimiento, ignora este mensaje.";

    $html = plantillaCorreoSukha($asunto, $contenido);
    return enviarCorreoSukha($email, $email, $asunto, $html, $mensajeTexto, [
        'code'   => $codigo,
        'codigo' => $codigo,
    ]);
}

