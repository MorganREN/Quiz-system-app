-- MySQL dump 10.13  Distrib 8.0.27, for Linux (x86_64)
--
-- Host: localhost    Database: m33394mr
-- ------------------------------------------------------
-- Server version	8.0.27-0ubuntu0.20.04.1

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
-- Table structure for table `Question`
--

DROP TABLE IF EXISTS `Question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Question` (
  `ID` mediumint NOT NULL AUTO_INCREMENT,
  `Question_ID` int NOT NULL,
  `Quiz_ID` int NOT NULL,
  `Question` varchar(80) DEFAULT NULL,
  `Option_1` varchar(80) DEFAULT NULL,
  `Option_2` varchar(80) DEFAULT NULL,
  `Option_3` varchar(80) DEFAULT NULL,
  `Option_4` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Question`
--

LOCK TABLES `Question` WRITE;
/*!40000 ALTER TABLE `Question` DISABLE KEYS */;
INSERT INTO `Question` VALUES (10,3,738942,'which one is 3','1','2','3','4'),(11,1,659245,'What\'s the color of watermelon?','yellow','blue','black','green'),(12,2,659245,'Color of grass','black','green','purple','red'),(13,3,659245,'3+2?','1','3','5','7'),(14,1,979552,'3+3=?','2','3','6','1'),(15,2,979552,'2*4=?','2','8','4','1'),(16,1,187958,'4+2=?','2','3','9','6'),(17,2,187958,'3+3=?','2','3','6','7'),(18,3,187958,'5*6=?','30','40','50','60'),(19,1,521099,'woyouduoshuai','henshuai','chaojishuai','feichangshuai','tebieshuai'),(20,2,521099,'1+1=?','2','3','4','5'),(21,1,507860,'1+1=','1','2','3','4'),(22,2,507860,'2+2=','2','3','4','5'),(23,3,507860,'2*4=','4','8','3','2'),(24,1,172280,'1+3=?','3','4','5','6'),(25,2,172280,'3+3=?','4','6','3','2'),(26,3,172280,'4*4=?','13','16','36','22');
/*!40000 ALTER TABLE `Question` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-03 13:45:20
