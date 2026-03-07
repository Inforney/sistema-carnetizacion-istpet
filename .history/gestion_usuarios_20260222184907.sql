-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-02-2026 a las 00:08:20
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
-- Base de datos: `gestion_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_12_034556_add_extra_fields_to_users_table', 1);

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
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('VcDX0gAyIDMektz3qObikVKe3vLESyzqVEPp5R61', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 OPR/126.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWG1zN01TTUlOUHpPSlpmbmRuUHVtMjlId28xcEhnTnZXeE43YjJ4UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VycyI7czo1OiJyb3V0ZSI7czoxMToidXNlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1770069327);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `address`, `birth_date`, `status`) VALUES
(1, 'Kevin Gabriel Huilca', 'kevin.huilca@istpet.edu.ec', NULL, '$2y$12$KlCOIz2ZkahipQj2zFusDug4qldv/VqUWVeCRqHvo5zlwquysvHCu', NULL, '2026-02-03 02:55:26', '2026-02-03 02:55:26', '+593 962 931 602', 'Caupicho', '2000-11-28', 'active'),
(2, 'Deondre Wolff', 'rupton@example.com', '2026-02-14 22:40:08', '$2y$12$jQDolticiT/dYeVkAGSeL.hd3ehG.gdnfrtzurYPkyGwblLvy5dW.', '0Ilj68AjjS', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 90 917 9075', '58791 Hegmann Station\nKaileestad, KY 94304-0730', '1968-08-29', 'inactive'),
(3, 'Barton Considine II', 'alessia.jones@example.net', '2026-02-14 22:40:09', '$2y$12$NShVJOmOOhSjrZCnzFQVB.eGfk4if4tKmLLu4s2Wh8H2Wv3Vghunq', 'qRdIK0VyRB', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 34 697 0773', '50184 Lesch Vista Apt. 793\nNorth Laverneland, CO 12691-6732', '2007-06-26', 'active'),
(4, 'Nakia Williamson DDS', 'keshaun64@example.org', '2026-02-14 22:40:09', '$2y$12$nAjy3f6bWtGAahcU2YFzfedsR3rdj4Ktr1uf8CQvXh1BKCpBr9o9m', '9QqpmgOrRa', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 15 377 8688', '6466 Hermiston Lights Apt. 513\nInesport, SD 78890', '1986-01-15', 'active'),
(5, 'Tyshawn Wuckert', 'eleanore.ondricka@example.org', '2026-02-14 22:40:09', '$2y$12$fkM4Zr5ijZRIkzc4LWTBoOIKiwEiNvNgricAZhKG4KiN05JwLnKOu', '4ZciNghXVq', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 21 984 9958', '25486 Jeramie Prairie\nWest Cooper, CA 11609', '1966-10-11', 'inactive'),
(6, 'Tremayne Stroman PhD', 'jimmy.wisozk@example.org', '2026-02-14 22:40:10', '$2y$12$9y8ryQZvBAL5XMA1UVfcD.IGwsYjPTGniH77fijSivkYAqjJWI9Uq', 'CGNG4QGXmH', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 01 416 1942', '43742 Jaeden Expressway Apt. 282\nNew Averystad, SD 92578', '1981-07-20', 'active'),
(7, 'Dr. Retta Raynor', 'francisco29@example.org', '2026-02-14 22:40:10', '$2y$12$.nCBdyN3yYDg3.FnqdkyJu1PcCg0fWmU7VokM3jQJTXCrE2ZT.ClG', 'IapKb7LDTk', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 50 164 5927', '3682 Emmy Ways Suite 657\nMurazikburgh, VA 14189', '1983-10-31', 'active'),
(8, 'Ms. Marjory Veum', 'gregorio53@example.com', '2026-02-14 22:40:10', '$2y$12$D0E50ttjKwfZTLxY7orCeO6SyOs65bwUbpGOl.hdieUxoQKd626D2', '2S4p858meE', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 67 081 0960', '117 Tyreek Parkways Suite 647\nPort Elvashire, VT 80144-1294', '1984-10-30', 'active'),
(9, 'Patrick Cassin II', 'vandervort.rene@example.org', '2026-02-14 22:40:10', '$2y$12$KpYioxoEERb9eMvfH5ZtyewB4He9Di3Fcy4Ce5H7Pa4cVlrJfwaoa', 'RpIeumpRqo', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 33 739 7980', '399 Dicki Estates\nElishatown, DE 25032', '1971-10-05', 'inactive'),
(10, 'Danyka Johnston', 'madge21@example.org', '2026-02-14 22:40:11', '$2y$12$rSLeyfCtTCnGeM9ZLClcROtbTwJwYlOceAbP5l1j5zEa4stQKZQXq', 'XemghMJgvj', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 07 974 8710', '15544 Charlie Ford\nNew Makaylaberg, NM 55353', '1973-01-09', 'active'),
(11, 'Arnold Schamberger', 'kerluke.arlie@example.net', '2026-02-14 22:40:11', '$2y$12$92PphsF7ES7XpTdiJXraV.ZdFhZmnJqzAbFOjWLHZm9JoLZ3PBomy', 't8MsccrwLb', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 21 221 6046', '417 Ledner Union\nRyderview, NJ 41209', '2003-03-19', 'active'),
(12, 'Ms. Anastasia Funk Jr.', 'skye00@example.net', '2026-02-14 22:40:11', '$2y$12$8EEuNLxDZhghZLm8MT7X5eJknRkxc08rD/oJzlgSa4AWNC1c8.rYe', 'HrpXYvAI85', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 94 548 6887', '185 Jacobs Circles Suite 477\nRevaview, ND 89116', '1968-08-22', 'active'),
(13, 'Zackary Oberbrunner I', 'gjakubowski@example.org', '2026-02-14 22:40:12', '$2y$12$svksRfdn0/ihPhO.uEuGQ.fQrwLMbeb7QnnM4V7PgmZgdiEWM/5tS', '2TbRgMqFRY', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 38 297 2781', '95025 Estrella Extensions Apt. 487\nCarolfort, KY 76149-8078', '1977-07-11', 'inactive'),
(14, 'Prof. Karolann Heathcote PhD', 'kiley.gulgowski@example.com', '2026-02-14 22:40:12', '$2y$12$HTEf194GFlVL2B3MLXqB1.BFKFzkxhONk0yi7ZkUQ2KPrnqjE9nMC', 'ZrFiQapayA', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 45 310 7234', '2062 Quitzon Ford\nWest Edmondtown, MO 04065', '1972-05-12', 'active'),
(15, 'Natasha Cummings', 'cali24@example.org', '2026-02-14 22:40:12', '$2y$12$kkV/9q.i.DxYv9QrMN8JnuIlEftMdOh0XOeFRaLDb/y7eCHGKr1ym', 'Sf1MsDf1WL', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 74 150 5190', '526 Wilma Pike Suite 327\nEast Troy, KY 63634-1923', '1992-10-05', 'inactive'),
(16, 'Matilda Quigley PhD', 'cwolff@example.com', '2026-02-14 22:40:13', '$2y$12$4In0zn0NMUr6JH9pquW/6O.IjIZffqgeYmVca42oeJz82Or1EfFky', 'jeoeVJYPDa', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 53 715 3187', '540 Rossie Valleys\nMitchellport, IN 32882', '2004-11-01', 'inactive'),
(17, 'Gretchen Huel', 'margret.bosco@example.org', '2026-02-14 22:40:13', '$2y$12$Gv36KEH.zn6fLzVH0rjfs.TO06Mkm5OpEzlVBxcT0CcvX19H7N58W', '9Y5DvjpgqK', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 05 577 1060', '885 Yost River Suite 693\nNew Reta, UT 06113-7561', '1983-11-24', 'inactive'),
(18, 'Lurline Ward', 'bernhard.jaycee@example.org', '2026-02-14 22:40:14', '$2y$12$dZWXhV/k1vVKGhmrNNijyuc4d1teOMBD.UEr0Xsu/ynFwmvqy69hm', 'ky8MtAj6IK', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 16 604 0513', '828 Jailyn Mountain Apt. 941\nAryannafurt, WI 05716', '1990-12-18', 'inactive'),
(19, 'Khalil McDermott', 'bernhard.alek@example.org', '2026-02-14 22:40:14', '$2y$12$55GVrD4ZHLfD/BjO1cw4tuPMRiC.nTS9/634Ik4E8I4RwcdzUcvGa', 'rsFGEY2w5w', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 56 284 3071', '44834 Price Rest\nSmithtown, MN 80327-0061', '1998-01-22', 'active'),
(20, 'Garett Hermiston', 'xjohns@example.com', '2026-02-14 22:40:14', '$2y$12$Pbf0XcNpEc.CO4ntbgAvkeX5eTvxv5/Ygj0vAR1av.KAD4NrgheLm', '9r4ukQ3f3q', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 42 418 7440', '9772 Karlie Walks Suite 262\nJamelshire, SC 87398-3022', '1963-03-09', 'inactive'),
(21, 'Ernesto Larson IV', 'qpurdy@example.org', '2026-02-14 22:40:14', '$2y$12$l2lOjPuOy28BaIK3M9cqV.I0lbLwMI4RNj9sKiJUdD0JC2oBQfR2i', 'K7R85E17G5', '2026-02-14 22:40:15', '2026-02-14 22:40:15', '+593 22 760 9144', '596 Murray Shore\nVandervortview, IL 83917-5790', '1964-07-31', 'inactive'),
(22, 'Cathy Ferry DDS', 'jaleel.russel@example.net', '2026-02-15 05:26:07', '$2y$12$zXtsd91lXUbJXoJZGMIDV.UtEb12vJGT.Ju./BXQ1hE1MPuuijvr6', 'ov8E22gS8C', '2026-02-15 05:26:19', '2026-02-15 05:26:19', '+593 21 922 8043', '59043 Stiedemann Skyway Apt. 337\nHaneburgh, SD 19385', '1983-07-10', 'inactive'),
(23, 'Garnet McKenzie', 'jenkins.yadira@example.com', '2026-02-15 05:26:08', '$2y$12$Z0nb1/taQK6b09QEL3aOCeUmDPszf7GZT6vpDliKg2j6osWygfdY.', 'diUbTieZdo', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 79 987 8920', '61407 Elmer Trace Apt. 300\nMoorefurt, PA 40212-9814', '1992-04-06', 'active'),
(24, 'Anastasia Powlowski II', 'xhomenick@example.org', '2026-02-15 05:26:09', '$2y$12$fFDOPwt1EGP9bsW/eq/P.OKlcZtO01jWlGLbuBCLiP6uxJtM5MwrW', '3TyRw9RbuG', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 11 176 5502', '657 Elroy Island\nNorth Kariannemouth, CO 37172', '1988-02-15', 'active'),
(25, 'Melissa Hintz III', 'frohan@example.net', '2026-02-15 05:26:10', '$2y$12$bB0aSZfRAluYtJoIxsGxZOUGMNfcZFV9h74hxDs7Y6yCwmSXXSch.', 'KQXxNEaxmH', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 29 649 1414', '88903 Margret Point Apt. 996\nWalkerside, DC 16828-8794', '1971-01-02', 'active'),
(26, 'Jayne Littel', 'demard@example.com', '2026-02-15 05:26:10', '$2y$12$hAr40WLViqdHgfBHwHt/V.lLe/QZHyyJfAUdJLjdDJAMyruUU.hum', 'msRO4dVfns', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 07 834 5155', '334 Powlowski Lodge\nTarafort, NY 85067-4594', '1971-10-04', 'active'),
(27, 'Dr. Hoyt Kunde PhD', 'winston.kshlerin@example.com', '2026-02-15 05:26:11', '$2y$12$IDSK/Ct1R43nMyCmny79Wus9OCbImHFro0B2d/./mDw0iv0AN8Rh6', 'FF7A6MhT1v', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 77 135 5379', '7749 Sydnie Crest Suite 147\nWest Alden, MD 51599-8761', '2001-05-31', 'inactive'),
(28, 'Prof. Brendon Deckow', 'vroberts@example.com', '2026-02-15 05:26:12', '$2y$12$a8ikjZ96gfTPaMe8attdXeccao/FeNtfMCC4zl9yoJOGKbdGyf3q2', 'Y8Y6My0JfA', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 88 356 4874', '656 Nikolaus Port\nLake Brisa, TN 40407-9253', '1987-01-28', 'active'),
(29, 'Prof. Dalton Konopelski II', 'dskiles@example.net', '2026-02-15 05:26:12', '$2y$12$EEhoT4AGn9q7WvUejuSDROtDTHOAfNPjYARxj.S0jEuDHdy0d/cnK', 'L8yVU82A9K', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 31 368 2919', '4888 Morissette Fall\nPort Serenityfurt, NM 45465-8668', '1963-07-24', 'inactive'),
(30, 'Reva Rohan', 'salvador.schmitt@example.net', '2026-02-15 05:26:13', '$2y$12$Vs9Nh5/jcD9vREjcj2yZIumgKJH8QYiU7evWGAceo3frQKqOHNHq6', 'Hl0n5UeiDu', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 14 525 1166', '41820 Zulauf Road Apt. 539\nDuBuqueland, NJ 80645-8015', '1980-02-27', 'inactive'),
(31, 'Aniyah Mills V', 'connelly.carlo@example.com', '2026-02-15 05:26:14', '$2y$12$Yrc2oa0n9ue0LU7o6IXrZOj690I7pVmW05PJpBO3RqS6Pi4um9tBm', 'D5MIhS7cHq', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 12 010 9141', '953 Johnpaul Forges\nNorth Walker, ID 51556-8156', '1978-08-13', 'active'),
(32, 'Vernie Emmerich', 'orval.pacocha@example.net', '2026-02-15 05:26:14', '$2y$12$Fj6dQiW.5fVcJGb7Ew9ezO5PCeklKyB8SI4FYvgcqN5YU83WtcBBO', 'PRl5X5kUAy', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 91 973 5332', '18553 Monahan Isle Suite 046\nHartmannchester, NJ 55282-8931', '1991-08-24', 'active'),
(33, 'Alejandra Morar', 'cullen.bode@example.net', '2026-02-15 05:26:15', '$2y$12$NPI7drxS9jdJFA6n99gAp.fQzEfOaLFcPLXv1cA089OOsy7z5WPe6', '9yMLNzoXoo', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 43 609 8782', '86675 Alexys Lights\nLake Hans, NV 36474', '1993-05-06', 'inactive'),
(34, 'General Beahan', 'megane58@example.com', '2026-02-15 05:26:16', '$2y$12$Qtbmvi8NMhLukIiMt9ES.OJP8Jru8Z/wrMTJ5I/F6SCeabUNHcODm', 's4aewRK65v', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 17 334 5293', '76186 Adaline Grove\nNew Aniyaland, WI 23427', '2002-08-21', 'active'),
(35, 'Winona Fadel Jr.', 'zbogisich@example.net', '2026-02-15 05:26:16', '$2y$12$3bFy0srJlVdYa7MKjK6bU.N1bGRFWDWxAzkPIz1YYnJJvthMVy8be', 'WF9Sn8bMIE', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 69 986 8744', '190 Kiehn Creek\nNorth Doris, DE 58396-4805', '1974-10-12', 'active'),
(36, 'Dr. Judson Bartell DVM', 'erna.hodkiewicz@example.com', '2026-02-15 05:26:17', '$2y$12$QbWU48tYhSHegLDjAh6JhuT1DdwuhELluENyUl5U/uopEbMFbairO', 'LuTaGdAmAO', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 09 124 3646', '262 Hoppe Dam Apt. 889\nMikefort, WY 41214-6071', '1984-02-19', 'active'),
(37, 'Beryl Bahringer DVM', 'tjast@example.org', '2026-02-15 05:26:17', '$2y$12$.lMSsacPGjh.khFa.ZBOOufGnqM/S1DZFI0badRmbkuPj5/SJpRhm', 'pA4zWyvJYG', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 81 436 3579', '2197 Robyn Mount Apt. 966\nWest Marioborough, RI 83862', '1962-05-10', 'active'),
(38, 'Prof. Lora Mosciski', 'merle.mckenzie@example.net', '2026-02-15 05:26:18', '$2y$12$L2XvhDCyA/WJcma2cnsg7OV5QKp69Zqa9MZjOiF1np5v3kwxq22oe', 'RFSBav5YAH', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 86 696 9404', '485 Zboncak Fields Suite 282\nKevinmouth, WI 79706-0340', '1969-08-03', 'inactive'),
(39, 'Nicklaus Kessler', 'edmund88@example.com', '2026-02-15 05:26:18', '$2y$12$HhgbRylxfXVekTcdVrcC9.FXzGXbw2G3CGIss3t/.gsZhPrAJ.mj6', 'CRhObpAzLV', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 79 871 6567', '32773 Napoleon Burg\nSouth Reggieshire, WV 74071', '1986-12-03', 'inactive'),
(40, 'Dustin Fay', 'doug.becker@example.org', '2026-02-15 05:26:18', '$2y$12$rhLV3qXBDClctVsTfLbZqeYUDgEbJODE9kAatejOmnoPkSBLUg3Pq', 'UWlQgn0EAL', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 57 518 7928', '85846 Padberg Manor\nEast Zaria, WY 51318-6206', '1982-11-06', 'active'),
(41, 'Dandre Quigley', 'gianni.kuphal@example.net', '2026-02-15 05:26:19', '$2y$12$lKdsCbwyAVhPXzDbjR5lp.RW1mQX1.CmS5qxC7KhqIOodxWUpxMA2', 'Zh4ZswkTQE', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 75 681 8719', '461 Malcolm Forks Apt. 381\nNorth Shanieview, LA 46813', '1979-02-23', 'inactive'),
(42, 'Kevin Huilca', 'kevin@istpet.edu.ec', '2026-02-15 05:26:20', '$2y$12$78rEwQvw87vv.tammUl0F.RHOfBUfUueOs6/7swl4YLEs70QAjHfy', '2g74roNrvG', '2026-02-15 05:26:20', '2026-02-15 05:26:20', '+593 962 931 602', 'Quito, Pichincha, Ecuador', '2000-11-28', 'active'),
(43, 'Lazaro Fisher', 'johnston.etha@example.org', '2026-02-15 05:32:13', '$2y$12$C2jZtPN0T0IutiRLnbYBt.DxbpALUhcgi9EKBMuaUgMrV3lZJH6fe', '4sG7SoNUvn', '2026-02-15 05:32:29', '2026-02-15 05:32:29', '+593 32 718 0066', '582 Frami Street Apt. 282\nGrimesview, AK 81617', '1991-09-26', 'inactive'),
(44, 'Larissa Glover', 'cecilia93@example.net', '2026-02-15 05:32:16', '$2y$12$eKU5oHpfCAPlEqKphXp.9eDZ5/QxPt1LUlSERNaC7RJwK90wPniRe', 'YpjYrSzESP', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 56 090 9340', '3121 Smitham Corner Apt. 667\nNew Vivienneton, TN 25087-8639', '2005-04-13', 'inactive'),
(45, 'Murl Kautzer', 'tbaumbach@example.org', '2026-02-15 05:32:16', '$2y$12$i6xZQJmLebWfYAThCLAKBevJ4Imc5qNlCBRUF9ZXixng96Q5MpnWm', 'W2k4Yj8jP4', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 96 418 8611', '29477 Rowe Extensions Suite 834\nHeaneyburgh, NV 34286-4394', '2004-10-26', 'inactive'),
(46, 'Magali Runte', 'berge.nellie@example.com', '2026-02-15 05:32:17', '$2y$12$abF.TVAQJq7Hh1o5Ux2/L.0/06snB9ZeWLC0HKMLhf0BELhMBFf/a', 'y0nniCy7ow', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 42 020 6489', '596 Lauriane Prairie\nEast Brooklyn, AL 07841', '2003-01-25', 'inactive'),
(47, 'Dr. Jeffery Weimann V', 'waelchi.libby@example.org', '2026-02-15 05:32:18', '$2y$12$Z7MiEJaDhGVEviv8bYgUgOloS5htfxOIsc93NnymO7xpD5PlrfRq2', 'UmOCgP1ARt', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 28 356 7543', '122 Littel Vista Apt. 088\nMcClureview, NM 15496-5330', '2003-08-08', 'active'),
(48, 'Raheem Trantow', 'kkshlerin@example.org', '2026-02-15 05:32:19', '$2y$12$1pNLBCwuuPDdCPj99OpaGe/HCUXOGehPFRo5rVN6Fc74XexOCvlX.', 'nKHMa7WQ1E', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 13 426 4943', '4296 Taylor Drive\nSouth Alessandrofurt, PA 63840', '1994-02-28', 'inactive'),
(49, 'Rita Romaguera', 'ybeahan@example.org', '2026-02-15 05:32:19', '$2y$12$Quraelp6TRGfzIXu2WoRiuSlBJ4z1JhYjlAbpG5Nz1f4bSYBGZOmi', 'Wp8rG0gNgz', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 05 221 8756', '2415 Mona Squares Apt. 499\nVestaport, CT 00896-9563', '1974-07-26', 'inactive'),
(50, 'Gladys Christiansen', 'pfannerstill.aletha@example.com', '2026-02-15 05:32:20', '$2y$12$WgpQHp5znuDj8.q//AnXBe8ldfi/ssITm9qSp2hFb3toL6l9EdZrC', 'rG8gsVf9Mo', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 14 982 6942', '5695 Hane Fork Apt. 704\nLake Arielfort, DC 58891-0539', '1997-02-08', 'inactive'),
(51, 'Brielle Mueller MD', 'heidenreich.darlene@example.net', '2026-02-15 05:32:21', '$2y$12$6PdB4P2zj70PAGp5yJMUL.FTLLN.OmF1mPUludueiImq6JQ4vO1TC', '2fOvfeOw6b', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 98 441 3214', '35845 Thiel Freeway\nNorth Soledad, FL 28486', '2000-12-11', 'inactive'),
(52, 'Bailee Schmitt', 'braxton.beahan@example.org', '2026-02-15 05:32:21', '$2y$12$aaIbaF5Id10/wIffsRfmau/Lni8Y.AapSTt8bTT6kQsd1nTeNzRa2', 'ecQsoCManw', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 75 104 7772', '116 Ullrich Ports Suite 320\nSouth Alethaborough, GA 01954', '1974-05-26', 'inactive'),
(53, 'Dr. Karelle Hane', 'kovacek.bonita@example.com', '2026-02-15 05:32:22', '$2y$12$pKdEHnEh2ORpGqPopeY/aOwYzg/RVTWQgMjdeoxubLoTAZ1h71egK', 'U6YfuayBU9', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 45 301 0960', '5686 Cordie Centers Suite 358\nNew Brandifort, AL 72712', '1992-10-02', 'inactive'),
(54, 'Amy Lowe', 'rosie.lubowitz@example.net', '2026-02-15 05:32:23', '$2y$12$KTwfFEKa1uNLwz2qs3OpqOG7Mee9Fox5R0JH16qKvFwdCpUwFmm9S', '5JNRt7DFIm', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 45 145 8696', '465 Imelda Village\nWest Watson, NE 32259-6193', '1970-07-01', 'inactive'),
(55, 'Collin Raynor V', 'norval.lehner@example.org', '2026-02-15 05:32:23', '$2y$12$LtniTNS.hwsdOjO5eMr1VuehgynubO5D8N09zyzlZHq4bbSFsW.hq', 'Yh1OpvssbK', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 75 467 3113', '73053 Blanda Field Suite 388\nCristburgh, IN 26598-6362', '2000-03-19', 'active'),
(56, 'Carmela Balistreri', 'ferry.lou@example.net', '2026-02-15 05:32:24', '$2y$12$enbsRtxl91r7FJINkxhFAujlbRRXSkaam3hgY1GWvmLV9XXbO2tzC', 'vpSJMXDJL5', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 10 378 9243', '844 Gulgowski Knoll Suite 083\nPadbergmouth, DE 92060-7483', '2007-08-30', 'active'),
(57, 'Prince McCullough', 'vesta.kovacek@example.org', '2026-02-15 05:32:25', '$2y$12$sCz0fTZdDlxzo.qHg7qBCuSZjV/4.tWOmf0Mw/xS2my.UPVyrrNvm', 'OYb8TEaxYl', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 54 623 3044', '9024 Lynn Inlet\nSouth Olin, AZ 27472-8845', '1995-01-07', 'active'),
(58, 'Prof. Troy Schulist Sr.', 'henderson.bradtke@example.net', '2026-02-15 05:32:26', '$2y$12$loH8NmhN3TSEq.o9lJ2wJuF87LV39TJd6wHfjvP1R370j/EfLLysi', 'lnM7KNf0Xc', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 04 563 6633', '6506 Torrey Crossing\nNorth Olenbury, NM 73777-6658', '2007-03-25', 'active'),
(59, 'Alverta Gottlieb', 'trey61@example.com', '2026-02-15 05:32:26', '$2y$12$4XDtf44CKY6TJcAyuqRAZ.kd8XPgRgt6vXD2nf6b303Rz8pUO3JdG', '0LTTE3pgMt', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 67 492 5195', '57140 Klocko Alley Suite 202\nJerdeport, TX 26452-4442', '1971-10-03', 'active'),
(60, 'Ryan Cummerata', 'uwisoky@example.org', '2026-02-15 05:32:27', '$2y$12$Wj7n6JBHl/kOq3tZOcl/GOgyR.9amAMNsmlAPaYCLDYC5ovFGfDai', 'wcsVAsVrJi', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 29 555 9940', '156 Jeanette Lakes Apt. 270\nHalieview, IN 92935', '1964-08-30', 'active'),
(61, 'Mckenna Gaylord', 'ncormier@example.org', '2026-02-15 05:32:28', '$2y$12$JgJMrKz6BAQKD3ozE/DR3ejQbt2X.JOzObOt5b9iegZeXi4iZ6ogy', 'vBPYz3cKha', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 79 809 2925', '371 Krajcik Brook Apt. 259\nKrisborough, MS 69925', '1987-11-20', 'active'),
(62, 'Shyanne Schroeder', 'elliott29@example.net', '2026-02-15 05:32:29', '$2y$12$HJdcsqNc3lDCZuqg0vjOH.PyqZRJE1ZZaviACpQ3tZqQeB.e/MjDK', 'UgL0QI5Ffo', '2026-02-15 05:32:30', '2026-02-15 05:32:30', '+593 73 559 5215', '473 Metz Walk Suite 147\nAnsleybury, AZ 01134', '2006-08-16', 'active');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
