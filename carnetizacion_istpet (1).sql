-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2026 a las 00:54:11
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `profesor_valida_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `usuario_id`, `laboratorio_id`, `profesor_id`, `fecha_entrada`, `hora_entrada`, `fecha_salida`, `hora_salida`, `created_at`, `updated_at`, `metodo_registro`, `marcado_ausente`, `nota_ausencia`, `profesor_valida_id`) VALUES
(5, 17, 1, 1, '2026-01-30', '16:50:39', '2026-01-30', '16:54:09', '2026-01-30 21:50:39', '2026-01-30 21:54:09', 'manual_profesor', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `carnets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `codigo_qr` varchar(255) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado` enum('activo','vencido','bloqueado') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carnets`
--

INSERT INTO `carnets` (`id`, `usuario_id`, `codigo_qr`, `fecha_emision`, `fecha_vencimiento`, `estado`, `created_at`, `updated_at`) VALUES
(130, 17, 'ISTPET-2026-1726429283', '2026-01-29', '2030-01-29', 'activo', '2026-01-30 00:40:44', '2026-01-30 00:40:44'),
(133, 20, 'ISTPET-2026-1102369475', '2026-02-05', '2027-02-05', 'activo', '2026-02-05 13:13:40', '2026-02-05 13:13:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('laboratorio','aula_interactiva') NOT NULL DEFAULT 'laboratorio',
  `capacidad` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion` varchar(200) NOT NULL,
  `codigo_qr_lab` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo','disponible','mantenimiento') DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `laboratorios`
--

INSERT INTO `laboratorios` (`id`, `nombre`, `tipo`, `capacidad`, `descripcion`, `ubicacion`, `codigo_qr_lab`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Laboratorio de Computación 1', 'laboratorio', 30, NULL, 'Edificio A - Piso 2', 'LAB-ISTPET-001', 'activo', '2026-01-04 03:08:09', '2026-01-04 03:08:09'),
(3, 'Laboratorio de Redes', 'laboratorio', 28, NULL, 'Edificio B - Piso 1', 'LAB-ISTPET-003', 'activo', '2026-01-04 03:08:09', '2026-01-04 03:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(14, '2026_01_11_000001_create_laboratorios_and_update_accesos', 5),
(15, '2026_01_24_081238_add_tipo_to_laboratorios', 6),
(16, '2025_01_24_083000_add_semestre_to_usuarios', 7),
(17, '2026_01_30_170051_add_additional_fields_to_profesores_table', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `especialidad` varchar(150) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `horario` varchar(255) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombres`, `apellidos`, `cedula`, `correo`, `celular`, `especialidad`, `fecha_ingreso`, `foto_url`, `horario`, `departamento`, `estado`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Kevin', 'Huilca', '1711223344', 'carlos.ramirez@istpet.edu.ec', '0998877665', 'Ingeniera en Sistemas', NULL, 'storage/fotos/profesores/profesor_1711223344.jpeg', NULL, 'Desarrollo de Software', 'activo', '$2y$10$OG.VQENEKJXzanA82YV/ku8i7Hbe.PkDY4nQtXjYGm.OHvQUGuzXm', '2026-01-04 03:08:09', '2026-01-30 22:18:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_password`
--

CREATE TABLE `solicitudes_password` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `estado` enum('pendiente','atendida','rechazada') NOT NULL DEFAULT 'pendiente',
  `atendida_por_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notas_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `tipo_documento` enum('cedula','pasaporte','ruc') NOT NULL DEFAULT 'cedula',
  `correo_institucional` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `ciclo_nivel` varchar(20) NOT NULL,
  `carrera` varchar(200) DEFAULT NULL,
  `semestre` enum('PRIMER NIVEL','SEGUNDO NIVEL','TERCER NIVEL','CUARTO NIVEL','QUINTO NIVEL','SEXTO NIVEL') DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `cedula`, `tipo_documento`, `correo_institucional`, `celular`, `ciclo_nivel`, `carrera`, `semestre`, `tipo_usuario`, `nacionalidad`, `fecha_registro`, `foto_url`, `estado`, `password`, `password_temporal`, `reset_token`, `reset_token_expira`, `created_at`, `updated_at`) VALUES
(17, 'kevin Gabriel', 'Huilca Campaña', '1726429283', 'cedula', 'kevin.huilca@istpet.edu.ec', '0962931602', 'TERCER NIVEL', 'Desarrollo de Software', NULL, 'estudiante', 'Ecuatoriana', '2026-01-30 00:40:44', 'storage/fotos_perfil/foto_1726429283_1769736066.png', 'activo', '$2y$10$whpUrsQk0gzCgjN5SttOEePVp2jqqFp5SljFqFVjddEOLCfAyxs8y', 0, NULL, NULL, '2026-01-30 00:40:44', '2026-01-30 01:21:06'),
(20, 'Juan Carlos', 'Pérez López', '1102369475', 'cedula', 'juan.perez@istpet.edu.ec', '0987654321', 'TERCER NIVEL', 'Desarrollo de Software', NULL, 'estudiante', 'Ecuatoriana', NULL, NULL, 'activo', '$2y$10$zEXyXwXA/UF82Ou.cQby2eFbg9KdESw79Cu9.nFtV4GvYbcfw3rLi', 0, NULL, NULL, '2026-02-05 13:11:35', '2026-02-05 13:11:35');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesor_id` (`profesor_id`),
  ADD KEY `idx_fecha_entrada` (`fecha_entrada`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_laboratorio` (`laboratorio_id`);

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administradores_usuario_unique` (`usuario`),
  ADD UNIQUE KEY `administradores_email_unique` (`email`),
  ADD KEY `administradores_usuario_index` (`usuario`),
  ADD KEY `administradores_email_index` (`email`);

--
-- Indices de la tabla `carnets`
--
ALTER TABLE `carnets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carnets_codigo_qr_unique` (`codigo_qr`),
  ADD KEY `carnets_codigo_qr_index` (`codigo_qr`),
  ADD KEY `carnets_usuario_id_index` (`usuario_id`),
  ADD KEY `carnets_estado_index` (`estado`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laboratorios_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `laboratorios_codigo_qr_lab_unique` (`codigo_qr_lab`),
  ADD KEY `laboratorios_nombre_index` (`nombre`),
  ADD KEY `laboratorios_codigo_qr_lab_index` (`codigo_qr_lab`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profesores_cedula_unique` (`cedula`),
  ADD UNIQUE KEY `profesores_correo_unique` (`correo`),
  ADD KEY `profesores_cedula_index` (`cedula`),
  ADD KEY `profesores_correo_index` (`correo`);

--
-- Indices de la tabla `solicitudes_password`
--
ALTER TABLE `solicitudes_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_estado_fecha` (`estado`,`created_at`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_cedula_unique` (`cedula`),
  ADD UNIQUE KEY `usuarios_correo_institucional_unique` (`correo_institucional`),
  ADD KEY `usuarios_cedula_index` (`cedula`),
  ADD KEY `usuarios_correo_institucional_index` (`correo_institucional`),
  ADD KEY `usuarios_estado_index` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `carnets`
--
ALTER TABLE `carnets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `solicitudes_password`
--
ALTER TABLE `solicitudes_password`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
