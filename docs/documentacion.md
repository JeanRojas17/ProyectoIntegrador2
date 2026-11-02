# Sistema de Gestión de Transportes RBL

## Documentación técnica del proyecto (Normas APA 7.ª edición)

**Institución:** _[Nombre de la institución]_
**Programa:** _[Nombre del programa académico]_
**Asignatura:** _[Nombre de la asignatura]_
**Docente:** _[Nombre del docente]_
**Integrantes:**
- _[Integrante 1]_
- _[Integrante 2]_
- _[Integrante 3]_

**Fecha:** 2 de noviembre de 2026

---

## Resumen

El presente proyecto describe el desarrollo de un sistema web para la gestión de una empresa de transportes de carga, construido con el framework Laravel 12 y el gestor de bases de datos MariaDB/MySQL. El sistema implementa operaciones CRUD (crear, leer, actualizar y eliminar) sobre cinco entidades principales: clientes, conductores, vehículos, rutas y envíos, además de un módulo de autenticación de usuarios. La arquitectura sigue el patrón Modelo-Vista-Controlador (MVC) y emplea migraciones y semillas para la reproducibilidad de la base de datos. Se documentan el modelo de datos, los comandos de Laravel utilizados, los prototipos de interfaz y el repositorio de código, en cumplimiento de los criterios SCE 2.7 a 2.11.

**Palabras clave:** Laravel, CRUD, MVC, base de datos, transportes, APA 7.

---

## Índice

1. Introducción
2. Planteamiento del problema
3. Objetivos
4. Marco teórico
5. Metodología
6. Modelo de base de datos (SCE 2.7)
7. Operaciones CRUD (SCE 2.8)
8. Comandos de Laravel utilizados (SCE 2.11)
9. Prototipado rápido / Mockups (SCE 2.10)
10. Código fuente, script de base de datos y repositorio Git (SCE 2.9)
11. Conclusiones
12. Referencias

---

## 1. Introducción

La digitalización de los procesos logísticos permite a las empresas de transporte reducir errores operativos y mejorar el control sobre sus recursos. El sistema **Transportes RBL** centraliza la información de clientes, conductores, vehículos, rutas y envíos en una aplicación web accesible desde el navegador. El desarrollo se realiza sobre Laravel, framework de código abierto para PHP que facilita la implementación del patrón MVC, la seguridad y la gestión de la base de datos (Otwell, 2026).

## 2. Planteamiento del problema

Muchas empresas de transporte administran su información en hojas de cálculo o registros manuales, lo que dificulta la consulta oportuna de datos, la asignación de vehículos y el seguimiento de los envíos. Se requiere una herramienta que permita registrar, consultar, actualizar y eliminar la información de manera controlada y segura.

## 3. Objetivos

### 3.1 Objetivo general

Desarrollar un sistema web con Laravel que permita gestionar mediante CRUD las entidades principales de una empresa de transportes.

### 3.2 Objetivos específicos

- Diseñar el modelo relacional de la base de datos con sus llaves primarias y foráneas.
- Implementar las operaciones CRUD para clientes, conductores, vehículos, rutas y envíos.
- Incorporar autenticación de usuarios para proteger el acceso al sistema.
- Elaborar los prototipos de interfaz y la documentación técnica bajo normas APA.

## 4. Marco teórico

**Laravel** es un framework PHP basado en el patrón MVC que ofrece un ORM denominado Eloquent, migraciones para versionar el esquema de la base de datos y un sistema de plantillas Blade (Otwell, 2026). **MySQL/MariaDB** es un sistema de gestión de bases de datos relacional ampliamente utilizado en aplicaciones web (Oracle Corporation, 2026). El patrón **MVC** separa la lógica de negocio (modelo), la presentación (vista) y el control de las peticiones (controlador), lo que favorece el mantenimiento del software (Gamma et al., 1994).

## 5. Metodología

Se aplicó una metodología de desarrollo incremental. Primero se configuró el entorno (PHP 8.2, Composer, Laravel 12 y MariaDB). Luego se crearon las migraciones y modelos, después los controladores y las validaciones, y finalmente las vistas Blade con Bootstrap. Cada incremento fue verificado mediante pruebas funcionales de las rutas y de las operaciones de creación y consulta.

## 6. Modelo de base de datos (SCE 2.7)

La base de datos se denomina `transportes_rbl` y está compuesta por las siguientes tablas:

| Tabla | Campos principales | Relaciones |
| --- | --- | --- |
| `users` | id, name, email, password | Autenticación |
| `clientes` | id, tipo_documento, numero_documento, nombre, telefono, email, direccion | 1:N con `envios` |
| `conductores` | id, tipo_documento, numero_documento, nombres, apellidos, telefono, numero_licencia, categoria_licencia, fecha_vencimiento_licencia, estado | 1:N con `envios` |
| `vehiculos` | id, placa, marca, modelo, anio, tipo_vehiculo, capacidad_carga, estado | 1:N con `envios` |
| `rutas` | id, origen, destino, distancia_km, tiempo_estimado_horas, tarifa_base | 1:N con `envios` |
| `envios` | id, codigo, cliente_id, conductor_id, vehiculo_id, ruta_id, fecha_envio, descripcion_carga, peso_kg, valor_declarado, estado | N:1 con las cuatro tablas anteriores |

### 6.1 Relaciones

- Un **cliente** puede tener muchos **envíos**.
- Un **conductor** puede tener muchos **envíos**.
- Un **vehículo** puede tener muchos **envíos**.
- Una **ruta** puede tener muchos **envíos**.
- Cada **envío** pertenece a un cliente, un conductor, un vehículo y una ruta.

Las llaves foráneas se declararon con integridad referencial y eliminación en cascada, de modo que al eliminar un registro padre se eliminan sus envíos asociados.

### 6.2 Diagrama entidad-relación (texto)

```
clientes (1) ────< envios >──── (1) conductores
vehiculos (1) ───< envios >──── (1) rutas
```

## 7. Operaciones CRUD (SCE 2.8)

Cada entidad cuenta con un controlador de recursos con las acciones `index`, `create`, `store`, `show`, `edit`, `update` y `destroy`, además de un Form Request para validar los datos y vistas Blade para la interfaz.

| Entidad | Ruta base | Controlador | Validación |
| --- | --- | --- | --- |
| Clientes | `/clientes` | `ClienteController` | `ClienteRequest` |
| Conductores | `/conductores` | `ConductorController` | `ConductorRequest` |
| Vehículos | `/vehiculos` | `VehiculoController` | `VehiculoRequest` |
| Rutas | `/rutas` | `RutaController` | `RutaRequest` |
| Envíos | `/envios` | `EnvioController` | `EnvioRequest` |

Las operaciones de eliminación solicitan confirmación al usuario y las acciones se protegen mediante autenticación y tokens CSRF.

## 8. Comandos de Laravel utilizados (SCE 2.11)

A continuación se relacionan los comandos de Artisan empleados durante el desarrollo:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:list
php artisan serve
```

Comandos de generación de código que estructuran el proyecto:

```bash
php artisan make:model Cliente -m
php artisan make:model Conductor -m
php artisan make:model Vehiculo -m
php artisan make:model Ruta -m
php artisan make:model Envio -m
php artisan make:controller ClienteController --resource
php artisan make:controller ConductorController --resource
php artisan make:controller VehiculoController --resource
php artisan make:controller RutaController --resource
php artisan make:controller EnvioController --resource
php artisan make:request ClienteRequest
php artisan make:request ConductorRequest
php artisan make:request VehiculoRequest
php artisan make:request RutaRequest
php artisan make:request EnvioRequest
```

> En este proyecto los archivos se crearon y ajustaron manualmente sobre la estructura generada por Laravel; los comandos anteriores documentan el flujo recomendado para replicarlo.

## 9. Prototipado rápido / Mockups (SCE 2.10)

Los prototipos de baja fidelidad se encuentran en la carpeta `docs/mockups/` y se abren en cualquier navegador:

- `login.html`: pantalla de inicio de sesión.
- `dashboard.html`: panel con indicadores y últimos envíos.
- `clientes-listado.html`: tabla de clientes con acciones.
- `cliente-formulario.html`: formulario de creación y edición.
- `envios-listado.html`: listado de envíos con relaciones.

## 10. Código fuente, script de base de datos y repositorio Git (SCE 2.9)

- **Código fuente:** proyecto Laravel completo en el repositorio.
- **Script de base de datos:** `database/script_bd.sql`, generado con `mysqldump`.
- **Repositorio Git:** https://github.com/JeanRojas17/ProyectoIntegrador2.git

Para restaurar la base de datos:

```bash
mysql -u root -p < database/script_bd.sql
```

## 11. Conclusiones

El sistema implementa de forma funcional los CRUD solicitados y una estructura de base de datos relacional coherente con el negocio de transporte. El uso de Laravel permitió estandarizar el código, validar la información y proteger el acceso mediante autenticación. La documentación y los mockups facilitan la comprensión y replicación del proyecto por parte de terceros.

## 12. Referencias

American Psychological Association. (2020). *Publication manual of the American Psychological Association* (7.ª ed.). https://doi.org/10.1037/0000165-000

Gamma, E., Helm, R., Johnson, R., & Vlissides, J. (1994). *Design patterns: Elements of reusable object-oriented software*. Addison-Wesley.

Oracle Corporation. (2026). *MySQL 8.0 reference manual*. https://dev.mysql.com/doc/

Otwell, T. (2026). *Laravel documentation* (Versión 12) [Software]. https://laravel.com/docs

Sommerville, I. (2016). *Software engineering* (10.ª ed.). Pearson.
