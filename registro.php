<?php
/**
 * Sukha - Registro de Usuarios
 * Implementa validación, verificación de correo único, hash de contraseñas,
 * geolocalización opcional con navigator.geolocation y envío de correo de bienvenida con PHPMailer.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/mailer.php';

$errores = [];
$exito = false;
$nombre = '';
$email = '';

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $errores[] = "Sesión inválida o expirada. Por favor actualiza la página e inténtalo nuevamente.";
    } else {
        $nombre                 = trim($_POST['nombre'] ?? '');
        $email                  = strtolower(trim($_POST['email'] ?? ''));
        $password               = $_POST['password'] ?? '';
        $password_confirmation  = $_POST['password_confirmation'] ?? '';
        $latitud                = !empty($_POST['latitud']) ? filter_var($_POST['latitud'], FILTER_VALIDATE_FLOAT) : null;
        $longitud               = !empty($_POST['longitud']) ? filter_var($_POST['longitud'], FILTER_VALIDATE_FLOAT) : null;
        $ciudad                 = !empty($_POST['ciudad']) ? trim($_POST['ciudad']) : null;

        // Validaciones
        if ($nombre === '') {
            $errores[] = "Por favor ingresa tu nombre completo.";
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Por favor ingresa un correo electrónico válido.";
        }

        if (strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres.";
        }

        if ($password !== $password_confirmation) {
            $errores[] = "Las contraseñas no coinciden.";
        }

        // Si no hay errores iniciales, verificar correo único con consulta preparada
        if (empty($errores)) {
            try {
                $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
                $stmtCheck->execute([$email]);
                if ($stmtCheck->fetch()) {
                    $errores[] = "Este correo electrónico ya se encuentra registrado. ¿Deseas iniciar sesión o recuperar tu contraseña?";
                } else {
                    // Guardar contraseña con password_hash (BCRYPT por defecto)
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    // Insertar nuevo usuario con consulta preparada
                    $stmtInsert = $pdo->prepare("
                        INSERT INTO usuarios (nombre, email, password, latitud, longitud, ciudad, creado_en)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())
                    ");
                    $stmtInsert->execute([
                        $nombre,
                        $email,
                        $passwordHash,
                        $latitud !== false ? $latitud : null,
                        $longitud !== false ? $longitud : null,
                        $ciudad
                    ]);

                    // Enviar correo de bienvenida al usuario con PHPMailer
                    enviarCorreoBienvenida($email, $nombre);

                    $exito = true;
                    // Regenerar token CSRF
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
            } catch (PDOException $e) {
                error_log("Error en registro de usuario: " . $e->getMessage());
                $errores[] = "Ocurrió un error al procesar tu solicitud. Por favor intenta de nuevo.";
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
    <title>Sukha — Crear Cuenta</title>
    <!-- Favicon Permanente -->
    <link rel="icon" type="image/png" sizes="192x192" href="favicon.png?v=4">
    <link rel="icon" type="image/png" sizes="64x64" href="favicon.png?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png?v=4">
    <link rel="shortcut icon" type="image/png" href="favicon.png?v=4">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN para renderizado autónomo -->
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

                <?php if ($exito): ?>
                    <!-- Vista de Éxito -->
                    <div class="text-center py-4 space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center" style="background-color: #EAF2E9; color: #7D9B76;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">¡Cuenta creada con éxito!</h2>
                        <p class="text-sm" style="color: #4A6A55;">
                            Bienvenido/a a <strong>Sukha</strong>, <strong><?= htmlspecialchars($nombre) ?></strong>. Te hemos enviado un correo de bienvenida a <strong><?= htmlspecialchars($email) ?></strong> con información para comenzar.
                        </p>
                        <div class="pt-4">
                            <a href="public/login"
                               class="inline-block w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide text-center transition-all hover:opacity-95"
                               style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);">
                                Iniciar Sesión en Sukha
                            </a>
                        </div>
                    </div>
                <?php else: ?>

                    <div class="mb-6">
                        <h1 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">Crea tu espacio en Sukha</h1>
                        <p class="text-sm mt-1" style="color: #6B7B6E;">Regístrate gratis y comienza tu camino hacia el bienestar emocional.</p>
                    </div>

                    <!-- Mensajes de Error -->
                    <?php if (!empty($errores)): ?>
                        <div class="mb-5 px-4 py-3 rounded-2xl text-sm" style="background-color: #FDF0EC; color: #9B3D26; border: 1px solid #E8C4B8;">
                            <p class="font-semibold mb-1">Por favor verifica los siguientes puntos:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <?php foreach ($errores as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="registro.php" class="space-y-4" id="formRegistro">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                        <!-- Campos ocultos de Geolocalización -->
                        <input type="hidden" id="latitud" name="latitud" value="">
                        <input type="hidden" id="longitud" name="longitud" value="">
                        <input type="hidden" id="ciudad" name="ciudad" value="">

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Nombre completo
                            </label>
                            <input
                                id="nombre"
                                type="text"
                                name="nombre"
                                value="<?= htmlspecialchars($nombre) ?>"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="Tu nombre"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($email) ?>"
                                required
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="tu@correo.com"
                            >
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="Mínimo 8 caracteres"
                            >
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Confirmar contraseña
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="Repite tu contraseña"
                            >
                        </div>

                        <!-- Estado discreto de geolocalización (opcional) -->
                        <div id="geo-info" class="text-[11px] text-right" style="color: #8FAF88;">
                            <span id="geo-status">Ubicación opcional para atención local</span>
                        </div>

                        <!-- Botón Enviar -->
                        <button
                            type="submit"
                            class="w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide transition-all hover:opacity-90 active:scale-[0.98] mt-2"
                            style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);"
                        >
                            Crear mi cuenta en Sukha
                        </button>

                        <div class="pt-2 text-center text-xs space-y-1" style="color: #6B7B6E;">
                            <p>
                                ¿Ya tienes una cuenta?
                                <a href="public/login" class="font-semibold hover:underline" style="color: #7D9B76;">Inicia sesión</a>
                            </p>
                            <p>
                                <a href="olvide_password.php" class="hover:underline" style="color: #8FAF88;">¿Olvidaste tu contraseña?</a>
                            </p>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Script de Geolocalización (No obligatoria si el usuario niega el permiso) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ("geolocation" in navigator) {
                var options = {
                    timeout: 6000,
                    maximumAge: 60000,
                    enableHighAccuracy: false
                };

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        var latInput = document.getElementById('latitud');
                        var lonInput = document.getElementById('longitud');
                        var statusEl = document.getElementById('geo-status');

                        if (latInput && lonInput) {
                            latInput.value = pos.coords.latitude.toFixed(7);
                            lonInput.value = pos.coords.longitude.toFixed(7);
                        }
                        if (statusEl) {
                            statusEl.textContent = 'Ubicación vinculada para rutas cercanas';
                            statusEl.style.color = '#7D9B76';
                        }
                    },
                    function(err) {
                        // Si el usuario deniega o hay error, no se interrumpe el registro
                        var statusEl = document.getElementById('geo-status');
                        if (statusEl) {
                            statusEl.textContent = 'Ubicación no proporcionada (opcional)';
                        }
                    },
                    options
                );
            }
        });
    </script>
</body>
</html>

