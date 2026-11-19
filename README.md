# Proyecto Tienda

Sistema de gestión para tienda de conveniencia (inventario, empleados, ventas, compras, devoluciones y facturación) desarrollado en PHP 8.2 con MySQL/MariaDB.

## Estructura del proyecto

```
Proyecto_Tienda/
├── config/          # Configuración de la aplicación y base de datos
├── public/          # DocumentRoot público (Front Controller y assets)
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   ├── .htaccess
│   └── index.php
├── src/
│   ├── Controllers/ # Controladores por dominio
│   ├── Core/        # Autoloader, Auth, Controller, Database, Router
│   └── Models/      # Acceso a datos y transacciones
├── views/           # Plantillas HTML/PHP organizadas por módulo y layouts
├── .env.example     # Plantilla de variables de entorno
├── .gitignore
└── .htaccess        # Redirección hacia public/ en XAMPP
```

## Configuración inicial

1. Copia `.env.example` a `.env` y ajusta tus credenciales de base de datos:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=proyecto_fbd
   DB_USER=root
   DB_PASS=
   ```
2. En XAMPP, asegúrate de que `mod_rewrite` esté habilitado y accede a:
   `http://localhost/Proyecto_Tienda/`
   (O bien ejecuta `php -S localhost:8000 -t public` para desarrollo local).

## Rutas principales

- `/login` — Inicio de sesión (también soporta `/IniciarSesion.php`)
- `/logout` — Cierre de sesión
- `/admin` — Panel de administración (requiere rol `Administrador`)
  - `/empleados/registrar`, `/empleados/modificar`, `/empleados/eliminar`
  - `/productos/registrar`, `/productos/modificar`, `/productos/eliminar`
  - `/movimientos`
- `/cajero` — Panel de cajero (requiere rol `Cajero`)
  - `/ventas/registrar`
  - `/compras/registrar`
  - `/devoluciones/registrar`
  - `/factura`, `/factura/generada`
