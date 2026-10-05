# MenteGuía IA

**Plataforma Web Inteligente para la Regulación de la Ansiedad y el Estrés mediante Inteligencia Artificial**

MenteGuía IA es una aplicación web integral orientada a la prevención, autorregistro y regulación de síntomas leves y moderados de ansiedad y estrés. Mediante un sistema de tamizaje estructurado, inteligencia artificial ética con fallback clínico determinístico y técnicas psicoterapéuticas guiadas interactivas (respiración diafragmática, relajación muscular, mindfulness y anclaje sensorial), la plataforma acompaña al usuario en su bienestar psicoemocional diario.

---

## 🌟 Características Principales y Módulos

1. **Landing Page Informativa:** Módulos educativos sobre qué es la ansiedad y el estrés, aviso legal visible y acceso rápido.
2. **Autenticación y Perfil:** Gestión de usuarios con roles (`user`, `admin`), fecha de nacimiento, ocupación y preferencias de relajación.
3. **Tamizaje Clínico y Scoring:**
   - Cuestionario interactivo multi-paso en Alpine.js con 10 preguntas en escala Likert (Ansiedad y Estrés).
   - Cálculo de riesgo determinístico en `RiskAssessmentService` con umbrales clínicos (`bajo`, `moderado`, `alto`).
   - Informe de resultados detallado redactado estrictamente en **lenguaje no diagnóstico**.
4. **Biblioteca Interactiva de Técnicas (11 Técnicas):**
   - Catálogo filtrable por categoría (`ansiedad`, `estres`) y duración.
   - Pacer visual animado de respiración SVG/CSS con cuenta regresiva.
   - Generador de tonos relajantes y frecuencias binaurales con **Web Audio API**.
   - Temporizador interactivo y modal de retroalimentación (tensión antes/después y satisfacción 1-5).
5. **Diario Emocional y Analítica:**
   - Check-in diario de 1 minuto (ansiedad, estrés, ánimo con emojis, horas de sueño).
   - Gráficas evolutivas interactivas en el Dashboard con **Chart.js** (últimos 30 días).
   - Vista formateada para exportar e imprimir reportes clínicos.
6. **Motor de IA y Recomendaciones Personalizadas:**
   - Análisis del contexto clínico del usuario (tamizaje + registros emocionales + técnicas más practicadas).
   - Soporte para API de Gemini / OpenAI con reglas de seguridad estrictas.
   - **Motor Fallback de Reglas Clínicas en PHP**: Garantiza sugerencias precisas y empáticas inmediatas incluso sin API Keys o sin conexión externa.
7. **Rutas de Atención y Emergencias:**
   - Directorio institucional (CAPs universitarios, EPS y líneas de ayuda pública).
   - Alerta prioritaria en un clic para Línea 106 y Línea 192 ante riesgo alto.
8. **Panel Administrativo Integral:**
   - Dashboard con métricas globales, proporciones de riesgo y técnicas más usadas.
   - CRUD centralizado de técnicas, preguntas de tamizaje y rutas de atención.
   - Gestión de usuarios y roles.
   - Tabla de **auditoría y trazabilidad** (`activity_logs`) sobre acciones administrativas.

---

## 💻 Stack Tecnológico

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade + Tailwind CSS + Alpine.js + Chart.js
- **Base de Datos:** MySQL (`app`)
- **Entorno Local:** XAMPP (Apache + MySQL)
- **Pruebas:** PHPUnit (37 pruebas automatizadas, 100% aprobadas)

---

## 🚀 Instalación y Puesta en Marcha (XAMPP)

### 1. Clonar el repositorio y entrar a la carpeta
```bash
cd C:\xampp\htdocs\plataformapsicologica
```

### 2. Instalar dependencias
```bash
composer install
npm install
npm run build
```

### 3. Configurar entorno y generar llave
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Base de datos MySQL
Crea la base de datos `app` en phpMyAdmin (`http://localhost/phpmyadmin`) con cotejamiento `utf8mb4_unicode_ci`.

Verifica las credenciales en tu archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar migraciones y datos iniciales (Seeders)
```bash
php artisan migrate --seed
```

### 6. Crear enlace simbólico de almacenamiento
```bash
php artisan storage:link
```

---

## 🔑 Credenciales de Prueba Predeterminadas

| Rol | Correo Electrónico | Contraseña | Acceso |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@menteguia.com` | `password123` | Panel Admin (`/admin`) y Dashboard |
| **Usuario Regular** | `usuario@menteguia.com` | `password123` | Mi Espacio (`/dashboard`) |

---

## 🧪 Ejecución de Pruebas Automatizadas

Para ejecutar la suite completa de pruebas unitarias y de integración:
```bash
php artisan test
```

---

## ⚠️ Declaración de Enfoque y Aviso Legal

> **MenteGuía IA** está diseñada como una herramienta de apoyo preventivo y aprendizaje de habilidades de autorregulación ante síntomas leves o moderados de **ansiedad** y **estrés**.
> **No realiza diagnósticos clínicos, no prescribe tratamientos médicos y no reemplaza la evaluación o psicoterapia impartida por profesionales de la salud mental colegiados.**
> Ante situaciones de crisis aguda o riesgo para la vida, la plataforma orienta prioritariamente hacia las líneas telefónicas de emergencia gratuitas y servicios hospitalarios de urgencia.
