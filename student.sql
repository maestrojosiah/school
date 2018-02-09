-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: school
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `f_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `l_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B723AF33A76ED395` (`user_id`),
  KEY `IDX_B723AF33EA000B10` (`class_id`),
  CONSTRAINT `FK_B723AF33A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B723AF33EA000B10` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,1,1,'Moureen','Wangui','0765434565','7','female'),(2,1,1,'Sheldon','Mgawa','8','8','male'),(3,1,1,'Grace','Njoki','7','7','female'),(4,1,1,'Joseph','Ngunjiri','6','9','male'),(5,1,1,'Gabriel','Gichohi','9','7','male'),(6,1,1,'Sharleen','Gamaharo','7','8','female'),(7,1,1,'Trista','Nyambura','6','8','female'),(8,1,1,'Rachael','Kabura','7','9','female'),(9,1,1,'Joy','Wanjiku','8','9','female'),(10,1,1,'Lexine','Kweyu','6','4','female'),(11,1,1,'Alvin','Mwangi','6','8','female'),(12,1,1,'Mitchel','Wambui','2','2','female'),(13,1,1,'Beatrice','Watheri','4','6','female'),(14,1,1,'Ronald','Mutuku','4','4','male'),(15,1,1,'Talia','Rumeiswa','2','2','female'),(16,1,1,'James','Wakibi','1','1','male'),(17,1,1,'Juliet','Njeri','1','1','female'),(18,1,1,'Sally','Wangari','1','1','female'),(19,1,1,'Mourice','Macharia','1','1','male'),(20,1,1,'Samuel','Kagume','2','2','male'),(21,1,1,'Tess','Wambui','1','1','female'),(22,1,1,'Ann','Wambui','1','1','female'),(23,1,1,'Diana','Mugure','2','2','female'),(24,1,1,'Lewis','Maina','1','2','male'),(25,1,1,'Joyce','Waithera','2','2','female'),(26,1,1,'Vivian','Ivasha','1','1','female'),(27,1,1,'Mary','Njeri','1','1','female'),(28,1,1,'Nancy','Wangui','1','1','female'),(29,1,1,'Victor','Kamau','1','1','male'),(30,1,1,'Mitchel','Njeri','1','1','female'),(31,1,1,'Michael','Ndungu','1','1','male');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-08 20:34:54
