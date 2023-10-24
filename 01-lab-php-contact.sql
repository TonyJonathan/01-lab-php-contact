-- MySQL dump 10.13  Distrib 8.1.0, for Linux (x86_64)
--
-- Host: localhost    Database: contact
-- ------------------------------------------------------
-- Server version	8.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (16,'wayne','lil','lilwayne@hotmail.fr',10),(19,'cohen','jonathan','jonathan.cohen@hotmail.fr',11),(24,'Monkey D ','Luffy','gomugomuno@hotmail.fr',11),(25,'bruchon','tony','tony.bruchon@hotmail.fr',13);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `sel` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (7,'pass','Testhach','testpass@hotmail.fr','$2y$10$1p55C2GxQwdZkgfooOqBVOAe4Q7cqDiK1pmfAk2.RM7mB7E1176cy','7cddfabd7277793de1b6562990b68f6b',NULL),(8,'salut','hey','heysalut@hotmail.fr','$2y$10$cfiEV9KGkIb18AS2ZawYoe3s4WLZDNdwSPrmFAudVSEuy1FRUR58a','dab30bfd2e6623d4a4c01f27fc5a7a9c',NULL),(9,'mec','salut','salutmec@hotmail.fr','$2y$10$AB1C/uUFxNFqWidJ1q2KTOUjhwfuOmFyNivZ1QraJTTW9wflTfOsy','325de74317a406731cfad9bc97a5e4be',NULL),(10,'Monkey D ','Luffy','gomugomuno@jetpistol.fr','$2y$10$qfZio4a9nl6SKE52C75CH.nWhX.Gk2ze99rVxhWQgMMUMCd50yKg6','1235b763913ac7f42530e437e5aa6d95',NULL),(11,'bruchon','tony','tony-252@hotmail.fr','$2y$10$MxQSPWVAJTIeErUH06Uj6ugKxJy7XZJnKSIZTsvuhdV/t4KVmzKBu','a761d96db0963bd6c2700b35afe5bf96',NULL),(12,'test ','test ','test@hotmail.fr','$2y$10$zuW0BAfT5MMhpByit68ozuPbqJkwtEmZtkkB/4ccTdZ/pQmo5F9Nu','66705443a5c6618a7918d1538eaa9e21',NULL),(13,'bruchon','tony','tony.bruchon@hotmail.fr','$2y$10$sv1zJuMzKeKTq9Xnt6sO8uQxSdRjl4n25b2Du0hRK0rTFvWnMdwyy','c2af48556b0dd03b6b05cbd2d98bc216',NULL);
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-09-27 13:04:46
