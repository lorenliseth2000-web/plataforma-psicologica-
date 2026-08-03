# MenteGuía IA

Plataforma web inteligente para la regulación de la ansiedad y el estrés mediante inteligencia artificial.

## Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Blade + Tailwind CSS + Alpine.js (Laravel Breeze)
- **Base de datos:** MySQL (`app`)
- **Servidor local:** XAMPP (Apache + MySQL)

## Requisitos

- PHP >= 8.2 con extensiones: `zip`, `pdo_mysql`, `mbstring`, `openssl`
- Composer
- Node.js y npm
- XAMPP con Apache y MySQL activos

## Instalación local (XAMPP)

```bash
# Clonar repositorio
git clone https://github.com/lorenliseth2000-web/plataforma-psicologica-.git
cd plataformapsicologica

# Dependencias PHP
composer install

# Dependencias frontend
npm install && npm run build

# Configurar entorno
copy .env.example .env
php artisan key:generate

# Crear base de datos en phpMyAdmin
# CREATE DATABASE app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Configurar .env
# DB_DATABASE=app
# DB_USERNAME=root
# DB_PASSWORD=

# Migraciones
php artisan migrate

# Enlace simbólico para archivos públicos
php artisan storage:link
```

## Acceso

URL local: `http://localhost/plataformapsicologica/public`

## Módulos planificados

1. Inicio (landing informativa)
2. Registro e inicio de sesión
3. Evaluación inicial (tamizaje)
4. Biblioteca de técnicas
5. Seguimiento emocional
6. Inteligencia artificial (recomendaciones)
7. Rutas de atención
8. Panel administrativo

## Documentación

- Requerimientos originales: `docs/mente-guia-IA.docx`

## Aviso importante

Esta plataforma **no realiza diagnósticos clínicos** ni reemplaza la atención psicológica o psiquiátrica. Es una herramienta de apoyo y orientación.

## Licencia

Proyecto académico / privado.
