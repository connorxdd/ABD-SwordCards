-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2019 at 07:08 PM
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
(27, 'Granjero', 'Blanco', 2, 'Con su horca y antorcha', 2.00),
(28, 'Lobo Detective', 'Gris', 3, 'Investiga fantasía', 2.00),
(29, 'Minero ', 'Negro', 4, 'Pica hasta los diamantes', 2.00),
(30, 'Horda orca', 'Verde', 10, 'Ejército de orcos', 5.00),
(31, 'Goblins Lokos', 'Verde', 4, 'Con el cohete estallan todo', 3.00);

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
(25, 27),
(25, 27),
(25, 27),
(25, 28),
(25, 29),
(25, 30),
(25, 30),
(25, 31),
(26, 27),
(26, 27),
(26, 28),
(26, 29),
(26, 30),
(26, 30),
(26, 31),
(26, 31);

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
(26, 'Deck 2', 9, 0, 8);

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
(10, 'Mario', '$2y$10$Kuvs9tooFramZIiv8nAYXOvgHGsnNZdpwJZl8uLltjeSooRxoNZjO', 'admin');

--
-- Indexes for dumped tables
--

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
  MODIFY `idCarta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `deck`
--
ALTER TABLE `deck`
  MODIFY `idDeck` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`idCarta`) REFERENCES `cardsdeck` (`idCarta`);

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
