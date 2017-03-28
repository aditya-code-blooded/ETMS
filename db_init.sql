-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: etms_db
-- ------------------------------------------------------
-- Server version	5.7.17-0ubuntu0.16.04.1

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
-- Table structure for table `email_addresses`
--

DROP TABLE IF EXISTS `email_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cascade_email_deletes` (`user_name`),
  CONSTRAINT `cascade_email_deletes` FOREIGN KEY (`user_name`) REFERENCES `users` (`user_name`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_addresses`
--

LOCK TABLES `email_addresses` WRITE;
/*!40000 ALTER TABLE `email_addresses` DISABLE KEYS */;
INSERT INTO `email_addresses` VALUES (21,'tonystark123','iamtonystark@gmail.com'),(22,'captainAmerica123','iamcaptainamerica@gmail.com'),(23,'hulk123','iamhulk@gmail.com');
/*!40000 ALTER TABLE `email_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `paid_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`expense_id`),
  UNIQUE KEY `expenseConstraint` (`user_name`,`amount`,`description`),
  CONSTRAINT `expenses_foreign_key` FOREIGN KEY (`user_name`) REFERENCES `users` (`user_name`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (16,'tonystark123',1500,'2017-03-28','Bought books'),(17,'tonystark123',5000,'2017-03-28','New Ironman suit was purchased'),(18,'tonystark123',2000,'2017-03-28','Repairs for Ironman suit'),(19,'tonystark123',3500,'2017-03-28','Miscellaneous expenses'),(20,'tonystark123',4000,'2017-03-28','Party'),(21,'tonystark123',1000,'2017-03-28','Hulk buster materials bought'),(22,'captainAmerica123',2000,'2017-03-28','Bought new shoes'),(23,'hulk123',150,'2017-03-28','Buy sandwich');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo`
--

DROP TABLE IF EXISTS `todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo` (
  `todo_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `todo_date` date NOT NULL,
  PRIMARY KEY (`todo_id`),
  UNIQUE KEY `todoConstraint` (`user_name`,`title`,`description`),
  CONSTRAINT `todo_foreign_key` FOREIGN KEY (`user_name`) REFERENCES `users` (`user_name`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo`
--

LOCK TABLES `todo` WRITE;
/*!40000 ALTER TABLE `todo` DISABLE KEYS */;
INSERT INTO `todo` VALUES (27,'tonystark123','Need to make an Ironman suit','The old Ironman suit is broken. Need to make enhancements to it.','2017-03-28'),(28,'tonystark123','Should save the world from Ultron','I need to assemble all the avengers to the save the world from Ultron','2017-03-28'),(29,'tonystark123','Party','Don\'t forget to party after defeating Thanos','2017-03-28'),(30,'captainAmerica123','Need to help Tony','Since Thanos is going to destroy the earth, I need to help Stark defeat him.','2017-03-28'),(31,'hulk123','Hulk Smash','Smash villains','2017-03-28');
/*!40000 ALTER TABLE `todo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `college` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`user_name`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (30,'tonystark123','$2y$10$Lb/X9uYhS2HWcDnVFABvSee9rrzMa0hOGbT.BdCibLYDxP907q6Dm','Tony','Stark','male','uploads/Tony_Stark.jpg','JNTUH - College of Engineering Hyderabad','Palo Alto California','1234567890'),(31,'captainAmerica123','$2y$10$Rc26bApt5Q1G9r1FENVv..r8Oy.h4KstFj5Q6n.GvmEpEYl9uDN1q','Captain','America','male','uploads/Captain-America.jpg','JNTUH - College of Engineering Hyderabad','Palo Alto California','1234567890'),(32,'hulk123','$2y$10$6nO3GuEr3MNy7LKaMoNXdO4DylyvA20PePEMad1C2A06m7kAYa4vS','Hulk','','male','uploads/hulk.png','JNTUH - College of Engineering Hyderabad','Palo Alto California','1234567890');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-28  5:46:59
