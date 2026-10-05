<?php
/**
 * Sukha - Conexión a Base de Datos con PDO
 * Conecta a la base de datos 'sukha' en MySQL usando cotejamiento UTF-8 y consultas preparadas.
 */

// Función auxiliar para leer variables del archivo .env sin dependencias externas
if (!function_exists('cargarVariablesEnv')) {
    function cargarVariablesEnv(string $rutaEnv): void {
        if (!file_exists($rutaEnv)) {
            return;
        }

        $lineas = file($rutaEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            $linea = trim($linea);
            // Ignorar comentarios
            if ($linea === '' || str_starts_with($linea, '#')) {
                continue;
            }
            // Parsear clave=valor
            if (str_contains($linea, '=')) {
                [$clave, $valor] = explode('=', $linea, 2);
                $clave = trim($clave);
                $valor = trim($valor);
                // Quitar comillas si las tiene
                $valor = trim($valor, '"\'');
                if (!array_key_exists($clave, $_ENV) && getenv($clave) === false) {
                    $_ENV[$clave] = $valor;
                    putenv("{$clave}={$valor}");
                }
            }
        }
    }
}

// Cargar variables de entorno desde .env
$envPath = __DIR__ . '/.env';
cargarVariablesEnv($envPath);

/**
 * Retorna la instancia de conexión PDO a la base de datos 'sukha'
 */
function obtenerConexion(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1');
    $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306);
    // Priorizar base de datos 'sukha'
    $dbName = 'sukha';
    $user = getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'root');
    $pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : ($_ENV['DB_PASSWORD'] ?? '');

    $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $opciones);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Error de conexión a la base de datos Sukha: " . $e->getMessage());
        throw new PDOException("No fue posible conectar con la base de datos de Sukha. Por favor verifica que el servicio MySQL esté activo.");
    }
}

// Variable global $pdo para scripts que la requieran directamente
try {
    $pdo = obtenerConexion();
} catch (Exception $e) {
    // Si se incluye en un script, la excepción será manejada por el llamador
    $pdo = null;
}

