-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-01-2026 a las 00:49:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `carnetizacion_istpet`
--
CREATE DATABASE IF NOT EXISTS `carnetizacion_istpet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carnetizacion_istpet`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE IF NOT EXISTS `accesos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `laboratorio_id` bigint(20) UNSIGNED NOT NULL,
  `profesor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_entrada` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `fecha_salida` date DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `metodo_registro` enum('qr_estudiante','manual_profesor') DEFAULT 'manual_profesor',
  `marcado_ausente` tinyint(1) DEFAULT 0,
  `nota_ausencia` text DEFAULT NULL,
  `profesor_valida_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profesor_id` (`profesor_id`),
  KEY `idx_fecha_entrada` (`fecha_entrada`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_laboratorio` (`laboratorio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `usuario_id`, `laboratorio_id`, `profesor_id`, `fecha_entrada`, `hora_entrada`, `fecha_salida`, `hora_salida`, `created_at`, `updated_at`, `metodo_registro`, `marcado_ausente`, `nota_ausencia`, `profesor_valida_id`) VALUES
(13, 12, 1, 1, '2026-01-11', '18:04:56', '2026-01-11', '18:09:46', '2026-01-11 23:04:56', '2026-01-11 23:09:46', 'manual_profesor', 1, 'No esta en clase', 1),
(14, 12, 1, 1, '2026-01-11', '18:13:01', '2026-01-11', '18:13:23', '2026-01-11 23:13:01', '2026-01-11 23:13:23', 'manual_profesor', 1, 'No esta en clases', 1),
(15, 12, 1, NULL, '2026-01-12', '23:08:41', '2026-01-12', '23:09:18', '2026-01-13 04:08:41', '2026-01-13 04:09:18', 'qr_estudiante', 0, NULL, NULL),
(16, 12, 1, NULL, '2026-01-12', '23:13:56', '2026-01-12', '23:15:31', '2026-01-13 04:13:56', '2026-01-13 04:15:31', 'qr_estudiante', 0, NULL, NULL),
(17, 12, 1, 1, '2026-01-12', '23:16:02', '2026-01-12', '23:16:26', '2026-01-13 04:16:02', '2026-01-13 04:16:26', 'manual_profesor', 0, NULL, NULL),
(18, 12, 1, 1, '2026-01-12', '23:19:44', '2026-01-12', '23:19:47', '2026-01-13 04:19:44', '2026-01-13 04:19:47', 'manual_profesor', 0, NULL, NULL),
(19, 12, 1, 1, '2026-01-12', '23:19:58', '2026-01-12', '23:21:18', '2026-01-13 04:19:58', '2026-01-13 04:21:18', 'manual_profesor', 0, NULL, NULL),
(20, 12, 1, NULL, '2026-01-13', '10:27:00', '2026-01-13', '10:53:52', '2026-01-13 15:27:00', '2026-01-13 15:53:52', 'qr_estudiante', 0, NULL, NULL),
(21, 12, 1, 1, '2026-01-15', '08:17:52', '2026-01-15', '08:18:11', '2026-01-15 13:17:52', '2026-01-15 13:18:11', 'manual_profesor', 0, NULL, NULL),
(22, 12, 1, 1, '2026-01-15', '08:18:18', '2026-01-15', '09:48:20', '2026-01-15 13:18:18', '2026-01-15 14:48:20', 'manual_profesor', 1, 'no esta en el aula', 1),
(23, 12, 1, NULL, '2026-01-15', '08:34:02', '2026-01-15', '08:34:31', '2026-01-15 13:34:02', '2026-01-15 13:34:31', 'qr_estudiante', 0, NULL, NULL),
(24, 12, 1, NULL, '2026-01-15', '08:36:43', '2026-01-15', '08:38:18', '2026-01-15 13:36:43', '2026-01-15 13:38:18', 'qr_estudiante', 0, NULL, NULL),
(25, 12, 1, 1, '2026-01-15', '10:20:04', '2026-01-15', '20:22:08', '2026-01-15 15:20:04', '2026-01-16 01:22:08', 'manual_profesor', 1, 'No se encuentra el estudiante en clases', 1),
(26, 12, 1, 1, '2026-01-15', '20:22:59', '2026-01-15', '20:23:23', '2026-01-16 01:22:59', '2026-01-16 01:23:23', 'manual_profesor', 1, 'wefgdbnjytrewqfg', 1),
(27, 12, 1, NULL, '2026-01-15', '20:38:25', '2026-01-15', '20:43:07', '2026-01-16 01:38:25', '2026-01-16 01:43:07', 'qr_estudiante', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE IF NOT EXISTS `administradores` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `administradores_usuario_unique` (`usuario`),
  UNIQUE KEY `administradores_email_unique` (`email`),
  KEY `administradores_usuario_index` (`usuario`),
  KEY `administradores_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `email`, `password`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@istpet.edu.ec', '$2y$10$54UHpGdyZhcunNsv1WgLU.N4MKnIZBIrjlMw3gX587wY8cvKi38xm', 'super_admin', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(2, 'admin2', 'admin2@istpet.edu.ec', '$2y$10$/Umkuwgnljl7IDhv4ErV9.WAnMj4m3FpOHubfxqeuulBDbQw.4ksy', 'admin', '2026-01-04 03:08:09', '2026-01-04 03:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carnets`
--

CREATE TABLE IF NOT EXISTS `carnets` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `codigo_qr` varchar(255) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado` enum('activo','vencido','bloqueado') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carnets_codigo_qr_unique` (`codigo_qr`),
  KEY `carnets_codigo_qr_index` (`codigo_qr`),
  KEY `carnets_usuario_id_index` (`usuario_id`),
  KEY `carnets_estado_index` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carnets`
--

INSERT INTO `carnets` (`id`, `usuario_id`, `codigo_qr`, `fecha_emision`, `fecha_vencimiento`, `estado`, `created_at`, `updated_at`) VALUES
(12, 12, 'ISTPET-2026-1726429283', '2026-01-15', '2030-01-15', 'activo', '2026-01-11 23:03:08', '2026-01-16 01:41:37'),
(13, 13, 'ISTPET-2026-BC713307', '2026-01-15', '2030-01-15', 'activo', '2026-01-15 13:47:20', '2026-01-15 13:47:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE IF NOT EXISTS `laboratorios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `ubicacion` varchar(200) NOT NULL,
  `codigo_qr_lab` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo','disponible','mantenimiento') DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laboratorios_nombre_unique` (`nombre`),
  UNIQUE KEY `laboratorios_codigo_qr_lab_unique` (`codigo_qr_lab`),
  KEY `laboratorios_nombre_index` (`nombre`),
  KEY `laboratorios_codigo_qr_lab_index` (`codigo_qr_lab`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `laboratorios`
--

INSERT INTO `laboratorios` (`id`, `nombre`, `capacidad`, `ubicacion`, `codigo_qr_lab`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Laboratorio de Computación 1', 30, 'Edificio A - Piso 2', 'LAB-ISTPET-001', 'activo', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(2, 'Laboratorio de Computación 2', 25, 'Edificio A - Piso 2', 'LAB-ISTPET-002', 'activo', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(3, 'Laboratorio de Redes', 28, 'Edificio B - Piso 1', 'LAB-ISTPET-003', 'activo', '2026-01-04 03:08:09', '2026-01-04 03:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_03_214501_create_usuarios_table', 1),
(6, '2026_01_03_214505_create_carnets_table', 1),
(7, '2026_01_03_214506_create_administradores_table', 1),
(8, '2026_01_03_214506_create_laboratorios_table', 1),
(9, '2026_01_03_214506_create_profesores_table', 1),
(10, '2026_01_03_214507_create_accesos_table', 1),
(11, '2026_01_10_fix_usuarios_documento', 2),
(12, '2026_01_10_000001_add_fotos_carreras_solicitudes', 3),
(13, '2026_01_10_000001_add_fotos_carreras_solicitudes', 4),
(14, '2026_01_11_000001_create_laboratorios_and_update_accesos', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE IF NOT EXISTS `profesores` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profesores_cedula_unique` (`cedula`),
  UNIQUE KEY `profesores_correo_unique` (`correo`),
  KEY `profesores_cedula_index` (`cedula`),
  KEY `profesores_correo_index` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombres`, `apellidos`, `cedula`, `correo`, `celular`, `estado`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Carlos', 'Ramírez', '1711223344', 'carlos.ramirez@istpet.edu.ec', '0998877665', 'activo', '$2y$10$OG.VQENEKJXzanA82YV/ku8i7Hbe.PkDY4nQtXjYGm.OHvQUGuzXm', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(2, 'María', 'González', '1722334455', 'maria.gonzalez@istpet.edu.ec', '0987766554', 'activo', '$2y$10$NLXzP5a.gWO4pMuv75YDpug8D3LaiVBGI7lSfkjntGQF0gJOcdQTW', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(3, 'Juan', 'Morales', '1733445566', 'juan.morales@istpet.edu.ec', '0976655443', 'activo', '$2y$10$LphOjFHmaYOT0NDW50lGV.sJITPSg8LsSuLotSBBD0r5Wohz2tGsq', '2026-01-04 03:08:09', '2026-01-04 03:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_password`
--

CREATE TABLE IF NOT EXISTS `solicitudes_password` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `estado` enum('pendiente','atendida','rechazada') NOT NULL DEFAULT 'pendiente',
  `atendida_por_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notas_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_estado_fecha` (`estado`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitudes_password`
--

INSERT INTO `solicitudes_password` (`id`, `usuario_id`, `tipo_usuario`, `documento`, `correo`, `estado`, `atendida_por_admin_id`, `notas_admin`, `created_at`, `updated_at`) VALUES
(2, 12, 'estudiante', '1726429283', 'kevin.huilca@istpet.edu.ec', 'atendida', 1, 'Contraseña temporal generada: ISTPET9283', '2026-01-11 23:16:16', '2026-01-11 23:16:33'),
(3, 12, 'estudiante', '1726429283', 'kevin.huilca@istpet.edu.ec', 'atendida', 1, 'Contraseña temporal generada: ISTPET9283', '2026-01-15 13:48:39', '2026-01-15 13:49:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `tipo_documento` enum('cedula','pasaporte','ruc') NOT NULL DEFAULT 'cedula',
  `correo_institucional` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `ciclo_nivel` varchar(20) NOT NULL,
  `carrera` varchar(200) DEFAULT NULL,
  `tipo_usuario` enum('estudiante','graduado') NOT NULL DEFAULT 'estudiante',
  `nacionalidad` varchar(50) NOT NULL DEFAULT 'Ecuatoriana',
  `fecha_registro` timestamp NULL DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo','bloqueado') NOT NULL DEFAULT 'activo',
  `password` varchar(255) NOT NULL,
  `password_temporal` tinyint(1) DEFAULT 0,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expira` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_cedula_unique` (`cedula`),
  UNIQUE KEY `usuarios_correo_institucional_unique` (`correo_institucional`),
  KEY `usuarios_cedula_index` (`cedula`),
  KEY `usuarios_correo_institucional_index` (`correo_institucional`),
  KEY `usuarios_estado_index` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `cedula`, `tipo_documento`, `correo_institucional`, `celular`, `ciclo_nivel`, `carrera`, `tipo_usuario`, `nacionalidad`, `fecha_registro`, `foto_url`, `estado`, `password`, `password_temporal`, `reset_token`, `reset_token_expira`, `created_at`, `updated_at`) VALUES
(12, 'kevin Gabriel', 'Huilca Campaña', '1726429283', 'cedula', 'kevin.huilca@istpet.edu.ec', '0962931602', 'TERCER NIVEL', 'Desarrollo de Software', 'estudiante', 'Ecuatoriana', '2026-01-11 23:03:08', 'fotos/estudiante_1768172588_69642c2c1b2e0.jpg', 'activo', '$2y$10$8xq5hxnjdTclcH03HxLiNOluUehL.dPDxMRTp9ZkI1yKD5KYKFFc.', 0, NULL, NULL, '2026-01-11 23:03:08', '2026-01-16 01:41:37'),
(13, 'Lucho', 'kopjihugcfjbhklñ', 'BC713307', 'pasaporte', 'karla@gmail.com', '0962931602', 'TERCER NIVEL', 'Desarrollo de Software', 'estudiante', 'Ecuatoriana', '2026-01-15 13:47:20', 'fotos/estudiante_1768484838_6968efe69d14b.jpeg', 'activo', '$2y$10$CRqSYXLgKUYRxWTfmdApweVsozIM2vXuL9eU.AI5jUVUQL/VjBrm6', 0, NULL, NULL, '2026-01-15 13:47:20', '2026-01-15 13:47:20');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accesos_ibfk_2` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accesos_ibfk_3` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carnets`
--
ALTER TABLE `carnets`
  ADD CONSTRAINT `carnets_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes_password`
--
ALTER TABLE `solicitudes_password`
  ADD CONSTRAINT `solicitudes_password_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
