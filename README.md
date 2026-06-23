# Sistema de Llamados - CAC Santa Bárbara

Aplicación web para realizar rondas diarias de inspección de llamados médicos y baños en un hospital, construida con Laravel 13 y Vue 3.

## Requisitos

- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL

## Instalación

1. Clonar el repositorio
2. Instalar dependencias de PHP:
   ```bash
   composer install
   ```
3. Instalar dependencias de Node.js:
   ```bash
   npm install
   npm install vue@3 @vitejs/plugin-vue --save-dev
   ```
4. Copiar el archivo `.env.example` a `.env` y configurar las variables de entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configurar la base de datos en el archivo `.env`
6. Ejecutar las migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```
7. Instalar Laravel Sanctum:
   ```bash
   php artisan install:api
   ```
8. Compilar los assets:
   ```bash
   npm run dev
   ```
9. Iniciar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

## Credenciales de prueba

- Email: admin@example.com
- Contraseña: clinica2024

## Estructura del proyecto

- **app/Models**: Modelos de Eloquent
- **app/Http/Controllers**: Controladores
- **database/migrations**: Migraciones de base de datos
- **database/seeders**: Seeders
- **resources/js**: Aplicación Vue.js
- **routes**: Rutas de la aplicación

## Funcionalidades

- Autenticación de usuarios
- Rondas diarias de inspección
- Registro de estados de habitaciones y baños
- Historial de cambios
- Exportación de datos
