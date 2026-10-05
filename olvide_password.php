<?php
/**
 * Sukha - Recuperación de Contraseña (Paso 1: Solicitud de Código)
 * Envía un código de 6 dígitos con hash al correo, vencimiento en 15 minutos, un solo uso.
 * Por seguridad anti-enumeración, el mensaje siempre es idéntico exista o no el correo.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/mailer.php';

$solicitudEnviada = false;
$emailIngresado = '';
$errores = [];

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Mensaje estándar de seguridad (idéntico exista o no el correo en el sistema)
$mensajeSeguro = "Si el correo electrónico ingresado está registrado en Sukha, recibirás un código de verificación de 6 dígitos en tu bandeja de entrada (revisa también tu carpeta de spam). Este código es de un solo uso y vencerá en 15 minutos.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $errores[] = "Sesión inválida o expirada. Por favor actualiza la página.";
    } else {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $emailIngresado = $email;

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Por favor ingresa un correo electrónico válido.";
        } else {
            try {
                // Consulta preparada para verificar existencia del usuario sin revelar el resultado al público
                $stmtUser = $pdo->prepare("SELECT id, nombre, email FROM usuarios WHERE email = ? LIMIT 1");
                $stmtUser->execute([$email]);
                $usuario = $stmtUser->fetch();

                if ($usuario) {
                    // Generar código aleatorio de 6 dígitos seguro
                    $codigo = sprintf('%06d', random_int(0, 999999));

                    // Hashear el código antes de guardarlo en base de datos
                    $codigoHash = password_hash($codigo, PASSWORD_DEFAULT);

                    // Invalidar códigos activos previos para este correo
                    $stmtInvalidar = $pdo->prepare("UPDATE password_resets SET usado = 1 WHERE email = ? AND usado = 0");
                    $stmtInvalidar->execute([$email]);

                    // Guardar nuevo código con expiración en 15 minutos
                    $stmtInsert = $pdo->prepare("
                        INSERT INTO password_resets (email, codigo_hash, expira, usado)
                        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE), 0)
                    ");
                    $stmtInsert->execute([$email, $codigoHash]);

                    // Enviar código al correo mediante PHPMailer
                    enviarCodigoRecuperacion($email, $codigo);
                }

                // La bandera se activa en ambos casos para mostrar el mismo mensaje
                $solicitudEnviada = true;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            } catch (PDOException $e) {
                error_log("Error al procesar restablecimiento de contraseña: " . $e->getMessage());
                $errores[] = "Ocurrió un inconveniente temporal. Por favor inténtalo de nuevo en unos momentos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sukha — Recuperar Contraseña</title>
    <!-- Favicon Permanente -->
    <link rel="icon" type="image/png" sizes="192x192" href="favicon.png?v=4">
    <link rel="icon" type="image/png" sizes="64x64" href="favicon.png?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png?v=4">
    <link rel="shortcut icon" type="image/png" href="favicon.png?v=4">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="antialiased min-h-screen" style="background-color: #F0EDE4; background-image: radial-gradient(circle at 20% 20%, #D4E5D2 0%, transparent 40%), radial-gradient(circle at 80% 80%, #EDE8DC 0%, transparent 40%);">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6">

        <!-- Logo + Brand -->
        <div class="mb-8 text-center">
            <a href="index.php" class="inline-flex flex-col items-center gap-2">
                <div class="w-16 h-16 flex items-center justify-center">
                    <img src="favicon.png?v=4" alt="Sukha" class="w-16 h-16 object-contain rounded-2xl shadow-sm">
                </div>
                <div>
                    <span class="font-playfair text-3xl font-bold" style="color: #2C3E35;">Sukha</span>
                    <p class="text-xs font-medium tracking-widest uppercase mt-0.5" style="color: #7D9B76;">Tu espacio de calma</p>
                </div>
            </a>
        </div>

        <!-- Card Principal -->
        <div class="w-full max-w-md rounded-3xl shadow-xl overflow-hidden" style="background-color: #FAF7F0; border: 1px solid #D4E5D2;">
            <div class="h-1.5 w-full" style="background: linear-gradient(to right, #7D9B76, #B5C9B3, #C4856A);"></div>
            <div class="px-8 py-8">

                <?php if ($solicitudEnviada): ?>
                    <!-- Mensaje idéntico de confirmación anti-enumeración -->
                    <div class="text-center py-2 space-y-4">
                        <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center" style="background-color: #EAF2E9; color: #7D9B76;">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="font-playfair text-xl font-bold" style="color: #2C3E35;">Solicitud procesada</h2>
                        <div class="p-4 rounded-2xl text-xs text-left leading-relaxed" style="background-color: #F5F0E8; border: 1px solid #D4E5D2; color: #4A6A55;">
                            <?= htmlspecialchars($mensajeSeguro) ?>
                        </div>

                        <div class="pt-3 space-y-2">
                            <a href="restablecer_password.php?email=<?= urlencode($emailIngresado) ?>"
                               class="inline-block w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide text-center transition-all hover:opacity-95"
                               style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);">
                                Ingresar Código y Nueva Contraseña &rarr;
                            </a>
                            <a href="public/login"
                               class="inline-block w-full py-2.5 text-xs text-center font-medium hover:underline"
                               style="color: #6B7B6E;">
                                Volver al inicio de sesión
                            </a>
                        </div>
                    </div>
                <?php else: ?>

                    <div class="mb-6">
                        <h1 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">¿Olvidaste tu contraseña?</h1>
                        <p class="text-sm mt-1" style="color: #6B7B6E;">Ingresa tu correo registrado y te enviaremos un código de seguridad para recuperarla.</p>
                    </div>

                    <?php if (!empty($errores)): ?>
                        <div class="mb-5 px-4 py-3 rounded-2xl text-sm" style="background-color: #FDF0EC; color: #9B3D26; border: 1px solid #E8C4B8;">
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <?php foreach ($errores as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="olvide_password.php" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($emailIngresado) ?>"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="tu@correo.com"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide transition-all hover:opacity-90 active:scale-[0.98] mt-2"
                            style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);"
                        >
                            Enviar código de 6 dígitos
                        </button>

                        <div class="pt-2 text-center text-xs space-y-1" style="color: #6B7B6E;">
                            <p>
                                ¿Recordaste tu contraseña?
                                <a href="public/login" class="font-semibold hover:underline" style="color: #7D9B76;">Inicia sesión</a>
                            </p>
                            <p>
                                ¿Tienes un código listo?
                                <a href="restablecer_password.php" class="hover:underline" style="color: #8FAF88;">Restablecer aquí</a>
                            </p>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>

