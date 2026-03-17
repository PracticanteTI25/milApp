<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## milApp
Web que se esta desarrollando para uso corporativo, documentación de lo que se lleva hasta el momento:

## Arquitectura del Sistema

El proyecto sigue el patrón MVC (Modelo - Vista - Controlador):

Modelo: Interacción con la base de datos (usuarios)

Vista: Plantillas Blade con integración de AdminLTE

Controlador: Gestión de lógica de negocio

Estructura relevante:
app/
 ├── Http/
 │    ├── Controllers/
 │    ├── Middleware/
resources/
 ├── views/
routes/
 ├── web.php
public/
 ├── css/


## Autenticación

Se implementó un sistema de autenticación personalizado sin uso de paquetes preconfigurados (Breeze, Jetstream).

Características:

Validación manual de credenciales

Persistencia de sesión mediante:

session(['user' => $usuario]);

Redirección controlada post-login

Cierre de sesión mediante destrucción de sesión

## Autorización y Control de Acceso
Middleware de Autenticación

Middleware auth.custom encargado de:

Verificar existencia de sesión activa

Restringir acceso a rutas protegidas

Redirigir a login si no hay autenticación

## Módulo de Gestión de Usuarios (CRUD)
Operaciones Implementadas

Create: Registro de nuevos usuarios

Read: Listado de usuarios

Update: Modificación de datos (incluye actualización opcional de contraseña)

Delete: Eliminación de registros

## Validaciones
Validaciones a nivel del controlador

## Seguridad
- Encriptación de contraseñas:

$usuario->password = bcrypt($request->password);

- Prevención de duplicidad en correos

- Validación de entrada de datos

## Capa de Presentación

Se utilizó AdminLTE como base visual, aplicando personalización mediante:

- Archivo public/css/custom.css

- Variables de color basadas en manual de marca

- Sobrescritura de estilos por defecto

### Personalizaciones:

Cambio de paleta de colores
Integración de logo 
Eliminación de elementos visuales no requeridos

## Estado del Proyecto:

- Autenticación funcional

- Control de acceso por roles

- CRUD de usuarios operativo

- Interfaz personalizada

- Validaciones implementadas