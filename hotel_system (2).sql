-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 18:47:05
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hotel_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(5, 'juanjo', '$2y$10$930m5kKi897pDLlgcrKIx.Rvegex8iO.SjZnsdBaL5mdMiEMJn7pu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `room_id`, `date`, `status`, `created_at`) VALUES
(1, 1, 1, '2025-06-18', 'confirmed', '2025-06-12 15:36:16'),
(2, 1, 1, '2025-06-19', 'cancelled', '2025-06-12 15:36:16'),
(3, 1, 1, '2025-06-20', 'cancelled', '2025-06-12 15:36:16'),
(4, 1, 1, '2025-06-21', 'cancelled', '2025-06-12 15:36:16'),
(5, 1, 6, '2025-06-18', 'confirmed', '2025-06-12 16:23:38'),
(6, 1, 6, '2025-06-19', 'confirmed', '2025-06-12 16:23:38'),
(7, 1, 6, '2025-06-20', 'confirmed', '2025-06-12 16:23:38'),
(8, 1, 6, '2025-06-21', 'confirmed', '2025-06-12 16:23:38'),
(9, 1, 6, '2025-06-22', 'confirmed', '2025-06-12 16:23:38'),
(10, 1, 6, '2025-06-23', 'confirmed', '2025-06-12 16:23:38'),
(11, 1, 4, '2025-06-23', 'confirmed', '2025-06-12 16:23:53'),
(12, 1, 4, '2025-06-24', 'confirmed', '2025-06-12 16:23:53'),
(13, 1, 6, '2025-06-27', 'confirmed', '2025-06-12 16:24:10'),
(14, 1, 6, '2025-06-28', 'confirmed', '2025-06-12 16:24:10'),
(15, 1, 3, '2025-06-18', 'confirmed', '2025-06-12 16:24:48'),
(16, 1, 3, '2025-06-20', 'cancelled', '2025-06-12 16:24:48'),
(17, 1, 3, '2025-06-19', 'confirmed', '2025-06-12 16:24:48'),
(18, 1, 2, '2025-06-28', 'confirmed', '2025-06-12 16:30:31'),
(19, 1, 2, '2025-07-02', 'confirmed', '2025-06-12 16:30:31'),
(20, 1, 3, '2025-06-30', 'confirmed', '2025-06-12 16:36:24'),
(21, 1, 5, '2025-07-05', 'cancelled', '2025-06-12 16:37:01'),
(22, 1, 2, '2025-06-23', 'confirmed', '2025-06-12 16:45:27'),
(23, 1, 2, '2025-06-25', 'confirmed', '2025-06-12 16:45:27'),
(24, 1, 2, '2025-06-24', 'confirmed', '2025-06-12 16:45:27'),
(25, 1, 2, '2025-06-26', 'confirmed', '2025-06-12 16:45:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `description`, `price`, `images`) VALUES
(1, 'Habitación Familiar', 'familiar', 'Amplia habitación con dos camas dobles y vista al mar.', 200.00, '684ae2985fb39.jpg,684ae29ea08a7.jpg'),
(2, 'Suite de Lujo', 'suite', 'Suite con jacuzzi y balcón privado.', 500.00, '684ae4ba12fdc.jpg,684ae4ba134d6.jpg'),
(3, 'Habitación Doble', 'doble', 'Habitación moderna con cama doble y baño privado.', 300.00, '684ae4de74dde.jpg,684ae4de74fe8.jpg'),
(4, 'Habitación Individual', 'individual', 'Cómoda habitación para una persona con baño compartido.', 100.00, '684ae5099b688.jpg,684ae5099b8a9.jpg'),
(5, 'Habitación Familiar', 'suite', 'vista a la plaza ubicada en el ultimo piso', 500.00, '684ae7b6d8ddc.jpg'),
(6, 'Suite de Lujo', 'doble', 'comoda habitacion con baño privado, vista al calle', 200.00, '684ae80a4d901.jpg,684ae80a4db00.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'juan jose', 'juanjo@gmail.com', '$2y$10$3OwbtPXbGr2cxOXJcBW.iuVLODsoz4c26At6u1wDrZ6fOPL5uvlgu', 'user', '2025-06-12 03:03:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indices de la tabla `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
