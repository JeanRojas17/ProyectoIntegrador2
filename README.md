# Transportes RBL

Sistema web de gestión de transportes de carga desarrollado con **Laravel 12** y **MySQL/MariaDB**. Incluye autenticación y CRUD completo de clientes, conductores, vehículos, rutas y envíos.

## Requisitos

- PHP >= 8.2
- Composer
- MySQL / MariaDB (XAMPP)
- Extensiones PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crea la base de datos y ajusta las credenciales en `.env`:

```sql
CREATE DATABASE transportes_rbl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transportes_rbl
DB_USERNAME=root
DB_PASSWORD=
```

Ejecuta las migraciones y los datos de prueba:

```bash
php artisan migrate:fresh --seed
```

Inicia el servidor:

```bash
php artisan serve
```

Abre http://localhost:8000

## Usuario de prueba

| Correo | Contraseña |
| --- | --- |
| `admin@transportesrbl.com` | `password` |

## Módulos (CRUD)

- **Clientes** — `/clientes`
- **Conductores** — `/conductores`
- **Vehículos** — `/vehiculos`
- **Rutas** — `/rutas`
- **Envíos** — `/envios` (relaciona cliente, conductor, vehículo y ruta)

## Modelo de base de datos

| Tabla | Descripción |
| --- | --- |
| `clientes` | Documento, nombre/razón social, teléfono, correo y dirección |
| `conductores` | Documento, nombres, licencia, categoría, vencimiento y estado |
| `vehiculos` | Placa, marca, modelo, año, tipo, capacidad y estado |
| `rutas` | Origen, destino, distancia, tiempo estimado y tarifa base |
| `envios` | Código, FKs, fecha, carga, peso, valor declarado y estado |

## Script de base de datos

El respaldo se encuentra en `database/script_bd.sql`. Para restaurarlo:

```bash
mysql -u root -p < database/script_bd.sql
```

## Documentación y mockups

- Documentación técnica con normas APA: `docs/documentacion.md`
- Prototipos (SCE 2.10): `docs/mockups/index.html`

## Estructura relevante

```
app/Http/Controllers/   Controladores CRUD y autenticación
app/Http/Requests/      Validaciones (Form Requests)
app/Models/             Modelos Eloquent con relaciones
database/migrations/    Esquema de la base de datos
database/seeders/       Datos de prueba
resources/views/        Vistas Blade (layout, auth, CRUD)
routes/web.php          Rutas de la aplicación
docs/                   Documentación y mockups
```
