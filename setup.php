<?php
/**
 * Sukha - Script de configuración inicial de Base de Datos
 * Crea la base de datos 'sukha' y las tablas 'usuarios' y 'password_resets'
 */

// Configuración de conexión inicial (MySQL en XAMPP)
$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';

$isCli = (php_sapi_name() === 'cli');

function outputMsg(string $msg, string $type = 'info'): void {
    global $isCli;
    if ($isCli) {
        $prefix = match($type) {
            'success' => '[OK] ',
            'error'   => '[ERROR] ',
            default   => '[INFO] '
        };
        echo $prefix . $msg . PHP_EOL;
    } else {
        $color = match($type) {
            'success' => '#2e7d32',
            'error'   => '#c62828',
            default   => '#1565c0'
        };
        echo "<p style='color: {$color}; font-family: sans-serif; padding: 4px 8px;'><strong>" . htmlspecialchars($msg) . "</strong></p>";
    }
}

try {
    // 1. Conexión al motor MySQL sin base de datos seleccionada
    $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    outputMsg("Conexión al motor MySQL establecida exitosamente.", "success");

    // 2. Crear base de datos 'sukha' con cotejamiento utf8mb4_unicode_ci
    $sqlDb = "CREATE DATABASE IF NOT EXISTS `sukha` 
              CHARACTER SET utf8mb4 
              COLLATE utf8mb4_unicode_ci;";
    $pdo->exec($sqlDb);
    outputMsg("Base de datos 'sukha' creada o verificada con cotejamiento utf8mb4_unicode_ci.", "success");

    // 3. Seleccionar la base de datos 'sukha'
    $pdo->exec("USE `sukha`;");

    // 4. Crear tabla 'usuarios'
    $sqlUsuarios = "CREATE TABLE IF NOT EXISTS `usuarios` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(100) NOT NULL,
        `email` VARCHAR(150) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `latitud` DECIMAL(10, 7) NULL,
        `longitud` DECIMAL(10, 7) NULL,
        `ciudad` VARCHAR(100) NULL,
        `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_usuarios_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $pdo->exec($sqlUsuarios);
    outputMsg("Tabla 'usuarios' creada o verificada (id, nombre, email UNIQUE, password, latitud, longitud, ciudad, creado_en).", "success");

    // 5. Crear tabla 'password_resets'
    $sqlPasswordResets = "CREATE TABLE IF NOT EXISTS `password_resets` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `email` VARCHAR(150) NOT NULL,
        `codigo_hash` VARCHAR(255) NOT NULL,
        `expira` DATETIME NOT NULL,
        `usado` TINYINT(1) DEFAULT 0,
        INDEX `idx_pwd_resets_email_usado` (`email`, `usado`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $pdo->exec($sqlPasswordResets);
    outputMsg("Tabla 'password_resets' creada o verificada (id, email, codigo_hash, expira, usado).", "success");

    outputMsg("Configuración de base de datos 'sukha' completada con éxito.", "success");

} catch (PDOException $e) {
    outputMsg("Error al configurar la base de datos: " . $e->getMessage(), "error");
    if ($isCli) {
        exit(1);
    }
}
