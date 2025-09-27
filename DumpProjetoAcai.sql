CREATE DATABASE  IF NOT EXISTS `acai_admin` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `acai_admin`;
-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: acai_admin_alt
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
-- Table structure for table `admin_data`
--

DROP TABLE IF EXISTS `admin_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_data` (
  `idAdmin` int(11) NOT NULL,
  `adminPicture` varchar(200) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png	',
  PRIMARY KEY (`idAdmin`),
  CONSTRAINT `admin_data_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_data`
--

LOCK TABLES `admin_data` WRITE;
/*!40000 ALTER TABLE `admin_data` DISABLE KEYS */;
INSERT INTO `admin_data` VALUES (1,'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758992688/Users-Pictures/adminPic001.jpg'),(2,'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758992122/Users-Pictures/adminPic002.jpg');
/*!40000 ALTER TABLE `admin_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_data`
--

DROP TABLE IF EXISTS `client_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_data` (
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`idClient`),
  CONSTRAINT `client_data_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_data`
--

LOCK TABLES `client_data` WRITE;
/*!40000 ALTER TABLE `client_data` DISABLE KEYS */;
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
  CONSTRAINT `order_data_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_data`
--

LOCK TABLES `order_data` WRITE;
/*!40000 ALTER TABLE `order_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_data`
--

DROP TABLE IF EXISTS `product_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_data` (
  `idProduct` int(11) NOT NULL AUTO_INCREMENT,
  `printName` varchar(60) NOT NULL,
  `altName` varchar(40) NOT NULL,
  `brandProduct` varchar(40) DEFAULT 'Other Brand',
  `typeProduct` enum('Cream','Additional','Other') DEFAULT 'Other',
  PRIMARY KEY (`idProduct`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_data`
--

LOCK TABLES `product_data` WRITE;
/*!40000 ALTER TABLE `product_data` DISABLE KEYS */;
INSERT INTO `product_data` VALUES (1,'Caixa de Açaí','caixaAcai','Açaí e Polpas Amazônia','Cream'),(2,'Colheres p/ Açaí e Sorvete','colheres','Plastjet','Other'),(3,'Cremes Frutados','cremesFrutados','Energia Natural','Cream'),(4,'Açaí Zero','acaiZero','Other Brand','Cream'),(5,'Açaí c/Ninho','acaiNinho','Other Brand','Cream'),(6,'Morango Congelado','morango','Other Brand','Other'),(7,'Leite em Pó','leiteEmPo','Other Brand','Additional'),(8,'Granola Tia Sônia','granolaTiaSonia','Tia Sônia','Additional'),(9,'Granola Tradicional','granolaTradicional','Other Brand','Additional'),(10,'Caixa de Paçoca','caixaPacoca','Unidoces','Additional'),(11,'Farofa de Paçoca','farofaPacoca','Balsamo','Additional'),(12,'Amendoim Triturado','amendoimTriturado','Balsamo','Additional'),(13,'Ovomaltine','ovomaltine','Ovomaltine','Additional'),(14,'Gotas de Chocolate','gotasChocolate','Harald','Additional'),(15,'Chocoball','chocoball','VaBene','Additional'),(16,'Jujuba','jujuba','SimoGomas','Additional'),(17,'Confetes Coloridos','confetesColoridos','Coloreti','Additional'),(18,'Cremes Saborazzi','cremesSaborazzi','Saborazzi','Cream'),(19,'Polpas de Frutas','polpas','Sabor Natural','Other');
/*!40000 ALTER TABLE `product_data` ENABLE KEYS */;
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
  CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`idProduct`) REFERENCES `product_version` (`idVersion`) ON DELETE CASCADE
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
-- Table structure for table `product_version`
--

DROP TABLE IF EXISTS `product_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_version` (
  `idVersion` int(11) NOT NULL AUTO_INCREMENT,
  `idProduct` int(11) NOT NULL,
  `nameProduct` varchar(50) NOT NULL,
  `sizeProduct` varchar(20) NOT NULL,
  `priceProduct` decimal(8,2) DEFAULT 0.00,
  `priceDate` date NOT NULL,
  `availability` enum('1','0') DEFAULT '1',
  `imageURL` varchar(250) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png',
  `flavor` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idVersion`,`idProduct`,`nameProduct`),
  KEY `product_version_ibfk_1` (`idProduct`),
  CONSTRAINT `product_version_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `product_data` (`idProduct`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_version`
--

LOCK TABLES `product_version` WRITE;
/*!40000 ALTER TABLE `product_version` DISABLE KEYS */;
INSERT INTO `product_version` VALUES (1,1,'acaiT1','1l',15.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758995467/Projeto_Acai/Products/acaiT1.jpg',NULL),(2,1,'acaiT10','10l',110.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311744/caixa-acai_l7uokc.jpg',NULL),(3,1,'acaiT5','5l',60.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311744/caixa-acai_l7uokc.jpg',NULL),(4,2,'colher200','200 unidades',30.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg',NULL),(5,2,'colher500','500 unidades',60.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg',NULL),(6,2,'colher800','800 unidades',45.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg',NULL),(7,3,'cremeCupuacu10','10l',80.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg','Cupuaçu'),(8,3,'cremeMaracuja10','10l',80.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg','Maracujá'),(9,3,'cremeMorango10','10l',80.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg','Morango'),(10,3,'cremeNinho10','10l',85.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg','Ninho'),(11,4,'acaiZero10','10l',85.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127161/acaiZero10_prkapg.png',NULL),(12,5,'acaiNinho1','1l',17.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png',NULL),(13,5,'acaiNinho250','250ml',4.50,'2025-09-25','0','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png',NULL),(14,6,'morango1','1kg',14.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080166/strawberry_akhbkp.jpg',NULL),(15,7,'leiteEmPo1','1kg',25.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311884/leite_em_po_rkrf0f.jpg',NULL),(16,8,'granola1.5','1.5kg',60.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311897/granola_majjmg_o5aufd.jpg',NULL),(17,9,'granola1','1kg',20.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127305/granola1_thtano.png',NULL),(18,10,'pacoca150','150 unidades',23.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311925/pacoca_xebjbp.jpg',NULL),(19,11,'farofaPacoca1','1kg',22.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127182/farofaPacoca1_daggz8.png',NULL),(20,12,'amendoimTriturado1','1kg',22.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127121/amendoimTriturado1_whxfpb.png',NULL),(21,13,'ovomaltine1','1kg',44.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750106271/ovomaltine_ctpjsl.webp',NULL),(22,14,'gotasChocolate1','1kg',28.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg',NULL),(23,15,'chocoball1','1kg',20.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127346/chocoball1_sqqqrp.png',NULL),(24,16,'jujuba500','500g',8.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127379/jujuba500_t3dmzr.png',NULL),(25,17,'confeteColoridos1','1kg',35.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127425/confete1_trubju.png',NULL),(26,18,'saborazziAvelaP','10kg',198.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Avelã Premium'),(27,18,'saborazziAvelaT','10kg',168.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Avelã Tradicional'),(28,18,'saborazziChocomaltine','10kg',162.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Chocomaltine'),(29,18,'saborazziCocada','10kg',174.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Cocada Cremosa'),(30,18,'saborazziCookies','10kg',170.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Cookies'),(31,18,'saborazziLeitinho','10kg',160.00,'2025-09-26','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Leitinho'),(32,18,'saborazziPacoca','10kg',140.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Paçoca'),(33,18,'saborazziSkimoB','10kg',130.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Skimo Branco'),(34,18,'saborazziSkimoL','10kg',156.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Skimo ao Leite'),(35,18,'saborazziWafer','10kg',130.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg','Wafer'),(36,19,'polpaAbac','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Abacaxi'),(37,19,'polpaAbacHort','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Hortelã'),(38,19,'polpaAcrl','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Acerola'),(39,19,'polpaAcrlMamao','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Acerola c/Mamão'),(40,19,'polpaCacau','1 unidade',2.50,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Cacau'),(41,19,'polpaCaja','1 unidade',2.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Caja'),(42,19,'polpaCaju','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Caju'),(43,19,'polpaCupuacu','1 unidade',2.50,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Cupuaçu'),(44,19,'polpaGoiaba','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Goiaba'),(45,19,'polpaGraviola','1 unidade',2.50,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Graviola'),(46,19,'polpaManga','1 unidade',1.80,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Manga'),(47,19,'polpaMangaba','1 unidade',2.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Mangaba'),(48,19,'polpaMaracuja','1 unidade',3.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Maracuja'),(49,19,'polpaMorango','1 unidade',2.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Morango'),(50,19,'polpaUva','1 unidade',2.00,'2025-09-25','1','https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg','Uva');
/*!40000 ALTER TABLE `product_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_data` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(30) NOT NULL,
  `userMail` varchar(50) NOT NULL,
  `userPhone` varchar(16) NOT NULL,
  `userPassword` varchar(250) NOT NULL,
  `city` varchar(40) NOT NULL,
  `district` varchar(40) NOT NULL,
  `street` varchar(50) NOT NULL,
  `localNum` varchar(10) NOT NULL,
  `referencePoint` varchar(50) DEFAULT '',
  `state` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') DEFAULT 'MG',
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_data`
--

LOCK TABLES `user_data` WRITE;
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
INSERT INTO `user_data` VALUES (1,'Farofilson Bananilson','admin@dominio.com','(32) 9 7854 0244','$2y$10$sQhi2JujYSx8Xaa7e74GvOeBvl9PFbfaBTfrqYNGWvp4ystxqszhC','Cidade Legal','Bairro Legal','Rua Legal','993','','AC'),(2,'cliente 2','client@dominio.com','(33) 9 7543 5772','$2y$10$sQhi2JujYSx8Xaa7e74GvOeBvl9PFbfaBTfrqYNGWvp4ystxqszhC','Cidade','Bairro','Rua','81','','PR');
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-27 16:56:45
