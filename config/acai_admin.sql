-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: container_database
-- Generation Time: Apr 26, 2026 at 03:47 AM
-- Server version: 8.0.45
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acai_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_data`
--

CREATE TABLE `admin_data` (
  `idAdmin` int NOT NULL,
  `avatar` varchar(200) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1775011739/acai_vooaxn.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_data`
--

INSERT INTO `admin_data` (`idAdmin`, `avatar`) VALUES
(1, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png');

-- --------------------------------------------------------

--
-- Table structure for table `change_data`
--

CREATE TABLE `change_data` (
  `idChange` int NOT NULL,
  `idAdmin` int DEFAULT NULL,
  `changeType` enum('modify','remove','add') DEFAULT 'modify',
  `tableName` varchar(30) NOT NULL,
  `oldValue` varchar(250) NOT NULL,
  `newValue` varchar(250) NOT NULL,
  `dateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idAttribute` int NOT NULL,
  `attributeChange` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_data`
--

CREATE TABLE `customer_data` (
  `idCustomer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_data`
--

INSERT INTO `customer_data` (`idCustomer`) VALUES
(2);

-- --------------------------------------------------------

--
-- Table structure for table `order_data`
--

CREATE TABLE `order_data` (
  `idOrder` int NOT NULL,
  `idCustomer` int NOT NULL,
  `dateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_data`
--

CREATE TABLE `product_data` (
  `idProduct` int NOT NULL,
  `printName` varchar(60) NOT NULL,
  `altName` varchar(40) NOT NULL,
  `brand` varchar(40) DEFAULT 'Other',
  `typeProduct` enum('Other','Additional','Cream') DEFAULT 'Other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_data`
--

INSERT INTO `product_data` (`idProduct`, `printName`, `altName`, `brand`, `typeProduct`) VALUES
(1, 'Caixa de Açaí', 'CaixaAcai', 'Açaí e Polpas Amazônia', 'Cream'),
(2, 'Colheres p/ Açaí e Sorvete', 'colheres', 'Plastjet', 'Other'),
(3, 'Cremes Frutados', 'cremesFrutados', 'Energia Natural', 'Cream'),
(4, 'Açaí Zero', 'acaiZero', 'Outra Marca', 'Cream'),
(5, 'Açaí c/Ninho', 'acaiNinho', 'Outra Marca', 'Cream'),
(6, 'Morango Congelado', 'morango', 'Outra Marca', 'Other'),
(7, 'Leite em Pó', 'leiteEmPo', 'Outra Marca', 'Additional'),
(8, 'Granola Tia Sônia', 'granolaTiaSonia', 'Tia Sônia', 'Additional'),
(9, 'Granola Tradicional', 'granolaTradicional', 'Outra Marca', 'Additional'),
(10, 'Caixa de Paçoca', 'caixaPacoca', 'Unidoces', 'Additional'),
(11, 'Farofa de Paçoca', 'farofaPacoca', 'Balsamo', 'Additional'),
(12, 'Amendoim Triturado', 'amendoimTriturado', 'Balsamo', 'Additional'),
(13, 'Ovomaltine', 'ovomaltine', 'Ovomaltine', 'Additional'),
(14, 'Gotas de Chocolate', 'gotasChocolate', 'Harald', 'Additional'),
(15, 'Chocoball', 'chocoball', 'VaBene', 'Additional'),
(16, 'Jujuba', 'jujuba', 'SimoGomas', 'Additional'),
(17, 'Confetes Coloridos', 'confetesColoridos', 'Coloreti', 'Additional'),
(18, 'Cremes Saborazzi', 'cremesSaborazzi', 'Saborazzi', 'Cream'),
(19, 'Polpas de Frutas', 'polpas', 'Sabor Natural', 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `product_order`
--

CREATE TABLE `product_order` (
  `idOrder` int NOT NULL,
  `idVariant` int NOT NULL,
  `amount` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

CREATE TABLE `product_variant` (
  `idVariant` int NOT NULL,
  `idProduct` int NOT NULL,
  `name` varchar(60) NOT NULL,
  `size` varchar(20) NOT NULL,
  `flavor` varchar(40) DEFAULT NULL,
  `image` varchar(250) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1775011739/acai_vooaxn.png',
  `priceDate` date NOT NULL,
  `price` decimal(8,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_variant`
--

INSERT INTO `product_variant` (`idVariant`, `idProduct`, `name`, `size`, `flavor`, `image`, `priceDate`, `price`, `status`) VALUES
(1, 1, 'acaiT10', '10l', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759519124/Projeto_Acai/Products/acaiT10.jpg', '2025-10-03', 110.00, 1),
(2, 1, 'acaiT5', '5l', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759519173/Projeto_Acai/Products/acaiT5.jpg', '2025-10-03', 65.00, 1),
(3, 1, 'acaiT1', '1l', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758995467/Projeto_Acai/Products/acaiT1.jpg', '2025-11-03', 18.00, 1),
(4, 2, 'colher200', '200 unidades', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', '2025-09-25', 30.00, 1),
(5, 2, 'colher500', '500 unidades', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', '2025-09-25', 60.00, 1),
(6, 2, 'colher800', '800 unidades', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', '2025-09-25', 45.00, 1),
(7, 3, 'cremeCupuacu10', '10l', 'Cupuaçu', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', '2025-09-25', 80.00, 1),
(8, 3, 'cremeMaracuja10', '10l', 'Maracujá', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', '2025-09-25', 80.00, 1),
(9, 3, 'cremeMorango10', '10l', 'Morango', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', '2025-09-25', 80.00, 1),
(10, 3, 'cremeNinho10', '10l', 'Ninho', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', '2025-09-25', 85.00, 1),
(11, 4, 'acaiZero10', '10l', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127161/acaiZero10_prkapg.png', '2025-09-25', 85.00, 1),
(12, 5, 'acaiNinho1', '1l', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png', '2025-09-25', 17.00, 1),
(13, 5, 'acaiNinho250', '250ml', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png', '2025-09-25', 4.50, 1),
(14, 6, 'morango1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759502043/Projeto_Acai/Products/morango1.png', '2025-09-25', 14.00, 1),
(15, 7, 'leiteEmPo1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311884/leite_em_po_rkrf0f.jpg', '2025-09-25', 25.00, 1),
(16, 8, 'granola1.5', '1.5kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311897/granola_majjmg_o5aufd.jpg', '2025-09-25', 60.00, 1),
(17, 9, 'granola1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127305/granola1_thtano.png', '2025-09-25', 20.00, 1),
(18, 10, 'pacoca150', '150 unidades', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311925/pacoca_xebjbp.jpg', '2025-09-25', 23.00, 1),
(19, 11, 'farofaPacoca1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127182/farofaPacoca1_daggz8.png', '2025-09-25', 22.00, 1),
(20, 12, 'amendoimTriturado1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127121/amendoimTriturado1_whxfpb.png', '2025-09-25', 22.00, 1),
(21, 13, 'ovomaltine1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750106271/ovomaltine_ctpjsl.webp', '2025-09-25', 44.00, 1),
(22, 14, 'gotasChocolate1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg', '2025-09-25', 28.00, 1),
(23, 15, 'chocoball1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127346/chocoball1_sqqqrp.png', '2025-09-25', 20.00, 1),
(24, 16, 'jujuba500', '500g', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127379/jujuba500_t3dmzr.png', '2025-09-25', 8.00, 1),
(25, 17, 'confeteColoridos1', '1kg', NULL, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127425/confete1_trubju.png', '2025-09-25', 35.00, 1),
(26, 18, 'saborazziAvelaP', '10kg', 'Avelã Premium', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 198.00, 1),
(27, 18, 'saborazziAvelaT', '10kg', 'Avelã Tradicional', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 168.00, 1),
(28, 18, 'saborazziChocomaltine', '10kg', 'Chocomaltine', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 162.00, 1),
(29, 18, 'saborazziCocada', '10kg', 'Cocada Cremosa', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 174.00, 1),
(30, 18, 'saborazziCookies', '10kg', 'Cookies', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 170.00, 1),
(31, 18, 'saborazziLeitinho', '10kg', 'Leitinho', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-26', 160.00, 1),
(32, 18, 'saborazziPacoca', '10kg', 'Paçoca', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 140.00, 1),
(33, 18, 'saborazziSkimoB', '10kg', 'Skimo Branco', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 130.00, 1),
(34, 18, 'saborazziSkimoL', '10kg', 'Skimo ao Leite', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 156.00, 1),
(35, 18, 'saborazziWafer', '10kg', 'Wafer', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', '2025-09-25', 130.00, 1),
(36, 19, 'polpaAbac', '1 unidade', 'Abacaxi', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(37, 19, 'polpaAbacHort', '1 unidade', 'Hortelã', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(38, 19, 'polpaAcrl', '1 unidade', 'Acerola', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(39, 19, 'polpaAcrlMamao', '1 unidade', 'Acerola c/Mamão', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(40, 19, 'polpaCacau', '1 unidade', 'Cacau', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.50, 1),
(41, 19, 'polpaCaja', '1 unidade', 'Caja', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.00, 1),
(42, 19, 'polpaCaju', '1 unidade', 'Caju', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(43, 19, 'polpaCupuacu', '1 unidade', 'Cupuaçu', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.50, 1),
(44, 19, 'polpaGoiaba', '1 unidade', 'Goiaba', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(45, 19, 'polpaGraviola', '1 unidade', 'Graviola', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.50, 1),
(46, 19, 'polpaManga', '1 unidade', 'Manga', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 1.80, 1),
(47, 19, 'polpaMangaba', '1 unidade', 'Mangaba', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.00, 1),
(48, 19, 'polpaMaracuja', '1 unidade', 'Maracuja', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 3.00, 1),
(49, 19, 'polpaMorango', '1 unidade', 'Morango', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.00, 1),
(50, 19, 'polpaUva', '1 unidade', 'Uva', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', '2025-09-25', 2.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `idUser` int NOT NULL,
  `nameUser` varchar(30) NOT NULL,
  `mailUser` varchar(50) NOT NULL,
  `phoneUser` char(16) NOT NULL,
  `passwordUser` varchar(250) NOT NULL,
  `a_district` varchar(40) NOT NULL,
  `a_street` varchar(50) NOT NULL,
  `a_referencePoint` varchar(100) DEFAULT '',
  `a_numHouse` varchar(10) NOT NULL,
  `a_city` varchar(40) NOT NULL,
  `a_state` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') DEFAULT 'SP',
  `typeUser` enum('admin','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`idUser`, `nameUser`, `mailUser`, `phoneUser`, `passwordUser`, `a_district`, `a_street`, `a_referencePoint`, `a_numHouse`, `a_city`, `a_state`, `typeUser`) VALUES
(1, 'Admin 1', 'admin@domain.com', '(22) 2 2222 2222', '$2y$10$UUybSCbs3zBwsUca6AoOj.Wud9R1PUs8dw7voaBpEMCHKN.VAQ9Jm', 'District', 'Street', '', '999', 'City', 'RJ', 'admin'),
(2, 'Client 1', 'client@domain.com', '(33) 3 3333 3333', '$2y$10$DrQeuOuEkiQgkDdUa7j0XuXj0IrQ6pSwMhZlIBc9br32kSZ/iz9d.', 'Bairro', 'Rua', '.', '777', 'Cidade', 'PI', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indexes for table `change_data`
--
ALTER TABLE `change_data`
  ADD PRIMARY KEY (`idChange`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indexes for table `customer_data`
--
ALTER TABLE `customer_data`
  ADD PRIMARY KEY (`idCustomer`);

--
-- Indexes for table `order_data`
--
ALTER TABLE `order_data`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idCustomer` (`idCustomer`);

--
-- Indexes for table `product_data`
--
ALTER TABLE `product_data`
  ADD PRIMARY KEY (`idProduct`);

--
-- Indexes for table `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`idOrder`,`idVariant`),
  ADD KEY `product_order_ibfk_2` (`idVariant`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`idVariant`),
  ADD KEY `idProduct` (`idProduct`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `change_data`
--
ALTER TABLE `change_data`
  MODIFY `idChange` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_data`
--
ALTER TABLE `order_data`
  MODIFY `idOrder` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_data`
--
ALTER TABLE `product_data`
  MODIFY `idProduct` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `idVariant` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD CONSTRAINT `admin_data_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_data`
--
ALTER TABLE `change_data`
  ADD CONSTRAINT `change_data_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admin_data` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `customer_data`
--
ALTER TABLE `customer_data`
  ADD CONSTRAINT `customer_data_ibfk_1` FOREIGN KEY (`idCustomer`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_data`
--
ALTER TABLE `order_data`
  ADD CONSTRAINT `order_data_ibfk_1` FOREIGN KEY (`idCustomer`) REFERENCES `customer_data` (`idCustomer`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `order_data` (`idOrder`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`idVariant`) REFERENCES `product_variant` (`idVariant`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_variant_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `product_data` (`idProduct`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
