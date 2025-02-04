-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-02-2025 a las 07:08:09
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ricplact4`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Bandeja'),
(1, 'Bolsas'),
(5, 'Botellas'),
(4, 'Cubiertos'),
(33, 'hola'),
(3, 'Platos'),
(6, 'Rollos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `lastname` varchar(60) DEFAULT NULL,
  `gender` enum('Masculino','Femenino') DEFAULT NULL,
  `dni` varchar(8) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `name`, `lastname`, `gender`, `dni`, `address`, `email`, `status`) VALUES
(1, 'Usuario01', 'asd', 'Masculino', '00000001', 'Ventas', 'ejemplo@gmail.com', 'Inactivo'),
(2, 'Gente', 'b', 'Femenino', '00000000', 'av.Lima', 'usuario02@gmail.com', 'Activo'),
(3, 'holsa', 'c', 'Masculino', '00000000', 'av', 'hola@gmail.com', 'Activo'),
(11, 'Aldair', 'Dominguez', 'Femenino', '00000000', 'Av.Canto Bello MZ Q LT 21 Asent.H San Fernando', 'aldair30d@gmail.com', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(1, '500-co2-1.jpg', 'image/jpeg'),
(2, 'Bandejas.jpeg', 'image/jpeg'),
(3, 'IMG-20230704-WA0001.jpg', 'image/jpeg'),
(4, 'IMG-20230704-WA0002.jpg', 'image/jpeg'),
(5, 'IMG-20230704-WA0003.jpg', 'image/jpeg'),
(6, 'IMG-20230704-WA0004.jpg', 'image/jpeg'),
(7, 'IMG-20230704-WA0005.jpg', 'image/jpeg'),
(8, 'IMG-20230704-WA0006.jpg', 'image/jpeg'),
(9, 'IMG-20230704-WA0007.jpg', 'image/jpeg'),
(10, 'IMG-20230704-WA0008.jpg', 'image/jpeg'),
(11, 'IMG-20230704-WA0009.jpg', 'image/jpeg'),
(12, 'IMG-20230704-WA0010.jpg', 'image/jpeg'),
(13, 'IMG-20230704-WA0011.jpg', 'image/jpeg'),
(14, 'IMG-20230704-WA0012.jpg', 'image/jpeg'),
(15, 'IMG-20230704-WA0013.jpg', 'image/jpeg'),
(16, 'IMG-20230704-WA0014.jpg', 'image/jpeg'),
(17, 'IMG-20230704-WA0015.jpg', 'image/jpeg'),
(18, 'IMG-20230704-WA0016.jpg', 'image/jpeg'),
(19, 'IMG-20230704-WA0017.jpg', 'image/jpeg'),
(20, 'IMG-20230704-WA0018.jpg', 'image/jpeg'),
(21, 'IMG-20230704-WA0019.jpg', 'image/jpeg'),
(22, 'IMG-20230704-WA0020.jpg', 'image/jpeg'),
(32, 'descarga.jpg', 'image/jpeg'),
(33, 'PAQUETE DE CUCHARAS DE PLASTICO.png', 'image/png'),
(34, 'PAQUETE DE PLATOS X20 UND.png', 'image/png'),
(38, '3.PNG', 'image/png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(10) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `unidad_venta` varchar(20) DEFAULT 'paquetes',
  `supplier_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `unidad_venta`, `supplier_id`) VALUES
(3, 'Bandejas de Plástico', NULL, '1', 10.00, 10.00, 1, 2, '2023-07-04 09:48:52', 'paquetes', 3),
(4, 'Bolsa para kilo 5x10', NULL, '192', 250.00, 7.00, 1, 3, '2023-07-07 18:45:10', 'paquetes', NULL),
(5, 'Bolsa 1/2K 7x10', NULL, '857', 170.00, 7.00, 1, 6, '2023-07-07 18:47:19', 'paquetes', NULL),
(6, 'Tucan 12x16 bolsa con  aza', NULL, '149', 250.00, 3.00, 1, 5, '2023-07-07 18:48:14', 'paquetes', NULL),
(7, 'Bolsa Leon con aza 12x16', NULL, '103', 350.00, 3.00, 1, 6, '2023-07-07 18:48:56', 'paquetes', NULL),
(8, 'Bolsa Rollo Tucan  3X8', NULL, '135', 450.00, 7.00, 1, 7, '2023-07-07 18:55:10', 'paquetes', NULL),
(9, 'Bolsa alfa co aza 12x16', NULL, '168', 230.00, 3.00, 1, 10, '2023-07-07 18:56:53', 'paquetes', NULL),
(10, 'Bolsa rollo Tucan 7x10', NULL, '178', 650.00, 11.00, 1, 12, '2023-07-07 18:58:10', 'paquetes', NULL),
(11, 'Bolsa Rollo Tucan 14x20', NULL, '360', 850.00, 19.00, 1, 13, '2023-07-07 18:58:42', 'paquetes', NULL),
(12, 'Bolsa tucan rollo 8x12', NULL, '175', 960.00, 11.00, 1, 14, '2023-07-07 18:59:49', 'paquetes', NULL),
(13, 'bolsa alfa con aza 16x19', NULL, '369', 750.00, 5.00, 1, 15, '2023-07-07 19:00:27', 'paquetes', NULL),
(21, 'PAQUETE DE CUCHARAS DE PLASTICO X30 Un', NULL, '100', 1.80, 2.30, 4, 33, '2024-11-01 01:25:23', 'paquetes', NULL),
(22, 'PAQUETE DE PLATOS X20 UND', NULL, '199', 3.50, 5.00, 3, 34, '2024-11-01 01:58:09', 'paquetes', NULL),
(24, 'hola', NULL, '122', 123.00, 213.00, 2, 1, '2024-11-07 05:50:25', 'paquetes', NULL),
(27, 'hola1', NULL, '6', 1.00, 1.00, 2, 32, '2024-11-07 06:33:04', 'paquetes', 3),
(30, 'hola2', NULL, '1', 5.00, 5.00, 33, 38, '2024-11-07 08:22:45', 'paquetes', 3),
(34, 'hola345', NULL, '2', 3.00, 3.00, 2, 4, '2024-11-07 14:01:05', 'unidades', NULL),
(37, 'sadfafds', 'hoola', '5', 5.00, 5.00, 6, 0, '2024-11-20 15:41:11', 'unidades', 3),
(38, 'a', 'a', '10', 10.00, 11.00, 2, 1, '2024-12-11 20:25:43', 'unidades', 3),
(39, 'asd', 'asd', '2', 4.00, 4.00, 2, 1, '2024-12-11 22:18:52', 'docenas', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` datetime NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `previous_stock` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`, `client_id`, `previous_stock`, `current_stock`) VALUES
(1, 3, 6, 12.00, '2023-07-04 00:00:00', NULL, 0, 0),
(4, 3, 7, 14.00, '2023-07-04 00:00:00', NULL, 0, 0),
(6, 5, 8, 56.00, '2023-07-07 00:00:00', NULL, 0, 0),
(7, 7, 6, 18.00, '2023-07-07 00:00:00', NULL, 0, 0),
(8, 4, 5, 35.00, '2023-07-07 00:00:00', NULL, 0, 0),
(9, 9, 6, 18.00, '2023-07-07 00:00:00', NULL, 0, 0),
(11, 5, 2, 14.00, '2023-10-29 00:00:00', NULL, 0, 0),
(12, 9, 2, 6.00, '2023-10-29 00:00:00', NULL, 0, 0),
(13, 4, 2, 14.00, '2023-10-30 00:00:00', NULL, 0, 0),
(14, 7, 5, 15.00, '2023-10-30 00:00:00', NULL, 0, 0),
(15, 5, 1, 7.00, '2023-10-31 00:00:00', NULL, 0, 0),
(16, 3, 1, 2.00, '2023-10-31 00:00:00', NULL, 0, 0),
(17, 5, 1, 7.00, '2023-10-31 00:00:00', NULL, 0, 0),
(18, 5, 1, 7.00, '2024-10-24 00:00:00', NULL, 0, 0),
(19, 5, 1, 7.00, '2024-10-29 00:00:00', NULL, 0, 0),
(20, 4, 2, 14.00, '2024-10-30 00:00:00', NULL, 0, 0),
(22, 6, 5, 15.00, '2024-10-30 00:00:00', NULL, 0, 0),
(23, 5, 3, 21.00, '2024-10-31 00:00:00', NULL, 0, 0),
(24, 6, 4, 12.00, '2024-10-31 00:00:00', NULL, 0, 0),
(26, 5, 987, 6909.00, '2024-10-31 00:00:00', NULL, 0, 0),
(27, 13, 0, 5.00, '2024-10-31 00:00:00', NULL, 0, 0),
(28, 4, 1, 7.00, '2024-10-31 00:00:00', NULL, 0, 0),
(31, 21, 100, 230.00, '2024-10-31 00:00:00', NULL, 0, 0),
(32, 21, 100, 230.00, '2024-10-31 00:00:00', NULL, 0, 0),
(33, 4, 2, 14.00, '2024-10-31 00:00:00', NULL, 0, 0),
(34, 4, 1, 7.00, '2024-10-31 00:00:00', NULL, 0, 0),
(35, 5, 1, 7.00, '2024-10-31 00:00:00', NULL, 0, 0),
(36, 3, 1, 2.00, '2024-11-06 00:00:00', NULL, 0, 0),
(41, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(42, 3, 2, 4.00, '2024-11-07 00:00:00', NULL, 0, 0),
(43, 3, 3, 6.00, '2024-11-07 00:00:00', NULL, 0, 0),
(44, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(45, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(46, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(47, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(48, 27, 1, 1.00, '2024-11-07 00:00:00', NULL, 0, 0),
(49, 24, 1, 213.00, '2024-11-07 00:00:00', NULL, 0, 0),
(50, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(52, 3, 1, 2.00, '2024-11-07 00:00:00', 1, 0, 0),
(53, 3, 1, 2.00, '2024-11-07 00:00:00', 1, 0, 0),
(54, 3, 1, 2.00, '2024-11-07 00:00:00', 1, 0, 0),
(55, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(56, 3, 1, 2.00, '2024-11-07 00:00:00', NULL, 0, 0),
(59, 30, 1, 5.00, '2024-11-07 00:00:00', 1, 0, 0),
(60, 5, 3, 21.00, '2024-11-07 00:00:00', 3, 0, 0),
(61, 7, 5, 15.00, '2024-11-07 00:00:00', 3, 0, 0),
(62, 4, 5, 35.00, '2024-11-07 00:00:00', 2, 0, 0),
(63, 6, 1, 3.00, '2024-11-07 00:00:00', 2, 0, 0),
(64, 34, 1, 3.00, '2024-11-07 00:00:00', 2, 0, 0),
(65, 3, 1, 2.00, '2024-11-07 00:00:00', 1, 0, 0),
(66, 6, 1, 3.00, '2024-11-07 00:00:00', 1, 0, 0),
(67, 7, 1, 3.00, '2024-11-07 00:00:00', 1, 0, 0),
(68, 3, 1, 2.00, '2024-11-07 00:00:00', 1, 0, 0),
(69, 30, 1, 5.00, '2024-11-20 00:00:00', 1, 0, 0),
(70, 30, 1, 5.00, '2024-11-20 00:00:00', 1, 0, 0),
(71, 3, 1, 10.00, '2024-11-20 00:00:00', 1, 0, 0),
(72, 3, 1, 10.00, '2024-11-20 00:00:00', 1, 0, 0),
(73, 3, 3, 30.00, '2024-11-20 00:00:00', 1, 0, 0),
(74, 3, 1, 10.00, '2024-11-21 00:00:00', 1, 22, 21),
(75, 3, 1, 10.00, '2024-11-21 00:00:00', 1, 21, 20),
(76, 3, 1, 10.00, '2024-11-21 06:22:55', 1, 20, 19),
(77, 3, 1, 10.00, '2024-11-21 00:27:08', 1, 20, 19),
(78, 3, 1, 10.00, '2024-11-21 22:06:54', 1, 19, 18),
(79, 3, 10, 100.00, '2024-11-21 22:07:38', 1, 18, 8),
(80, 30, 10, 50.00, '2024-11-21 22:14:20', 1, 11, 1),
(81, 3, 1, 10.00, '2024-12-04 13:26:00', 2, 8, 7),
(82, 22, 1, 5.00, '2024-12-11 16:13:57', 2, 200, 199),
(83, 3, 2, 20.00, '2024-12-11 17:08:19', 1, 7, 5),
(84, 5, 80, 560.00, '2024-12-11 17:34:49', 1, 937, 857),
(85, 3, 1, 10.00, '2024-12-12 11:58:37', 1, 5, 4),
(86, 3, 1, 10.00, '2024-12-12 12:03:47', 1, 4, 3),
(87, 3, 1, 10.00, '2024-12-12 14:20:34', 11, 3, 2),
(88, 3, 1, 10.00, '2024-12-12 14:27:51', 11, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_transactions`
--

CREATE TABLE `sale_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `sale_date` datetime NOT NULL,
  `total` decimal(25,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity_added` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `quantity_added`, `previous_stock`, `new_stock`, `timestamp`) VALUES
(1, 3, 4, 21, 25, '2024-11-20 21:46:51'),
(2, 27, 5, 1, 6, '2024-11-20 22:00:28'),
(3, 3, 0, 25, 25, '2024-11-20 22:05:11'),
(4, 30, 2, 0, 2, '2024-11-20 22:20:51'),
(5, 30, 2, 2, 4, '2024-11-20 23:07:25'),
(6, 30, 2, 4, 6, '2024-11-20 23:18:39'),
(7, 3, 2, 20, 22, '2024-11-21 00:00:57'),
(8, 3, 1, 19, 20, '2024-11-21 00:23:39'),
(9, 30, 3, 6, 9, '2024-11-21 20:36:57'),
(10, 30, 2, 9, 11, '2024-11-21 22:12:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `ruc` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `ruc`, `address`, `email`, `phone`, `created_at`, `status`) VALUES
(3, 'a', '10321321312', 'a', 'albertovillacp19+c1@gmail.com', '1123323232', '2024-11-20 09:23:10', 'Inactivo'),
(4, 'sac', '10123213213', 'Av.Canto Bello MZ Q LT 21 Asent.H San Fernando', 'aldair30d@gmail.com', '927418762', '2024-12-11 08:53:38', 'Activo'),
(5, 'sac', '20123213213', 'Av.Canto Bello MZ Q LT 21 Asent.H San Fernando', 'aldair30d@gmail.com', '927418762', '2024-12-11 08:54:10', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `gender` enum('M','F','O') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(11) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `gender`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Default Administradora', '', 'M', 'Admin', '50a450e062d8ff9473d8d0f9bcc696fc5ffd9499', 1, '611cpay1.png', 1, '2025-02-04 07:06:58'),
(2, 'Default Almacenero', '', 'M', 'Almacenero', '50a450e062d8ff9473d8d0f9bcc696fc5ffd9499', 2, 'sfp0i162.png', 1, '2024-12-12 00:14:03'),
(3, 'Default Vendedor', '', 'M', 'Vendedor', '50a450e062d8ff9473d8d0f9bcc696fc5ffd9499', 3, 'b1bf57s33.png', 1, '2024-12-12 00:21:06'),
(28, 'Luis Perez Albujar', '', 'M', 'UCV123', '256537e61f5b2cec38c9105946cc11cd77ff8b56', 3, 'no_image.jpg', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Almacenero', 2, 1),
(3, 'Vendedor', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `fk_supplier_id` (`supplier_id`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_sales_client` (`client_id`);

--
-- Indices de la tabla `sale_transactions`
--
ALTER TABLE `sale_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indices de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indices de la tabla `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `sale_transactions`
--
ALTER TABLE `sale_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Filtros para la tabla `sale_transactions`
--
ALTER TABLE `sale_transactions`
  ADD CONSTRAINT `sale_transactions_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
