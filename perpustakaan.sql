-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: perpustakaan
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `book_reports`
--

DROP TABLE IF EXISTS `book_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `issue_type` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `book_reports_user_id_foreign` (`user_id`) USING BTREE,
  KEY `book_reports_book_id_foreign` (`book_id`) USING BTREE,
  CONSTRAINT `book_reports_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `book_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_reports`
--

LOCK TABLES `book_reports` WRITE;
/*!40000 ALTER TABLE `book_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `book_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `penulis_id` bigint(20) unsigned NOT NULL,
  `penerbit_id` bigint(20) unsigned NOT NULL,
  `kategori_id` bigint(20) unsigned DEFAULT NULL,
  `tahun` year(4) NOT NULL,
  `stok` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `file_buku` varchar(191) DEFAULT NULL COMMENT 'Path file PDF E-Book',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `books_penulis_id_foreign` (`penulis_id`) USING BTREE,
  KEY `books_penerbit_id_foreign` (`penerbit_id`) USING BTREE,
  KEY `books_kategori_id_foreign` (`kategori_id`) USING BTREE,
  CONSTRAINT `books_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE,
  CONSTRAINT `books_penerbit_id_foreign` FOREIGN KEY (`penerbit_id`) REFERENCES `penerbit` (`id`) ON DELETE CASCADE,
  CONSTRAINT `books_penulis_id_foreign` FOREIGN KEY (`penulis_id`) REFERENCES `penulis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,'ligma',3,2,1,2025,111121110,'covers/zughivUOJjOInB3qdss7XIEf3URA0uJYdDnsf9mL.jpg',NULL,NULL,NULL,NULL,NULL),(2,'Fibonnaci',1,3,2,2000,101000,'covers/d7UZbrYYCTTmcECuBitqiEUPBq6W87vEsSndd9px.jpg',NULL,NULL,NULL,NULL,NULL),(5,'Fibonnaci',1,3,1,1999,1000,NULL,NULL,NULL,NULL,'2026-01-13 18:49:36',NULL),(6,'money psycology',1,1,1,1999,1009999,NULL,NULL,NULL,NULL,NULL,NULL),(10,'jjj',3,2,2,2000,100,NULL,NULL,NULL,NULL,NULL,NULL),(12,'b',2,1,2,2000,999,NULL,NULL,NULL,NULL,NULL,5),(13,'jovianloverachelkeithlin',3,3,1,2000,999,NULL,NULL,NULL,NULL,NULL,NULL),(14,'angelamerindukanlionels',3,1,2,2000,2002,'covers/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg','ebooks/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg',NULL,NULL,NULL,NULL),(16,'rikardobermalamdengannatalia',2,1,3,2025,11,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_masuk_buku`
--

DROP TABLE IF EXISTS `data_masuk_buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_masuk_buku` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint(20) unsigned NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `data_masuk_buku_book_id_foreign` (`book_id`) USING BTREE,
  CONSTRAINT `data_masuk_buku_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_masuk_buku`
--

LOCK TABLES `data_masuk_buku` WRITE;
/*!40000 ALTER TABLE `data_masuk_buku` DISABLE KEYS */;
INSERT INTO `data_masuk_buku` VALUES (1,2,100,'2025-10-22',NULL,NULL,NULL,NULL),(2,2,100000,'2025-10-27',NULL,NULL,NULL,NULL),(3,16,10,'2025-10-31',NULL,NULL,NULL,NULL),(4,1,111111111,'2026-01-14',NULL,NULL,NULL,NULL),(5,6,1000000,'2026-01-14',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `data_masuk_buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edit_histories`
--

DROP TABLE IF EXISTS `edit_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edit_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(191) NOT NULL,
  `action_type` varchar(191) DEFAULT NULL,
  `row_id` bigint(20) unsigned NOT NULL,
  `perubahan` text DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `edited_by` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(191) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_histories`
--

LOCK TABLES `edit_histories` WRITE;
/*!40000 ALTER TABLE `edit_histories` DISABLE KEYS */;
INSERT INTO `edit_histories` VALUES (1,'users',NULL,1,'Data user diperbarui',NULL,NULL,5,NULL,NULL,NULL,NULL,'2026-01-14 13:05:36','2026-01-14 13:05:36'),(2,'users',NULL,1,'Data user diperbarui','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}','{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":\"1\"}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:19:26','2026-01-14 13:19:26'),(3,'users',NULL,1,'Revert ke versi sebelumnya','{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:19:32','2026-01-14 13:19:32'),(4,'users',NULL,1,'Revert ke versi sebelumnya','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}','{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:19:52','2026-01-14 13:19:52'),(5,'users',NULL,1,'Revert ke versi sebelumnya','{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:20:04','2026-01-14 13:20:04'),(6,'users',NULL,1,'Revert ke versi sebelumnya','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}','{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:20:29','2026-01-14 13:20:29'),(7,'books',NULL,12,'Data buku diperbarui','{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}','{\"judul\":\"a\",\"penulis_id\":\"2\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"999\"}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:20:52','2026-01-14 13:20:52'),(8,'books',NULL,12,'Revert ke versi sebelumnya','{\"judul\":\"a\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}','{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:23:49','2026-01-14 13:23:49'),(9,'books',NULL,14,'Judul: angelamerindukanlionel → angelamerindukansc','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}','{\"judul\":\"angelamerindukansc\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2002\"}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:35:10','2026-01-14 13:35:10'),(10,'books',NULL,14,'Revert ke versi sebelumnya','{\"judul\":\"angelamerindukansc\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:35:24','2026-01-14 13:35:24'),(11,'books',NULL,12,'Judul: b → a','{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}','{\"judul\":\"a\",\"penulis_id\":\"2\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"999\"}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:41:49','2026-01-14 13:41:49'),(12,'books',NULL,12,'Revert: Judul: a → b','{\"judul\":\"a\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}','{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}',5,NULL,NULL,NULL,NULL,'2026-01-14 13:41:58','2026-01-14 13:41:58'),(13,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 12:32:07','2026-01-26 12:32:07'),(14,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 12:41:13','2026-01-26 12:41:13'),(15,'users','login',2,'User logged in',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 12:41:23','2026-01-26 12:41:23'),(16,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 12:41:40','2026-01-26 12:41:40'),(17,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 12:59:50','2026-01-26 12:59:50'),(18,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:00:00','2026-01-26 13:00:00'),(19,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:18:56','2026-01-26 13:18:56'),(20,'users','login',2,'User logged in',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:19:03','2026-01-26 13:19:03'),(21,'users','logout',2,'User logged out',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:25:31','2026-01-26 13:25:31'),(22,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:25:39','2026-01-26 13:25:39'),(23,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:25:46','2026-01-26 13:25:46'),(24,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:25:53','2026-01-26 13:25:53'),(25,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:26:28','2026-01-26 13:26:28'),(26,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 13:26:35','2026-01-26 13:26:35'),(27,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 14:37:25','2026-01-26 14:37:25'),(28,'users','login',10,'User logged in',NULL,NULL,10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 14:46:41','2026-01-26 14:46:41'),(29,'users','login',10,'User logged in',NULL,NULL,10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 14:55:36','2026-01-26 14:55:36'),(30,'users','logout',10,'User logged out',NULL,NULL,10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 14:55:39','2026-01-26 14:55:39'),(31,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:09:54','2026-01-26 15:09:54'),(32,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:19:48','2026-01-26 15:19:48'),(33,'users','login',2,'User logged in',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:20:01','2026-01-26 15:20:01'),(34,'users','logout',2,'User logged out',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:22:56','2026-01-26 15:22:56'),(35,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:23:06','2026-01-26 15:23:06'),(36,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:36:12','2026-01-26 15:36:12'),(37,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:36:19','2026-01-26 15:36:19'),(38,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:41:18','2026-01-26 15:41:18'),(39,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:41:25','2026-01-26 15:41:25'),(40,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:43:12','2026-01-26 15:43:12'),(41,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:43:35','2026-01-26 15:43:35'),(42,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:45:02','2026-01-26 15:45:02'),(43,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 15:48:17','2026-01-26 15:48:17'),(44,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:10:52','2026-01-26 16:10:52'),(45,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:11:08','2026-01-26 16:11:08'),(46,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:11:25','2026-01-26 16:11:25'),(47,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:13:51','2026-01-26 16:13:51'),(48,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:14:11','2026-01-26 16:14:11'),(49,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:14:23','2026-01-26 16:14:23'),(50,'books','update',14,'Foto: diubah; File E-Book: diubah','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":null,\"file_buku\":null}','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\",\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}',5,NULL,NULL,NULL,NULL,'2026-01-26 16:16:57','2026-01-26 16:16:57'),(51,'books','update',14,'Data buku diperbarui','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\"}',5,NULL,NULL,NULL,NULL,'2026-01-26 16:17:24','2026-01-26 16:17:24'),(52,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:17:36','2026-01-26 16:17:36'),(53,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:17:44','2026-01-26 16:17:44'),(54,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:18:11','2026-01-26 16:18:11'),(55,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:24:55','2026-01-26 16:24:55'),(56,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:27:17','2026-01-26 16:27:17'),(57,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:35:47','2026-01-26 16:35:47'),(58,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 16:35:54','2026-01-26 16:35:54'),(59,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-26 17:18:40','2026-01-26 17:18:40'),(60,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-01-27 00:45:45','2026-01-27 00:45:45'),(61,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 13:59:35','2026-02-03 13:59:35'),(62,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 14:45:56','2026-02-03 14:45:56'),(63,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 14:46:03','2026-02-03 14:46:03'),(64,'books','update',14,'Judul: angelamerindukanlionel → angelamerindukanlionels','{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}','{\"judul\":\"angelamerindukanlionels\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\"}',5,NULL,NULL,NULL,NULL,'2026-02-03 15:10:15','2026-02-03 15:10:15'),(65,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:12:37','2026-02-03 15:12:37'),(66,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:12:45','2026-02-03 15:12:45'),(67,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:21:00','2026-02-03 15:21:00'),(68,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:21:07','2026-02-03 15:24:18'),(69,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:24:18','2026-02-03 15:24:18'),(70,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:24:42','2026-02-03 15:24:42'),(71,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:24:49','2026-02-03 15:26:20'),(72,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:25:02','2026-02-03 15:25:02'),(73,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:26:20','2026-02-03 15:26:20'),(74,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:27:43','2026-02-03 15:27:43'),(75,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:30:31','2026-02-03 15:30:42'),(76,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:30:42','2026-02-03 15:30:42'),(77,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:30:55','2026-02-03 15:30:55'),(78,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:31:04','2026-02-03 15:31:18'),(79,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:31:18','2026-02-03 15:31:18'),(80,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:31:44','2026-02-03 15:31:44'),(81,'users','login',2,'User logged in',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:31:52','2026-02-03 15:31:52'),(82,'users','logout',2,'User logged out',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:32:14','2026-02-03 15:32:14'),(83,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:32:20','2026-02-03 15:33:21'),(84,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:32:35','2026-02-03 15:32:35'),(85,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:32:49','2026-02-03 15:32:49'),(86,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:33:21','2026-02-03 15:33:21'),(87,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:33:33','2026-02-03 15:33:33'),(88,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:33:41','2026-02-03 15:34:09'),(89,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:33:52','2026-02-03 15:33:52'),(90,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:34:09','2026-02-03 15:34:09'),(91,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:34:35','2026-02-03 15:34:35'),(92,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:34:41','2026-02-03 15:36:19'),(93,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:34:59','2026-02-03 15:34:59'),(94,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:35:00','2026-02-03 15:35:00'),(95,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:36:08','2026-02-03 15:36:08'),(96,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:36:19','2026-02-03 15:36:19'),(97,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:36:34','2026-02-03 15:36:34'),(98,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:36:41','2026-02-03 15:37:03'),(99,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:37:03','2026-02-03 15:37:03'),(100,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:37:05','2026-02-03 15:37:05'),(101,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:37:19','2026-02-03 15:37:33'),(102,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:37:33','2026-02-03 15:37:33'),(103,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:37:42','2026-02-03 15:37:42'),(104,'users','login',16,'User logged in',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:39:14','2026-02-03 15:39:14'),(105,'users','logout',16,'User logged out',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:39:20','2026-02-03 15:39:20'),(106,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:39:37','2026-02-03 15:39:37'),(107,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:39:54','2026-02-03 15:39:54'),(108,'users','login',4,'User logged in',NULL,NULL,4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:40:02','2026-02-03 15:40:02'),(109,'users','logout',4,'User logged out',NULL,NULL,4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:40:35','2026-02-03 15:40:35'),(110,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:40:41','2026-02-03 15:44:13'),(111,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:40:56','2026-02-03 15:40:56'),(112,'books','delete',12,'Buku dihapus (Soft Delete)',NULL,NULL,5,NULL,NULL,NULL,NULL,'2026-02-03 15:41:13','2026-02-03 15:41:13'),(113,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:41:25','2026-02-03 15:41:25'),(114,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:41:44','2026-02-03 15:41:44'),(115,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:42:22','2026-02-03 15:42:22'),(116,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:42:51','2026-02-03 15:42:51'),(117,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:43:39','2026-02-03 15:43:39'),(118,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:44:13','2026-02-03 15:44:13'),(119,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:44:19','2026-02-03 15:44:19'),(120,'users','login',16,'User logged in',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,'2026-02-03 15:45:04','2026-02-03 15:45:04'),(121,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:45:33','2026-02-03 15:48:22'),(122,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:45:46','2026-02-03 15:45:46'),(123,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:47:30','2026-02-03 15:47:30'),(124,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1.02650000,103.92590000,'2026-02-03 15:48:22','2026-02-03 15:48:22'),(125,'users','login',1,'User logged in',NULL,NULL,1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-23 00:48:14','2026-04-23 00:48:14'),(126,'users','logout',1,'User logged out',NULL,NULL,1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-23 00:48:20','2026-04-23 00:48:20'),(127,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:48:29','2026-04-23 00:59:17'),(128,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',-7.88490000,112.01040000,'2026-04-23 00:48:42','2026-04-23 00:48:42'),(129,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:48:57','2026-04-23 00:48:57'),(130,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:49:01','2026-04-23 00:49:01'),(131,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:49:04','2026-04-23 00:49:04'),(132,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:49:07','2026-04-23 00:49:07'),(133,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:49:08','2026-04-23 00:49:08'),(134,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326512,104.01571313,'2026-04-23 00:49:17','2026-04-23 00:49:17'),(135,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326477,104.01571671,'2026-04-23 00:50:50','2026-04-23 00:50:50'),(136,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326477,104.01571671,'2026-04-23 00:50:59','2026-04-23 00:50:59'),(137,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12326477,104.01571671,'2026-04-23 00:51:02','2026-04-23 00:51:02'),(138,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:51:09','2026-04-23 00:51:09'),(139,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:51:12','2026-04-23 00:51:12'),(140,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:51:16','2026-04-23 00:51:16'),(141,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:51:17','2026-04-23 00:51:17'),(142,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12324990,104.01570909,'2026-04-23 00:59:17','2026-04-23 00:59:17'),(143,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12327620,104.01572266,'2026-04-23 01:47:37','2026-04-23 01:54:23'),(144,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333850,104.01562300,'2026-04-27 00:51:42','2026-04-27 01:19:38'),(145,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12340300,104.01576900,'2026-04-27 00:51:49','2026-04-27 00:59:20'),(146,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333850,104.01562300,'2026-04-27 01:11:18','2026-04-27 01:19:38'),(147,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333850,104.01562300,'2026-04-27 01:23:43','2026-04-27 01:32:42'),(148,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12329710,104.01571277,'2026-04-27 01:41:40','2026-04-27 01:50:21'),(149,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333850,104.01562300,'2026-04-27 01:52:58','2026-04-27 01:52:58'),(150,'users','login_google',16,'User logged in with Google',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:38:52','2026-04-28 00:38:52'),(151,'users','location_ping',16,'User location update',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333000,104.01553350,'2026-04-28 00:38:56','2026-04-28 00:38:56'),(152,'users','logout',16,'User logged out',NULL,NULL,16,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:38:58','2026-04-28 00:38:58'),(153,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333000,104.01553350,'2026-04-28 00:39:19','2026-04-28 00:39:21'),(154,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12333000,104.01553350,'2026-04-28 00:39:21','2026-04-28 00:39:21'),(155,'users','logout',5,'User logged out',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:40:01','2026-04-28 00:40:01'),(156,'users','login',2,'User logged in',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12330021,104.01566638,'2026-04-28 00:40:12','2026-04-28 00:40:21'),(157,'users','location_ping',2,'User location update',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12330021,104.01566638,'2026-04-28 00:40:16','2026-04-28 00:40:21'),(158,'users','logout',2,'User logged out',NULL,NULL,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:43:42','2026-04-28 00:43:42'),(159,'users','login',4,'User logged in',NULL,NULL,4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12329238,104.01570610,'2026-04-28 00:43:49','2026-04-28 00:44:32'),(160,'users','location_ping',4,'User location update',NULL,NULL,4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12329238,104.01570610,'2026-04-28 00:43:53','2026-04-28 00:44:32'),(161,'users','logout',4,'User logged out',NULL,NULL,4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:45:04','2026-04-28 00:45:04'),(162,'users','login',3,'User logged in',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12328900,104.01576100,'2026-04-28 00:45:10','2026-04-28 00:54:24'),(163,'users','location_ping',3,'User location update',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12328900,104.01576100,'2026-04-28 00:45:13','2026-04-28 00:54:24'),(164,'users','logout',3,'User logged out',NULL,NULL,3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-04-28 00:54:31','2026-04-28 00:54:31'),(165,'users','login',5,'User logged in',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12325264,104.01573057,'2026-04-28 00:54:37','2026-04-28 01:01:22'),(166,'users','location_ping',5,'User location update',NULL,NULL,5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',1.12325264,104.01573057,'2026-04-28 00:54:39','2026-04-28 01:01:22');
/*!40000 ALTER TABLE `edit_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jabatans`
--

DROP TABLE IF EXISTS `jabatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jabatans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatans`
--

LOCK TABLES `jabatans` WRITE;
/*!40000 ALTER TABLE `jabatans` DISABLE KEYS */;
/*!40000 ALTER TABLE `jabatans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `jobs_queue_index` (`queue`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'pcycology',NULL,NULL),(2,'trading',NULL,NULL),(3,'love stotry',NULL,NULL);
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (1,'admin',NULL,NULL),(2,'petugas',NULL,NULL),(3,'peminjam',NULL,NULL),(4,'manager',NULL,NULL),(6,'Super Admin','2026-01-13 18:04:18','2026-01-13 18:04:18');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_otp_codes`
--

DROP TABLE IF EXISTS `login_otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_otp_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_otp_codes_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_otp_codes`
--

LOCK TABLES `login_otp_codes` WRITE;
/*!40000 ALTER TABLE `login_otp_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_otp_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_14_111957_create_penulis_table',1),(5,'2025_09_14_112004_create_penerbit_table',1),(6,'2025_09_16_130231_create_books_table',1),(7,'2025_09_25_054201_add_foto_to_books_table',1),(8,'2025_10_05_105010_create_data_masuk_buku_table',1),(9,'2025_10_05_105016_create_peminjaman_buku_table',1),(10,'2025_10_05_130921_add_level_id_to_users_table',1),(11,'2025_10_10_090228_create_kategori_table',1),(12,'2025_10_11_090542_add_kategori_id_to_books_table',1),(13,'2025_10_14_161302_create_jabatans_table',1),(14,'2025_10_14_161338_create_penjagas_table',1),(15,'2025_10_14_161443_create_peminjams_table',1),(16,'2025_11_05_130937_create_levels_table',1),(17,'2026_01_12_173225_add_verification_code_to_users_table',2),(18,'2026_01_13_042817_update_status_in_peminjaman_buku_table',2),(19,'2026_01_13_063645_create_permissions_table',3),(20,'2026_01_13_063650_create_role_permissions_table',4),(21,'2026_01_13_063653_add_super_admin_level',4),(22,'2026_01_13_073418_add_email_verified_at_to_users_table',4),(23,'2026_01_13_073448_update_existing_users_email_verified_at',4),(24,'2026_01_14_002719_add_soft_deletes_to_books',4),(25,'2026_01_14_013336_create_settings_table',5),(26,'2026_01_14_015406_add_deleted_at_to_users_table',6),(27,'2026_01_14_081603_change_tanggal_columns_to_datetime_in_peminjaman_buku_table',7),(28,'2026_01_14_143029_add_deleted_by_to_tables',8),(29,'2026_01_14_200000_create_user_level_histories_table',9),(30,'2026_01_14_150000_create_edit_histories_table',10),(31,'2026_01_14_160000_add_old_new_values_to_edit_histories_table',11),(32,'2026_01_26_192000_create_password_resets_table',12),(33,'2026_01_26_193500_add_no_hp_to_users_table',13),(34,'2026_01_26_195000_add_location_and_ip_to_edit_histories',14),(35,'2026_01_26_203000_add_file_buku_to_books_table',15),(36,'2026_01_26_221728_add_denda_to_peminjaman_buku_table',16),(37,'2026_01_26_222150_add_whatsapp_to_users_table',17),(38,'2026_01_26_223300_create_request_buku_table',18),(39,'2026_01_26_233826_update_status_enum_add_rusak_hilang_to_peminjaman_buku',19),(40,'2026_01_27_080124_add_fonnte_token_to_settings_table',20),(45,'2026_01_27_083650_create_book_reports_table',21),(46,'2026_02_03_213006_create_menus_table',21),(47,'2026_02_03_213015_create_problem_reports_table',21),(48,'2026_02_03_213025_add_discord_webhook_to_settings_table',21),(49,'2026_04_23_100000_create_notifications_table',22),(50,'2026_04_27_090000_add_branding_fields_to_settings_table',22),(51,'2026_04_27_110000_add_visual_theme_fields_to_settings_table',23),(52,'2026_04_27_120500_add_google_fields_to_users_table',24),(53,'2026_04_28_090000_create_login_otp_codes_table',25);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'info',
  `url` varchar(191) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `notifications_user_id_created_at_index` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman_buku`
--

DROP TABLE IF EXISTS `peminjaman_buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjaman_buku` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `tanggal_pinjam` datetime NOT NULL,
  `tanggal_kembali` datetime DEFAULT NULL,
  `status` enum('pending_pinjam','dipinjam','pending_kembali','dikembalikan','rusak','hilang') DEFAULT 'pending_pinjam',
  `denda` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `peminjaman_buku_book_id_foreign` (`book_id`) USING BTREE,
  KEY `peminjaman_buku_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `peminjaman_buku_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peminjaman_buku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman_buku`
--

LOCK TABLES `peminjaman_buku` WRITE;
/*!40000 ALTER TABLE `peminjaman_buku` DISABLE KEYS */;
INSERT INTO `peminjaman_buku` VALUES (2,2,3,'2025-10-23 00:00:00','2025-10-23 00:00:00','dikembalikan',0,NULL,NULL,NULL,NULL),(8,14,3,'2025-10-31 00:00:00','2025-10-31 00:00:00','dikembalikan',0,NULL,NULL,NULL,NULL),(9,16,3,'2025-10-31 00:00:00','2025-10-31 00:00:00','dikembalikan',0,NULL,NULL,NULL,NULL),(10,10,3,'2025-10-31 00:00:00','2025-10-31 00:00:00','dikembalikan',0,NULL,NULL,NULL,NULL),(11,14,3,'2026-01-13 00:00:00','2026-01-13 00:00:00','dikembalikan',0,'2026-01-13 08:05:26',NULL,NULL,NULL),(13,12,3,'2026-01-13 15:54:35','2026-01-13 23:32:42','dikembalikan',0,'2026-01-13 08:11:07',NULL,NULL,NULL),(14,6,3,'2026-01-13 15:54:21','2026-01-13 23:32:47','dikembalikan',0,'2026-01-13 08:11:27',NULL,NULL,NULL),(15,14,3,'2026-01-13 00:00:00','2026-01-13 23:33:11','dikembalikan',0,'2026-01-13 08:20:56',NULL,NULL,NULL),(16,5,3,'2026-01-13 23:03:54','2026-01-13 23:32:26','dikembalikan',0,'2026-01-13 16:03:12',NULL,NULL,NULL),(17,2,3,'2026-01-13 23:06:33','2026-01-13 23:32:22','dikembalikan',0,'2026-01-13 16:05:44',NULL,NULL,NULL),(18,6,3,'2026-01-13 23:34:36','2026-01-14 00:47:55','dikembalikan',0,'2026-01-13 16:34:13',NULL,NULL,NULL),(19,13,3,'2026-01-13 23:45:12','2026-01-14 00:46:22','dikembalikan',0,'2026-01-13 16:44:43',NULL,NULL,NULL),(23,14,3,'2026-01-14 00:46:38','2026-01-14 00:54:23','dikembalikan',0,'2026-01-13 17:18:07',NULL,'2026-01-13 18:38:46',NULL),(24,13,3,'2026-01-14 01:00:01','2026-01-14 08:09:01','dikembalikan',0,'2026-01-13 17:55:38',NULL,NULL,NULL),(25,14,3,'2026-01-14 00:56:20','2026-01-14 08:09:05','dikembalikan',0,'2026-01-13 17:55:42',NULL,NULL,NULL),(26,1,3,'2026-01-14 07:58:16','2026-01-14 08:08:57','dikembalikan',0,'2026-01-14 00:57:26',NULL,NULL,NULL),(27,16,3,'2026-01-14 08:09:56','2026-01-14 08:20:14','dikembalikan',0,'2026-01-14 01:09:19',NULL,NULL,NULL),(28,14,3,'2026-01-14 08:11:23','2026-01-14 08:20:10','dikembalikan',0,'2026-01-14 01:10:56',NULL,NULL,NULL),(29,13,3,'2026-01-14 08:20:31','2026-01-26 22:22:42','dikembalikan',-5585,'2026-01-14 01:19:43',NULL,NULL,NULL),(30,16,3,'2026-01-14 08:21:50','2026-01-14 08:41:06','dikembalikan',0,'2026-01-14 01:21:18',NULL,NULL,NULL),(31,2,3,'2026-01-14 08:24:47','2026-01-14 08:41:03','dikembalikan',0,'2026-01-14 01:24:40',NULL,NULL,NULL),(32,14,11,'2026-01-14 08:42:05','2026-01-14 08:42:39','dikembalikan',0,'2026-01-14 01:41:52',NULL,NULL,NULL),(37,14,3,'2026-01-26 11:12:18','2026-02-03 22:32:08','dikembalikan',-1000,'2026-01-26 04:11:42',NULL,NULL,NULL),(38,16,3,'2026-01-26 20:25:19','2026-02-03 22:32:04','dikembalikan',-1000,'2026-01-26 13:00:36',NULL,NULL,NULL);
/*!40000 ALTER TABLE `peminjaman_buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjams`
--

DROP TABLE IF EXISTS `peminjams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `peminjams_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `peminjams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjams`
--

LOCK TABLES `peminjams` WRITE;
/*!40000 ALTER TABLE `peminjams` DISABLE KEYS */;
INSERT INTO `peminjams` VALUES (1,4,NULL,NULL,NULL,'2025-10-19 20:27:09','2025-10-19 20:27:09'),(2,5,NULL,NULL,NULL,'2025-10-19 21:12:30','2025-10-19 21:12:30'),(4,7,NULL,NULL,NULL,'2025-10-31 00:38:24','2025-10-31 00:38:24'),(5,8,NULL,NULL,NULL,'2025-10-31 00:50:38','2025-10-31 00:50:38'),(6,9,NULL,NULL,NULL,'2025-10-31 01:02:25','2025-10-31 01:02:25'),(7,10,NULL,NULL,NULL,'2026-01-13 08:50:05','2026-01-13 08:50:05'),(8,11,NULL,NULL,NULL,'2026-01-14 01:32:13','2026-01-14 01:32:13'),(11,14,NULL,NULL,NULL,'2026-01-26 06:23:29','2026-01-26 06:23:29'),(12,15,NULL,NULL,NULL,'2026-01-26 09:52:20','2026-01-26 09:52:20'),(13,16,NULL,NULL,NULL,'2026-02-03 15:38:33','2026-02-03 15:38:33');
/*!40000 ALTER TABLE `peminjams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penerbit`
--

DROP TABLE IF EXISTS `penerbit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penerbit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_penerbit` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerbit`
--

LOCK TABLES `penerbit` WRITE;
/*!40000 ALTER TABLE `penerbit` DISABLE KEYS */;
INSERT INTO `penerbit` VALUES (1,'avs','malas',NULL,NULL),(2,'triv','hahaha',NULL,NULL),(3,'ac','yoo',NULL,NULL);
/*!40000 ALTER TABLE `penerbit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penjagas`
--

DROP TABLE IF EXISTS `penjagas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penjagas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `jabatan_id` bigint(20) unsigned NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `penjagas_user_id_foreign` (`user_id`) USING BTREE,
  KEY `penjagas_jabatan_id_foreign` (`jabatan_id`) USING BTREE,
  CONSTRAINT `penjagas_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`),
  CONSTRAINT `penjagas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjagas`
--

LOCK TABLES `penjagas` WRITE;
/*!40000 ALTER TABLE `penjagas` DISABLE KEYS */;
/*!40000 ALTER TABLE `penjagas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penulis`
--

DROP TABLE IF EXISTS `penulis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penulis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_penulis` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penulis`
--

LOCK TABLES `penulis` WRITE;
/*!40000 ALTER TABLE `penulis` DISABLE KEYS */;
INSERT INTO `penulis` VALUES (1,'Kalimasada','Kalimasada@gmail.com',NULL,NULL),(2,'Timothy Ronald','TimothyRonald@gmail.com',NULL,NULL),(3,'GabrielRey','GabrielRey@gmail.com',NULL,NULL);
/*!40000 ALTER TABLE `penulis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `permissions_name_unique` (`name`) USING BTREE,
  UNIQUE KEY `permissions_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'buku.create','buku-create','buku','create','Tambah data buku','2026-04-28 00:50:10','2026-04-28 00:50:10'),(2,'buku.read','buku-read','buku','read','Lihat data buku','2026-04-28 00:50:10','2026-04-28 00:50:10'),(3,'buku.update','buku-update','buku','update','Edit data buku','2026-04-28 00:50:10','2026-04-28 00:50:10'),(4,'buku.delete','buku-delete','buku','delete','Hapus data buku','2026-04-28 00:50:10','2026-04-28 00:50:10'),(5,'buku-masuk.create','buku-masuk-create','buku-masuk','create','Tambah data buku masuk','2026-04-28 00:50:10','2026-04-28 00:50:10'),(6,'buku-masuk.read','buku-masuk-read','buku-masuk','read','Lihat data buku masuk','2026-04-28 00:50:10','2026-04-28 00:50:10'),(7,'buku-masuk.update','buku-masuk-update','buku-masuk','update','Edit data buku masuk','2026-04-28 00:50:10','2026-04-28 00:50:10'),(8,'buku-masuk.delete','buku-masuk-delete','buku-masuk','delete','Hapus data buku masuk','2026-04-28 00:50:10','2026-04-28 00:50:10'),(9,'peminjaman.create','peminjaman-create','peminjaman','create','Tambah data peminjaman','2026-04-28 00:50:10','2026-04-28 00:50:10'),(10,'peminjaman.read','peminjaman-read','peminjaman','read','Lihat data peminjaman','2026-04-28 00:50:10','2026-04-28 00:50:10'),(11,'peminjaman.update','peminjaman-update','peminjaman','update','Edit data peminjaman','2026-04-28 00:50:10','2026-04-28 00:50:10'),(12,'peminjaman.delete','peminjaman-delete','peminjaman','delete','Hapus data peminjaman','2026-04-28 00:50:10','2026-04-28 00:50:10'),(13,'peminjaman.approve','peminjaman-approve','peminjaman','approve','Setujui peminjaman/pengembalian','2026-04-28 00:50:10','2026-04-28 00:50:10'),(14,'user.create','user-create','user','create','Tambah data user','2026-04-28 00:50:10','2026-04-28 00:50:10'),(15,'user.read','user-read','user','read','Lihat data user','2026-04-28 00:50:10','2026-04-28 00:50:10'),(16,'user.update','user-update','user','update','Edit data user','2026-04-28 00:50:10','2026-04-28 00:50:10'),(17,'user.delete','user-delete','user','delete','Hapus data user','2026-04-28 00:50:10','2026-04-28 00:50:10'),(18,'permission.manage','permission-manage','permission','manage','Kelola hak akses','2026-04-28 00:50:10','2026-04-28 00:50:10'),(19,'laporan.read','laporan-read','laporan','read','Lihat laporan','2026-04-28 00:50:10','2026-04-28 00:50:10'),(20,'laporan.export','laporan-export','laporan','export','Export laporan','2026-04-28 00:50:10','2026-04-28 00:50:10'),(21,'log.read','log-read','log','read','Lihat log aktivitas','2026-04-28 00:50:10','2026-04-28 00:50:10');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem_reports`
--

DROP TABLE IF EXISTS `problem_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `description` text NOT NULL,
  `photo_proof` varchar(191) DEFAULT NULL,
  `status` enum('pending','processed','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `problem_reports_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `problem_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem_reports`
--

LOCK TABLES `problem_reports` WRITE;
/*!40000 ALTER TABLE `problem_reports` DISABLE KEYS */;
INSERT INTO `problem_reports` VALUES (1,3,'buku nya sampulnya sobek',NULL,'pending',NULL,'2026-02-03 15:36:27','2026-02-03 15:36:27');
/*!40000 ALTER TABLE `problem_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_buku`
--

DROP TABLE IF EXISTS `request_buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_buku` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `judul_buku` varchar(191) NOT NULL,
  `penulis` varchar(191) DEFAULT NULL,
  `kategori` varchar(191) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `request_buku_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `request_buku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_buku`
--

LOCK TABLES `request_buku` WRITE;
/*!40000 ALTER TABLE `request_buku` DISABLE KEYS */;
INSERT INTO `request_buku` VALUES (2,3,'hairy potter','nikolas tinja','horror fiction',NULL,'pending',NULL,'2026-02-03 15:33:11','2026-02-03 15:33:11');
/*!40000 ALTER TABLE `request_buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `level_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `role_permissions_level_id_permission_id_unique` (`level_id`,`permission_id`) USING BTREE,
  KEY `role_permissions_permission_id_foreign` (`permission_id`) USING BTREE,
  CONSTRAINT `role_permissions_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,1,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(2,1,2,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(3,1,3,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(4,1,4,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(5,1,5,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(6,1,6,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(7,1,7,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(8,1,8,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(9,1,9,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(10,1,10,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(11,1,11,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(12,1,12,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(13,1,13,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(14,1,14,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(15,1,15,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(16,1,16,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(17,1,17,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(18,1,19,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(19,1,20,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(20,2,1,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(21,2,2,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(22,2,3,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(23,2,4,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(24,2,5,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(25,2,6,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(26,2,7,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(27,2,8,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(28,2,9,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(29,2,10,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(30,2,11,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(31,2,12,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(32,2,13,'2026-01-13 00:43:00','2026-01-13 00:43:00'),(58,6,1,NULL,NULL),(59,6,2,NULL,NULL),(60,6,3,NULL,NULL),(61,6,4,NULL,NULL),(62,6,5,NULL,NULL),(63,6,6,NULL,NULL),(64,6,7,NULL,NULL),(65,6,8,NULL,NULL),(66,6,9,NULL,NULL),(67,6,10,NULL,NULL),(68,6,11,NULL,NULL),(69,6,12,NULL,NULL),(70,6,13,NULL,NULL),(71,6,14,NULL,NULL),(72,6,15,NULL,NULL),(73,6,16,NULL,NULL),(74,6,17,NULL,NULL),(75,6,18,NULL,NULL),(76,6,19,NULL,NULL),(77,6,20,NULL,NULL),(79,6,21,'2026-01-26 12:35:13','2026-01-26 12:35:13'),(80,1,21,'2026-01-26 12:35:13','2026-01-26 12:35:13'),(81,4,20,'2026-04-28 00:50:10','2026-04-28 00:50:10'),(82,4,19,'2026-04-28 00:50:10','2026-04-28 00:50:10');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(191) NOT NULL DEFAULT 'Perpustakaan Kekinian',
  `logo` varchar(191) DEFAULT NULL,
  `manager_name` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_info` varchar(191) DEFAULT NULL,
  `theme_primary_color` varchar(20) DEFAULT NULL,
  `theme_secondary_color` varchar(20) DEFAULT NULL,
  `app_background_color` varchar(20) DEFAULT NULL,
  `background_overlay_opacity` decimal(3,2) DEFAULT NULL,
  `sidebar_bg_color` varchar(20) DEFAULT NULL,
  `topbar_bg_color` varchar(20) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `background_image` varchar(191) DEFAULT NULL,
  `fonnte_token` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discord_webhook` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Perpustakaan Digital','settings/rjoma9WbhkTRzzqVX305SCn9n57YT0YaUCCxrqdo.png','Admin Utama','Jalan Gadjah Mada No. 123, Batam','021-12345678','#837aff','#3730a3','#0f172a',0.88,'#0f172a','#0f172a',NULL,NULL,NULL,'2026-01-13 18:57:38','2026-04-27 01:42:04',NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_level_histories`
--

DROP TABLE IF EXISTS `user_level_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_level_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `old_level_id` bigint(20) unsigned DEFAULT NULL,
  `new_level_id` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_level_histories`
--

LOCK TABLES `user_level_histories` WRITE;
/*!40000 ALTER TABLE `user_level_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_level_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `whatsapp` varchar(191) DEFAULT NULL,
  `no_hp` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `code_expires_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  UNIQUE KEY `users_google_id_unique` (`google_id`),
  KEY `users_level_id_foreign` (`level_id`) USING BTREE,
  CONSTRAINT `users_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'aaa','a@gmail.com',NULL,NULL,NULL,NULL,'2026-01-13 13:39:40','$2y$12$lJHewKBNzu2egwA2jEHejOuY4uF4qpxBbzWONM6ya5PQdaQE3LsqW',1,'2025-10-01 07:55:12','2026-01-14 13:19:26',NULL,NULL,NULL,5),(2,'b','b@gmail.com',NULL,NULL,NULL,NULL,'2026-01-13 13:39:43','$2y$12$nZQMZNN1hperF7vi8JTcmeggIza8L9.W7UZgmni7xUaTCbOWgCIom',2,'2025-11-01 07:55:21','2025-10-24 01:21:04',NULL,NULL,NULL,5),(3,'c','c@gmail.com',NULL,NULL,NULL,NULL,'2026-01-13 13:39:47','$2y$12$kVozzpeytnXyt1Hfws31b.fJb6u5lBpFsl7vu7wi/vLz8r5u4KGra',3,'2025-10-01 07:55:25','2026-01-13 07:55:08',NULL,NULL,NULL,NULL),(4,'d','d@gmail.com',NULL,NULL,NULL,NULL,'2026-01-13 13:39:51','$2y$12$WCzQml8.E4EzvJRUFClqI.nf7ZJHlRca2DMxqyMif53TGX/h5GYZO',4,'2025-10-19 20:27:09','2025-10-19 20:27:09',NULL,NULL,NULL,NULL),(5,'e','e@gmail.com',NULL,NULL,'082386338889',NULL,'2026-01-13 13:52:08','$2y$12$faXjqjE1CP7M/qfD0Ka1uuSQorjSvpTqNcW7t9y/WjLhklh.TVmue',6,'2025-10-19 21:12:30','2026-02-03 14:10:56',NULL,NULL,NULL,NULL),(7,'jonathan','jonathan@gmail.com',NULL,NULL,NULL,NULL,'2025-10-31 00:38:24','jonathan',3,'2025-10-31 00:38:24','2025-10-31 00:38:24',NULL,NULL,NULL,NULL),(8,'james','james@gmail.com',NULL,NULL,NULL,NULL,'2025-10-31 00:50:38','123',3,'2025-10-31 00:50:38','2025-10-31 00:50:38',NULL,NULL,NULL,NULL),(9,'terserah','terserah@gmail.com',NULL,NULL,NULL,NULL,'2025-10-31 01:02:25','terserrah',3,'2025-10-31 01:02:25','2025-10-31 01:02:25',NULL,NULL,NULL,NULL),(10,'a','jovian2010gntx@gmail.com',NULL,NULL,NULL,'082386338889','2026-01-13 08:50:05','$2y$12$1IZ9FbQSHmkh51B/6wehEeKUv0V1eCu4iIRI5TdWYFwBYmIpDNQEq',3,'2026-01-13 08:50:05','2026-01-13 08:50:05','p2WEHYQAR26we1MC2ijyC2OaHr39OMmspj2GPrhGyjwwBdyEzuhfdyFWUFaXVkL7','2026-01-14 08:50:05','2026-01-13 19:04:38',NULL),(11,'Johny','floatycandy@gmail.com',NULL,NULL,NULL,NULL,'2026-01-14 01:33:00','$2y$12$IQG0xi4CQVduWseYkVJx8eIEP6KvJUFzGEmlbUkn4Qv0dftgjqpuq',3,'2026-01-14 01:32:13','2026-01-14 01:33:00',NULL,NULL,NULL,NULL),(14,'aaaaa','aaaa@gmail.com',NULL,NULL,NULL,NULL,NULL,'$2y$12$1uCnO5E1fL0yIGN2OZOMMOmzlUhRRgRHqqE7Hc90KkLyePUjX18p2',3,'2026-01-26 06:23:29','2026-01-26 06:23:29','H9K8B48dLQcwJK2Xhqg5qYmwoGnP1fG7QTJ57d5ZIPRBggGFu6pDhgpXjqWhXrNa','2026-01-27 06:23:29',NULL,NULL),(15,'aaaaaaa','bebeb@gmail.com',NULL,NULL,NULL,NULL,NULL,'$2y$12$.cRnQ5lLoKB5/sOTHcFwO.iYdmEJoOzogEvrfFaNzAQbNxw9V/dqG',3,'2026-01-26 09:52:20','2026-01-26 09:52:20','cC4zFOM6chidyj1duT8RxAFGk6d1NHhFfiBBaVPNEyuAFOJU9oEkZy99ogzOadOM','2026-01-27 09:52:20',NULL,NULL),(16,'yanto','jovianvian12@gmail.com','115141974356074914567','https://lh3.googleusercontent.com/a/ACg8ocKDXQIjTYFElj7PgUoWACnC0ZwcJIO1SEg8pradAsnMZKSLpmo=s96-c',NULL,NULL,'2026-02-03 15:38:58','$2y$12$1Ywy0J.9yMJT6vCUTSuGkOaVGAWXPI1UhQNO9RCYzACR4CGK/T2IO',3,'2026-02-03 15:38:33','2026-04-28 00:38:52',NULL,NULL,NULL,NULL);
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

-- Dump completed on 2026-04-28  8:14:55
