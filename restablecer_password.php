<?php
/**
 * Sukha - Restablecer Contraseña (Paso 2: Verificación de Código y Nueva Clave)
 * Valida el correo, verifica el código de 6 dígitos con hash y tiempo de expiración (15 min),
 * y actualiza la contraseña con password_hash mediante consultas preparadas.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';

$exito = false;
$errores = [];
$emailIngresado = strtolower(trim($_GET['email'] ?? ($_POST['email'] ?? '')));
$codigoIngresado = trim($_POST['codigo'] ?? '');

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $errores[] = "Sesión inválida o expirada. Por favor recarga la página.";
    } else {
        $email                 = strtolower(trim($_POST['email'] ?? ''));
        $codigo                = trim($_POST['codigo'] ?? '');
        $password              = $_POST['password'] ?? '';
        $password_confirmation = $_POST['password_confirmation'] ?? '';

        $emailIngresado = $email;
        $codigoIngresado = $codigo;

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Por favor ingresa un correo electrónico válido.";
        }

        if ($codigo === '' || !preg_match('/^[0-9]{6}$/', $codigo)) {
            $errores[] = "El código de seguridad debe tener exactamente 6 dígitos numéricos.";
        }

        if (strlen($password) < 8) {
            $errores[] = "La nueva contraseña debe tener al menos 8 caracteres.";
        }

        if ($password !== $password_confirmation) {
            $errores[] = "Las contraseñas ingresadas no coinciden.";
        }

        if (empty($errores)) {
            try {
                // Buscar registros activos y no expirados para este correo
                $stmt = $pdo->prepare("
                    SELECT id, email, codigo_hash, expira, usado
                    FROM password_resets
                    WHERE email = ? AND usado = 0 AND expira > NOW()
                    ORDER BY id DESC
                    LIMIT 5
                ");
                $stmt->execute([$email]);
                $registros = $stmt->fetchAll();

                $registroValido = null;
                foreach ($registros as $reg) {
                    if (password_verify($codigo, $reg['codigo_hash'])) {
                        $registroValido = $reg;
                        break;
                    }
                }

                if ($registroValido) {
                    // Hashear la nueva contraseña
                    $nuevoPasswordHash = password_hash($password, PASSWORD_DEFAULT);

                    // Actualizar contraseña del usuario con consulta preparada
                    $stmtUser = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
                    $stmtUser->execute([$nuevoPasswordHash, $email]);

                    // Marcar el código como usado (un solo uso)
                    $stmtMarcar = $pdo->prepare("UPDATE password_resets SET usado = 1 WHERE id = ?");
                    $stmtMarcar->execute([$registroValido['id']]);

                    // Invalidar cualquier otro código remanente para mayor seguridad
                    $stmtLimpiar = $pdo->prepare("UPDATE password_resets SET usado = 1 WHERE email = ?");
                    $stmtLimpiar->execute([$email]);

                    $exito = true;
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $errores[] = "El código ingresado es incorrecto o ha vencido. Recuerda que cada código es válido únicamente durante 15 minutos.";
                }
            } catch (PDOException $e) {
                error_log("Error al restablecer contraseña: " . $e->getMessage());
                $errores[] = "Ocurrió un error al actualizar la contraseña. Por favor intenta de nuevo.";
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
    <title>Sukha — Restablecer Contraseña</title>
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

                <?php if ($exito): ?>
                    <!-- Vista de Éxito -->
                    <div class="text-center py-4 space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center" style="background-color: #EAF2E9; color: #7D9B76;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">¡Contraseña actualizada!</h2>
                        <p class="text-sm" style="color: #4A6A55;">
                            Tu contraseña ha sido restablecida correctamente. Ya puedes ingresar a Sukha con tus nuevas credenciales.
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
                        <h1 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">Crea tu nueva contraseña</h1>
                        <p class="text-sm mt-1" style="color: #6B7B6E;">Ingresa el código de 6 dígitos que enviamos a tu correo y define tu nueva clave.</p>
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

                    <form method="POST" action="restablecer_password.php" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                        <!-- Correo Electrónico -->
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
                                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="tu@correo.com"
                            >
                        </div>

                        <!-- Código de 6 dígitos -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label for="codigo" class="block text-xs font-semibold uppercase tracking-wider" style="color: #2C3E35;">
                                    Código de 6 dígitos
                                </label>
                                <span class="text-[11px]" style="color: #8FAF88;">Revisa tu correo o spam</span>
                            </div>
                            <input
                                id="codigo"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                name="codigo"
                                value="<?= htmlspecialchars($codigoIngresado) ?>"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-2xl text-lg font-mono font-bold tracking-widest text-center outline-none transition-all"
                                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                                placeholder="000000"
                            >
                        </div>

                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Nueva contraseña
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

                        <!-- Confirmar Nueva Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                                Confirmar nueva contraseña
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
                                placeholder="Repite la nueva contraseña"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide transition-all hover:opacity-90 active:scale-[0.98] mt-2"
                            style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);"
                        >
                            Guardar nueva contraseña
                        </button>

                        <div class="pt-2 text-center text-xs space-y-1" style="color: #6B7B6E;">
                            <p>
                                ¿No te llegó el código o ya expiró?
                                <a href="olvide_password.php" class="font-semibold hover:underline" style="color: #7D9B76;">Solicitar uno nuevo</a>
                            </p>
                            <p>
                                <a href="public/login" class="hover:underline" style="color: #8FAF88;">Volver a Iniciar Sesión</a>
                            </p>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>

