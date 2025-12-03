# Generador de Contraseñas

Aplicación web simple hecha en Laravel que permite generar contraseñas seguras y personalizadas.

## Requisitos

- PHP 8.x
- Composer
- Laravel 12

## Instalación

```bash
git clone https://github.com/CCAYCHOG/password-generator-laravel.git
cd password-generator-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan serve