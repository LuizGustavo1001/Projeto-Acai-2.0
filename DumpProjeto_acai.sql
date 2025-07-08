CREATE DATABASE  IF NOT EXISTS `projeto_acai` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `projeto_acai`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: projeto_acai
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client_address`
--

DROP TABLE IF EXISTS `client_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_address` (
  `idClient` int(11) NOT NULL,
  `district` varchar(40) NOT NULL,
  `localNum` varchar(8) NOT NULL,
  `referencePoint` varchar(50) DEFAULT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(40) NOT NULL,
  PRIMARY KEY (`idClient`),
  CONSTRAINT `client_address_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_address`
--

LOCK TABLES `client_address` WRITE;
/*!40000 ALTER TABLE `client_address` DISABLE KEYS */;
INSERT INTO `client_address` VALUES (1,'Cerejeiras','111','adasda','R. das Pétalas','Pinheiros');
/*!40000 ALTER TABLE `client_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_data`
--

DROP TABLE IF EXISTS `client_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_data` (
  `idClient` int(11) NOT NULL AUTO_INCREMENT,
  `clientName` varchar(30) NOT NULL,
  `clientMail` varchar(50) NOT NULL,
  `clientPassword` varchar(100) NOT NULL,
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_data`
--

LOCK TABLES `client_data` WRITE;
/*!40000 ALTER TABLE `client_data` DISABLE KEYS */;
INSERT INTO `client_data` VALUES (1,'Farofilson Bananilson','farofa@gmail.com','$2y$10$x25JPJffTMoH1VdIUvzsr.nyjtYu1IYmmKM2gvTa97dt3kY4JqUle');
/*!40000 ALTER TABLE `client_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_number`
--

DROP TABLE IF EXISTS `client_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_number` (
  `idClient` int(11) NOT NULL,
  `clientNumber` varchar(20) NOT NULL,
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `clientNumber` (`clientNumber`),
  CONSTRAINT `client_number_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_number`
--

LOCK TABLES `client_number` WRITE;
/*!40000 ALTER TABLE `client_number` DISABLE KEYS */;
INSERT INTO `client_number` VALUES (1,'(33) 9 1231 7658');
/*!40000 ALTER TABLE `client_number` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_order`
--

DROP TABLE IF EXISTS `client_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_order` (
  `idOrder` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) NOT NULL,
  `orderDate` date DEFAULT curdate(),
  `orderHour` time DEFAULT curtime(),
  `orderStmt` enum('Finished','Pending') DEFAULT 'Pending',
  PRIMARY KEY (`idOrder`),
  KEY `idClient` (`idClient`),
  CONSTRAINT `client_order_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_order`
--

LOCK TABLES `client_order` WRITE;
/*!40000 ALTER TABLE `client_order` DISABLE KEYS */;
INSERT INTO `client_order` VALUES (1,1,'2025-07-08','12:35:34','Pending');
/*!40000 ALTER TABLE `client_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `idProd` int(11) NOT NULL,
  `nameProd` varchar(40) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `condProd` tinyint(1) NOT NULL,
  `brand` varchar(30) DEFAULT 'Other Brand',
  `priceDate` date DEFAULT NULL,
  `imageURL` varchar(200) NOT NULL DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png',
  `prodType` varchar(15) NOT NULL DEFAULT 'Other',
  PRIMARY KEY (`idProd`),
  UNIQUE KEY `nameProd` (`nameProd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'acaiT10',110.00,1,'Açaí Amazônia','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(2,'acaiT5',60.00,1,'Açaí Amazônia','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(3,'acaiT1',15.00,1,'Açaí Amazônia','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(4,'colher200',30.00,1,'Plastjet','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','Other'),(5,'colher500',60.00,1,'Plastjet','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','Other'),(6,'colher800',45.00,1,'Plastjet','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','Other'),(7,'cremeCupuacu10',80.00,1,'Energia Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','Cream'),(8,'cremeNinho10',85.00,1,'Energia Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','Cream'),(9,'cremeMaracuja10',80.00,1,'Energia Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','Cream'),(10,'cremeMorango10',80.00,1,'Energia Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','Cream'),(11,'acaiZero10',85.00,1,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(12,'acaiNinho1',17.00,1,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(13,'acaiNinho250',4.50,0,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','Cream'),(14,'morango1',14.00,1,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080166/strawberry_akhbkp.jpg','additional'),(15,'leiteEmPo1',25.00,1,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079860/leite_em_po_rkrf0f.png','additional'),(16,'granola1.5',60.00,1,'Tia Sônia','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750086648/granola_majjmg_o5aufd.png','additional'),(17,'granola1',20.00,1,'Other Brand','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(18,'pacoca150',23.00,1,'Unidoces','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750106268/pacoca_xebjbp.png','additional'),(19,'farofaPacoca1',22.00,1,'Balsamo','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(20,'amendoimTriturado1',22.00,1,'Balsamo','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(21,'ovomaltine1',44.00,1,'Ovomaltine','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(22,'gotaChocolate1',28.00,1,'Harald','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(23,'chocoball1',20.00,1,'VaBene','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(24,'jujuba500',8.00,1,'SimoGomas','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(25,'disquete1',35.00,1,'Coloreti','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png','additional'),(26,'saborazziChocomalt',162.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(27,'saborazziCocada',174.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(28,'saborazziCookies',170.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(29,'saborazziAvelaP',198.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(30,'saborazziAvelaT',168.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(31,'saborazziLeitinho',160.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(32,'saborazziPacoca',140.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(33,'saborazziSkimoL',156.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(34,'saborazziSkimoB',130.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(35,'saborazziWafer',130.00,1,'Saborazzi','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','Cream'),(36,'polpaAbac',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(37,'polpaAbacHort',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(38,'polpaAcrl',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(39,'polpaAcrlMamao',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(40,'polpaCacau',2.50,0,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(41,'polpaCaja',2.00,0,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(42,'polpaCaju',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(43,'polpaCupuacu',2.50,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(44,'polpaGoiaba',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(45,'polpaGraviola',2.50,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(46,'polpaManga',1.80,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(47,'polpaMangaba',2.00,0,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(48,'polpaMaracuja',3.00,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(49,'polpaMorango',2.00,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other'),(50,'polpaUva',2.00,1,'Sabor Natural','2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','Other');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_order`
--

DROP TABLE IF EXISTS `product_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_order` (
  `idProdOrd` int(11) NOT NULL AUTO_INCREMENT,
  `idOrder` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `singlePrice` decimal(6,2) NOT NULL DEFAULT 0.00,
  `totPrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`idProdOrd`),
  KEY `idOrder` (`idOrder`),
  KEY `idProd` (`idProd`),
  CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `client_order` (`idOrder`),
  CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`idProd`) REFERENCES `product` (`idProd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_order`
--

LOCK TABLES `product_order` WRITE;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rescuepassword`
--

DROP TABLE IF EXISTS `rescuepassword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rescuepassword` (
  `idRescue` int(11) NOT NULL AUTO_INCREMENT,
  `rescueToken` varchar(6) NOT NULL,
  `dayLimit` date NOT NULL,
  `hourLimit` time NOT NULL,
  `emailReciever` varchar(50) NOT NULL,
  PRIMARY KEY (`idRescue`),
  UNIQUE KEY `rescueToken` (`rescueToken`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rescuepassword`
--

LOCK TABLES `rescuepassword` WRITE;
/*!40000 ALTER TABLE `rescuepassword` DISABLE KEYS */;
/*!40000 ALTER TABLE `rescuepassword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'projeto_acai'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-08 12:37:01
