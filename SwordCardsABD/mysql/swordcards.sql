-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2019 at 01:45 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swordcards`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `idCarta` int(11) NOT NULL,
  `nombreCarta` varchar(25) NOT NULL,
  `tipo` varchar(16) NOT NULL,
  `valor` int(4) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `winRate` float(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`idCarta`, `nombreCarta`, `tipo`, `valor`, `descripcion`, `winRate`) VALUES
(13, 'Goblin_Dios', 'Negro', 0, 'Regeneración_rápida', 4.00),
(14, 'Caballero', 'Azul', 0, 'Demasiado potente', 2.00),
(15, 'Goblin Dios', 'Verde', 0, 'Es muy feo', 4.00),
(16, 'Orko', 'Negro', 0, 'Regeneración_rápida', 3.00),
(17, 'Nigromante', 'Rojo', 0, 'Fantasma herrante', 3.00),
(18, 'Prueba', 'none', 2, 'sdsdsad', 2.00),
(19, 'Marine', 'Verde', 4, 'Armadura de hierro', 3.00),
(20, 'Tau', 'Naranja', 4, 'Tecnología de su lado', 5.00),
(22, 'Yo', 'Naranja', 2, 'Hmmmmmm', 1.00),
(23, 'TazDingoxD', 'Verde', 2, 'Colmillos con Magia', 2.00),
(24, 'Daniel Gordo', 'Grasiento', 5, 'Suelta donuts', 4.00),
(25, 'Daniel Gordo 2', 'Grasiento', 5, 'Suelta donuts', 4.00),
(26, 'NewDingo', 'Blanco', 2, 'Es muy feo', 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `cardsdeck`
--

CREATE TABLE `cardsdeck` (
  `idDeck` int(11) NOT NULL,
  `idCarta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cardsdeck`
--

INSERT INTO `cardsdeck` (`idDeck`, `idCarta`) VALUES
(17, 14),
(17, 17),
(18, 14),
(18, 14),
(18, 15),
(18, 16),
(18, 17),
(18, 17),
(18, 17),
(18, 18),
(19, 13),
(19, 14),
(20, 15),
(20, 16),
(21, 13),
(21, 14),
(21, 15),
(24, 13),
(24, 14),
(24, 14);

-- --------------------------------------------------------

--
-- Table structure for table `deck`
--

CREATE TABLE `deck` (
  `idDeck` int(11) NOT NULL,
  `nombreDeck` varchar(25) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `winRate` int(11) NOT NULL,
  `numCartas` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deck`
--

INSERT INTO `deck` (`idDeck`, `nombreDeck`, `idUsuario`, `winRate`, `numCartas`) VALUES
(17, 'Deck_Noob', 9, 0, 8),
(18, 'dfdsfsdf', 9, 0, 8),
(19, 'First_Deck', 11, 0, 8),
(20, 'Pro1', 11, 0, 8),
(21, 'dsad', 11, 0, 8),
(22, '1212', 11, 0, 8),
(23, 'SADSAD', 12, 0, 8),
(24, 'PruebaxDD', 9, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nick` varchar(25) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `rol` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nick`, `pwd`, `rol`) VALUES
(9, 'User1', '$2y$10$Rzd0YxhRE7Ou5UO6PRptXOnrnoHtu7OAaR7TAXbAZK8mlvwM6wONu', 'user'),
(10, 'Mario', '$2y$10$Kuvs9tooFramZIiv8nAYXOvgHGsnNZdpwJZl8uLltjeSooRxoNZjO', 'admin'),
(11, 'Paula', '$2y$10$403CvGEVJdl8cydAyZU3ae1zr.6zYRSPJUgTebZ1Mgtd7zh2vHQFG', 'user'),
(12, 'User22', '$2y$10$vmIp4.sDL0Ft5TA9wzDdVuAb4PmaZgBwFYwAOy5492QtP1mKcFmIG', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD UNIQUE KEY `idAdmin` (`idAdmin`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`idCarta`);

--
-- Indexes for table `cardsdeck`
--
ALTER TABLE `cardsdeck`
  ADD KEY `idDeck` (`idDeck`,`idCarta`) USING BTREE,
  ADD KEY `cardsdeck_ibfk_1` (`idCarta`);

--
-- Indexes for table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`idDeck`),
  ADD KEY `idUsuario` (`idUsuario`) USING BTREE;

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `idCarta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `deck`
--
ALTER TABLE `deck`
  MODIFY `idDeck` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `usuarios` (`idUsuario`);

--
-- Constraints for table `cardsdeck`
--
ALTER TABLE `cardsdeck`
  ADD CONSTRAINT `cardsdeck_ibfk_1` FOREIGN KEY (`idCarta`) REFERENCES `cards` (`idCarta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cardsdeck_ibfk_2` FOREIGN KEY (`idDeck`) REFERENCES `deck` (`idDeck`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deck`
--
ALTER TABLE `deck`
  ADD CONSTRAINT `deck_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
