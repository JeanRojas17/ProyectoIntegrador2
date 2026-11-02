
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `transportes_rbl` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `transportes_rbl`;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(10) NOT NULL DEFAULT 'CC',
  `numero_documento` varchar(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_numero_documento_unique` (`numero_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'NIT','900123456-1','Distribuidora El Progreso S.A.S.','3104567890','compras@elprogreso.com','Calle 45 # 12-30, Bogotá','2026-11-03 00:40:52','2026-11-03 00:40:52'),(2,'CC','1020304050','María Fernanda Gómez','3209876543','maria.gomez@correo.com','Carrera 8 # 20-15, Medellín','2026-11-03 00:40:52','2026-11-03 00:40:52'),(3,'NIT','901987654-2','Alimentos del Norte Ltda.','6053214567','logistica@alimentosnorte.com','Vía 40 # 65-10, Barranquilla','2026-11-03 00:40:52','2026-11-03 00:40:52'),(4,'CC','79456123','Carlos Andrés Ramírez','3157894561','carlos.ramirez@correo.com','Avenida 6N # 23-45, Cali','2026-11-03 00:40:52','2026-11-03 00:40:52'),(5,'NIT','800456789-3','Ferretería Central S.A.','3006549871','pedidos@ferreteriacentral.com','Calle 80 # 50-22, Bucaramanga','2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `conductores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conductores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(10) NOT NULL DEFAULT 'CC',
  `numero_documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `numero_licencia` varchar(30) NOT NULL,
  `categoria_licencia` varchar(10) NOT NULL,
  `fecha_vencimiento_licencia` date NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conductores_numero_documento_unique` (`numero_documento`),
  UNIQUE KEY `conductores_numero_licencia_unique` (`numero_licencia`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `conductores` WRITE;
/*!40000 ALTER TABLE `conductores` DISABLE KEYS */;
INSERT INTO `conductores` VALUES (1,'CC','71234567','Jorge Luis','Martínez Rojas','3114567890','LIC-001234','C3','2027-05-15','disponible','2026-11-03 00:40:52','2026-11-03 00:40:52'),(2,'CC','79845612','Andrés Felipe','Cárdenas Peña','3125678901','LIC-005678','C2','2026-11-30','en_ruta','2026-11-03 00:40:52','2026-11-03 00:40:52'),(3,'CC','1098765432','Luis Alberto','Torres Vega','3136789012','LIC-009012','C3','2028-02-20','disponible','2026-11-03 00:40:52','2026-11-03 00:40:52'),(4,'CE','E1234567','Pedro José','Salazar Mora','3147890123','LIC-003456','C2','2027-08-10','en_ruta','2026-11-03 00:40:52','2026-11-03 00:40:52'),(5,'CC','1023456789','Diego Armando','Herrera Ruiz','3158901234','LIC-007890','C3','2029-01-05','inactivo','2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `conductores` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `envios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `envios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `conductor_id` bigint(20) unsigned NOT NULL,
  `vehiculo_id` bigint(20) unsigned NOT NULL,
  `ruta_id` bigint(20) unsigned NOT NULL,
  `fecha_envio` date NOT NULL,
  `descripcion_carga` text NOT NULL,
  `peso_kg` decimal(10,2) NOT NULL,
  `valor_declarado` decimal(12,2) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `envios_codigo_unique` (`codigo`),
  KEY `envios_cliente_id_foreign` (`cliente_id`),
  KEY `envios_conductor_id_foreign` (`conductor_id`),
  KEY `envios_vehiculo_id_foreign` (`vehiculo_id`),
  KEY `envios_ruta_id_foreign` (`ruta_id`),
  CONSTRAINT `envios_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `envios_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `envios_ruta_id_foreign` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `envios_vehiculo_id_foreign` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `envios` WRITE;
/*!40000 ALTER TABLE `envios` DISABLE KEYS */;
INSERT INTO `envios` VALUES (1,'ENV-2026-001',1,1,1,1,'2026-10-15','Cajas de alimentos no perecederos',3200.00,4500000.00,'entregado','2026-11-03 00:40:52','2026-11-03 00:40:52'),(2,'ENV-2026-002',2,2,2,2,'2026-10-18','Electrodomésticos',1800.00,7800000.00,'en_transito','2026-11-03 00:40:52','2026-11-03 00:40:52'),(3,'ENV-2026-003',3,3,3,3,'2026-10-20','Materiales de ferretería',5400.00,3200000.00,'pendiente','2026-11-03 00:40:52','2026-11-03 00:40:52'),(4,'ENV-2026-004',4,4,4,4,'2026-10-22','Productos de aseo',950.00,1200000.00,'entregado','2026-11-03 00:40:52','2026-11-03 00:40:52'),(5,'ENV-2026-005',5,5,5,5,'2026-10-25','Repuestos automotrices',2100.00,5600000.00,'en_transito','2026-11-03 00:40:52','2026-11-03 00:40:52'),(6,'ENV-2026-006',1,1,1,1,'2026-10-28','Muebles de oficina',1400.00,2900000.00,'cancelado','2026-11-03 00:40:52','2026-11-03 00:40:52'),(7,'ENV-2026-007',2,2,2,2,'2026-11-01','Textiles y confecciones',700.00,1800000.00,'pendiente','2026-11-03 00:40:52','2026-11-03 00:40:52'),(8,'ENV-2026-008',3,3,3,3,'2026-11-02','Bebidas embotelladas',4100.00,2300000.00,'en_transito','2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `envios` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_02_000001_create_clientes_table',1),(5,'2026_11_02_000002_create_conductores_table',1),(6,'2026_11_02_000003_create_vehiculos_table',1),(7,'2026_11_02_000004_create_rutas_table',1),(8,'2026_11_02_000005_create_envios_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rutas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `origen` varchar(100) NOT NULL,
  `destino` varchar(100) NOT NULL,
  `distancia_km` decimal(10,2) NOT NULL,
  `tiempo_estimado_horas` decimal(8,2) DEFAULT NULL,
  `tarifa_base` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `rutas` WRITE;
/*!40000 ALTER TABLE `rutas` DISABLE KEYS */;
INSERT INTO `rutas` VALUES (1,'Bogotá','Medellín',415.00,8.50,850000.00,'2026-11-03 00:40:52','2026-11-03 00:40:52'),(2,'Bogotá','Cali',460.00,9.00,920000.00,'2026-11-03 00:40:52','2026-11-03 00:40:52'),(3,'Medellín','Barranquilla',700.00,13.00,1350000.00,'2026-11-03 00:40:52','2026-11-03 00:40:52'),(4,'Cali','Bucaramanga',780.00,15.00,1480000.00,'2026-11-03 00:40:52','2026-11-03 00:40:52'),(5,'Barranquilla','Cartagena',120.00,2.50,320000.00,'2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `rutas` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('lKI2Nwh0cW0OQm1tcEbxiHmzBzXZhhB69xlxmMey',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; es-CO) WindowsPowerShell/5.1.19041.6575','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVDE3NUlVZG9GU01mWkY1STFtb21sZ01IY3ZhT0ZEWUVHS0YwczQ1aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODEyNC9lbnZpb3MiO3M6NToicm91dGUiO3M6MTI6ImVudmlvcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImUyNDBkMTFlOTExNjJkYjRlNzliYmQwODFjNjE4YjkzNmQ3NzM1YjRiZTI1MWY0YjYzZDc3ODNlZjVmZGQ4NDEiO30=',1793648497),('qxJkp0SOpMFu23bcmYmmp3GdL5Lfq0St3XiLH920',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; es-CO) WindowsPowerShell/5.1.19041.6575','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicE9UaEkxcnEydFlMdWwxSDhPRUY4RlQyMjl5SUJrM1BvelFtOUlvaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODEyMy9lbnZpb3MvMSI7czo1OiJyb3V0ZSI7czoxMToiZW52aW9zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJlMjQwZDExZTkxMTYyZGI0ZTc5YmJkMDgxYzYxOGI5MzZkNzczNWI0YmUyNTFmNGI2M2Q3NzgzZWY1ZmRkODQxIjt9',1793648480);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador RBL','admin@transportesrbl.com',NULL,'$2y$12$OmE4XaAMGGlALlEFUaxFXeoSvXLk3DnKQXQInqgtrLT7Cq7SzLVtW',NULL,'2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` smallint(5) unsigned NOT NULL,
  `tipo_vehiculo` varchar(50) NOT NULL,
  `capacidad_carga` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehiculos_placa_unique` (`placa`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,'ABC123','Chevrolet','NPR',2021,'Camión',4500.00,'disponible','2026-11-03 00:40:52','2026-11-03 00:40:52'),(2,'DEF456','Hino','Dutro',2022,'Camión',6000.00,'en_ruta','2026-11-03 00:40:52','2026-11-03 00:40:52'),(3,'GHI789','Renault','Kangoo',2020,'Furgón',1500.00,'mantenimiento','2026-11-03 00:40:52','2026-11-03 00:40:52'),(4,'JKL012','Mercedes-Benz','Accelo',2023,'Camión',8000.00,'disponible','2026-11-03 00:40:52','2026-11-03 00:40:52'),(5,'MNO345','Toyota','Hilux',2019,'Camioneta',1000.00,'inactivo','2026-11-03 00:40:52','2026-11-03 00:40:52');
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

