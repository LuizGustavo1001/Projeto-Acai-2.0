CREATE DATABASE  IF NOT EXISTS `acai_admin` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `acai_admin`;
-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: acai_admin
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
-- Table structure for table `admin_address`
--

DROP TABLE IF EXISTS `admin_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_address` (
  `idAdmin` int(11) NOT NULL,
  `city` varchar(40) NOT NULL,
  `district` varchar(40) NOT NULL,
  `street` varchar(50) NOT NULL,
  `localNum` varchar(10) NOT NULL,
  `referencePoint` varchar(50) DEFAULT NULL,
  `state` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') DEFAULT 'MG',
  KEY `idAdmin` (`idAdmin`),
  CONSTRAINT `admin_address_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admin_data` (`idAdmin`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_address`
--

LOCK TABLES `admin_address` WRITE;
/*!40000 ALTER TABLE `admin_address` DISABLE KEYS */;
INSERT INTO `admin_address` VALUES (1,'Cidade Legal','MG','R. Legal','553',NULL,'MG');
/*!40000 ALTER TABLE `admin_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_changes`
--

DROP TABLE IF EXISTS `admin_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_changes` (
  `idChange` int(11) NOT NULL AUTO_INCREMENT,
  `idAdmin` int(11) NOT NULL,
  `changeStatus` enum('Accepted','Rejected','Pending') DEFAULT 'Pending',
  `changeDate` date NOT NULL,
  `changeHour` time NOT NULL,
  `changeDesc` varchar(200) NOT NULL,
  PRIMARY KEY (`idChange`),
  KEY `idAdmin` (`idAdmin`),
  CONSTRAINT `admin_changes_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admin_data` (`idAdmin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_changes`
--

LOCK TABLES `admin_changes` WRITE;
/*!40000 ALTER TABLE `admin_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_data`
--

DROP TABLE IF EXISTS `admin_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_data` (
  `idAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(30) NOT NULL,
  `adminMail` varchar(50) NOT NULL,
  `adminPhone` varchar(16) NOT NULL,
  `adminPicture` varchar(200) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png',
  `adminPassword` varchar(200) NOT NULL,
  PRIMARY KEY (`idAdmin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_data`
--

LOCK TABLES `admin_data` WRITE;
/*!40000 ALTER TABLE `admin_data` DISABLE KEYS */;
INSERT INTO `admin_data` VALUES (1,'Tijolilson TIjolos','tijolos@dominio.com','(35) 9 7438 7234','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757894088/622bc18a-9a8a-4aa3-987a-c2767a486266_wuq7i2.jpg','$2y$10$sQhi2JujYSx8Xaa7e74GvOeBvl9PFbfaBTfrqYNGWvp4ystxqszhC');
/*!40000 ALTER TABLE `admin_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_address`
--

DROP TABLE IF EXISTS `client_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_address` (
  `idClient` int(11) NOT NULL,
  `city` varchar(40) NOT NULL,
  `district` varchar(40) NOT NULL,
  `street` varchar(50) NOT NULL,
  `localNum` varchar(10) NOT NULL,
  `referencePoint` varchar(50) DEFAULT NULL,
  `state` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') DEFAULT 'MG',
  KEY `idClient` (`idClient`),
  CONSTRAINT `client_address_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_address`
--

LOCK TABLES `client_address` WRITE;
/*!40000 ALTER TABLE `client_address` DISABLE KEYS */;
INSERT INTO `client_address` VALUES (8,'Cachoeira Nova','Laranjeiras','R. das palmeiras','124','','MG');
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
  `clientPhone` varchar(16) NOT NULL,
  `clientPassword` varchar(200) NOT NULL,
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_data`
--

LOCK TABLES `client_data` WRITE;
/*!40000 ALTER TABLE `client_data` DISABLE KEYS */;
INSERT INTO `client_data` VALUES (8,'Farofilson Bananilson','farofa@dominio.com','(31) 9 7435 8274','$2y$10$n0ZUjyXqsc76pEuKP7/HCO9oibFQUuMTfDfF/nCSmpUbOvAYuA3hi');
/*!40000 ALTER TABLE `client_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_data`
--

DROP TABLE IF EXISTS `order_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_data` (
  `idOrder` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderHour` time NOT NULL,
  `orderStatus` enum('Finished','Pending') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`idOrder`),
  KEY `idClient` (`idClient`),
  CONSTRAINT `order_data_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_data`
--

LOCK TABLES `order_data` WRITE;
/*!40000 ALTER TABLE `order_data` DISABLE KEYS */;
INSERT INTO `order_data` VALUES (2,8,'2025-09-11','16:01:05','Pending'),(5,8,'2025-09-20','19:19:33','Pending'),(6,8,'2025-09-20','19:20:13','Pending'),(7,8,'2025-09-21','05:55:55','Pending'),(8,8,'2025-09-21','17:56:48','Pending'),(9,8,'2025-09-21','13:11:51','Pending');
/*!40000 ALTER TABLE `order_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `idProduct` int(11) NOT NULL AUTO_INCREMENT,
  `nameProduct` varchar(40) NOT NULL,
  `brandProduct` varchar(40) NOT NULL,
  `priceProduct` decimal(8,2) DEFAULT 0.00,
  `priceDate` date NOT NULL,
  `imageURL` varchar(200) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png',
  `availability` enum('1','0') DEFAULT '1',
  `typeProduct` enum('Cream','Additional','Other') DEFAULT 'Other',
  PRIMARY KEY (`idProduct`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'acaiT10','Açaí Amazônia',110.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','1','Cream'),(2,'acaiT5','Açaí Amazônia',60.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','1','Cream'),(3,'acaiT1','Açaí Amazônia',15.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','1','Cream'),(4,'colher200','Plastjet',30.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','1','Other'),(5,'colher500','Plastjet',60.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','1','Other'),(6,'colher800','Plastjet',45.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png','0','Other'),(7,'cremeCupuacu10','Energia Natural',80.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','1','Cream'),(8,'cremeNinho10','Energia Natural',85.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','1','Cream'),(9,'cremeMaracuja10','Energia Natural',80.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','1','Cream'),(10,'cremeMorango10','Energia Natural',80.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png','1','Cream'),(11,'acaiZero10','Other Brand',85.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg','1','Cream'),(12,'acaiNinho1','Other Brand',17.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Cream'),(13,'acaiNinho250','Other Brand',4.50,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Cream'),(14,'morango1','Other Brand',14.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080166/strawberry_akhbkp.jpg','1','Additional'),(15,'leiteEmPo1','Other Brand',25.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079860/leite_em_po_rkrf0f.png','1','Additional'),(16,'granola1.5','Tia Sônia',60.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750086648/granola_majjmg_o5aufd.png','1','Additional'),(17,'granola1','Other Brand',20.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(18,'pacoca150','Unidoces',23.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750106268/pacoca_xebjbp.png','1','Additional'),(19,'farofaPacoca1','Balsamo',22.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(20,'amendoimTriturado1','Balsamo',22.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(21,'ovomaltine1','Ovomaltine',44.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755096528/acai_doaqvb.png','1','Additional'),(22,'gotasChocolate1','Harald',28.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755096528/acai_doaqvb.png','1','Additional'),(23,'chocoball1','VaBene',20.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(24,'jujuba500','SimoGomas',8.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(25,'confete1','Coloreti',35.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png','1','Additional'),(26,'saborazziChocomalt','Saborazzi',162.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(27,'saborazziCocada','Saborazzi',174.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(28,'saborazziCookies','Saborazzi',170.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(29,'saborazziAvelaP','Saborazzi',198.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(30,'saborazziAvelaT','Saborazzi',168.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(31,'saborazziLeitinho','Saborazzi',160.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(32,'saborazziPacoca','Saborazzi',140.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(33,'saborazziSkimoL','Saborazzi',156.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(34,'saborazziSkimoB','Saborazzi',130.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(35,'saborazziWafer','Saborazzi',130.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079857/cremes-saborazzi_dhssx6.png','1','Cream'),(36,'polpaAbac','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(37,'polpaAbacHort','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(38,'polpaAcrl','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(39,'polpaAcrlMamao','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(40,'polpaCacau','Sabor Natural',2.50,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(41,'polpaCaja','Sabor Natural',2.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(42,'polpaCaju','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(43,'polpaCupuacu','Sabor Natural',2.50,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(44,'polpaGoiaba','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(45,'polpaGraviola','Sabor Natural',2.50,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(46,'polpaManga','Sabor Natural',1.80,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(47,'polpaMangaba','Sabor Natural',2.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(48,'polpaMaracuja','Sabor Natural',3.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(49,'polpaMorango','Sabor Natural',2.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other'),(50,'polpaUva','Sabor Natural',2.00,'2025-06-10','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg','1','Other');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_order`
--

DROP TABLE IF EXISTS `product_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_order` (
  `idOrder` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `totPrice` decimal(10,2) DEFAULT 0.00,
  `singlePrice` decimal(8,2) DEFAULT 0.00,
  PRIMARY KEY (`idOrder`,`idProduct`),
  KEY `idProduct` (`idProduct`),
  CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `order_data` (`idOrder`) ON DELETE CASCADE,
  CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`idProduct`) REFERENCES `product` (`idProduct`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_order`
--

LOCK TABLES `product_order` WRITE;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
INSERT INTO `product_order` VALUES (2,1,1,110.00,110.00),(2,10,1,80.00,80.00),(8,1,4,440.00,110.00),(8,11,1,85.00,85.00),(8,28,2,340.00,170.00);
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-21 13:51:41
