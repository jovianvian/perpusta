/*
 Navicat Premium Dump SQL

 Source Server         : jo
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : perpustakaan

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 26/02/2026 07:05:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for book_reports
-- ----------------------------
DROP TABLE IF EXISTS `book_reports`;
CREATE TABLE `book_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `issue_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','reviewed','resolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `book_reports_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `book_reports_book_id_foreign`(`book_id` ASC) USING BTREE,
  CONSTRAINT `book_reports_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `book_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of book_reports
-- ----------------------------

-- ----------------------------
-- Table structure for books
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis_id` bigint UNSIGNED NOT NULL,
  `penerbit_id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NULL DEFAULT NULL,
  `tahun` year NOT NULL,
  `stok` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_buku` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Path file PDF E-Book',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `books_penulis_id_foreign`(`penulis_id` ASC) USING BTREE,
  INDEX `books_penerbit_id_foreign`(`penerbit_id` ASC) USING BTREE,
  INDEX `books_kategori_id_foreign`(`kategori_id` ASC) USING BTREE,
  CONSTRAINT `books_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `books_penerbit_id_foreign` FOREIGN KEY (`penerbit_id`) REFERENCES `penerbit` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `books_penulis_id_foreign` FOREIGN KEY (`penulis_id`) REFERENCES `penulis` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of books
-- ----------------------------
INSERT INTO `books` VALUES (1, 'ligma', 3, 2, 1, 2025, 111121110, 'covers/zughivUOJjOInB3qdss7XIEf3URA0uJYdDnsf9mL.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (2, 'Fibonnaci', 1, 3, 2, 2000, 101000, 'covers/d7UZbrYYCTTmcECuBitqiEUPBq6W87vEsSndd9px.jpg', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (5, 'Fibonnaci', 1, 3, 1, 1999, 1000, NULL, NULL, NULL, NULL, '2026-01-14 01:49:36', NULL);
INSERT INTO `books` VALUES (6, 'money psycology', 1, 1, 1, 1999, 1009999, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (10, 'jjj', 3, 2, 2, 2000, 100, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (12, 'b', 2, 1, 2, 2000, 999, NULL, NULL, NULL, NULL, NULL, 5);
INSERT INTO `books` VALUES (13, 'jovianloverachelkeithlin', 3, 3, 1, 2000, 999, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (14, 'angelamerindukanlionels', 3, 1, 2, 2000, 2002, 'covers/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg', 'ebooks/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg', NULL, NULL, NULL, NULL);
INSERT INTO `books` VALUES (16, 'rikardobermalamdengannatalia', 2, 1, 3, 2025, 11, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for data_masuk_buku
-- ----------------------------
DROP TABLE IF EXISTS `data_masuk_buku`;
CREATE TABLE `data_masuk_buku`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `book_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `data_masuk_buku_book_id_foreign`(`book_id` ASC) USING BTREE,
  CONSTRAINT `data_masuk_buku_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of data_masuk_buku
-- ----------------------------
INSERT INTO `data_masuk_buku` VALUES (1, 2, 100, '2025-10-22', NULL, NULL, NULL, NULL);
INSERT INTO `data_masuk_buku` VALUES (2, 2, 100000, '2025-10-27', NULL, NULL, NULL, NULL);
INSERT INTO `data_masuk_buku` VALUES (3, 16, 10, '2025-10-31', NULL, NULL, NULL, NULL);
INSERT INTO `data_masuk_buku` VALUES (4, 1, 111111111, '2026-01-14', NULL, NULL, NULL, NULL);
INSERT INTO `data_masuk_buku` VALUES (5, 6, 1000000, '2026-01-14', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for edit_histories
-- ----------------------------
DROP TABLE IF EXISTS `edit_histories`;
CREATE TABLE `edit_histories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `row_id` bigint UNSIGNED NOT NULL,
  `perubahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `old_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `new_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `edited_by` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` decimal(10, 8) NULL DEFAULT NULL,
  `longitude` decimal(11, 8) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 125 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of edit_histories
-- ----------------------------
INSERT INTO `edit_histories` VALUES (1, 'users', NULL, 1, 'Data user diperbarui', NULL, NULL, 5, NULL, NULL, NULL, NULL, '2026-01-14 20:05:36', '2026-01-14 20:05:36');
INSERT INTO `edit_histories` VALUES (2, 'users', NULL, 1, 'Data user diperbarui', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', '{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":\"1\"}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:19:26', '2026-01-14 20:19:26');
INSERT INTO `edit_histories` VALUES (3, 'users', NULL, 1, 'Revert ke versi sebelumnya', '{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:19:32', '2026-01-14 20:19:32');
INSERT INTO `edit_histories` VALUES (4, 'users', NULL, 1, 'Revert ke versi sebelumnya', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', '{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:19:52', '2026-01-14 20:19:52');
INSERT INTO `edit_histories` VALUES (5, 'users', NULL, 1, 'Revert ke versi sebelumnya', '{\"name\":\"a\",\"email\":\"a@gmail.com\",\"level_id\":1}', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:20:04', '2026-01-14 20:20:04');
INSERT INTO `edit_histories` VALUES (6, 'users', NULL, 1, 'Revert ke versi sebelumnya', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', '{\"name\":\"aaa\",\"email\":\"a@gmail.com\",\"level_id\":1}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:20:29', '2026-01-14 20:20:29');
INSERT INTO `edit_histories` VALUES (7, 'books', NULL, 12, 'Data buku diperbarui', '{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', '{\"judul\":\"a\",\"penulis_id\":\"2\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"999\"}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:20:52', '2026-01-14 20:20:52');
INSERT INTO `edit_histories` VALUES (8, 'books', NULL, 12, 'Revert ke versi sebelumnya', '{\"judul\":\"a\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', '{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:23:49', '2026-01-14 20:23:49');
INSERT INTO `edit_histories` VALUES (9, 'books', NULL, 14, 'Judul: angelamerindukanlionel → angelamerindukansc', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}', '{\"judul\":\"angelamerindukansc\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2002\"}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:35:10', '2026-01-14 20:35:10');
INSERT INTO `edit_histories` VALUES (10, 'books', NULL, 14, 'Revert ke versi sebelumnya', '{\"judul\":\"angelamerindukansc\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2002,\"foto\":null}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:35:24', '2026-01-14 20:35:24');
INSERT INTO `edit_histories` VALUES (11, 'books', NULL, 12, 'Judul: b → a', '{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', '{\"judul\":\"a\",\"penulis_id\":\"2\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"999\"}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:41:49', '2026-01-14 20:41:49');
INSERT INTO `edit_histories` VALUES (12, 'books', NULL, 12, 'Revert: Judul: a → b', '{\"judul\":\"a\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', '{\"judul\":\"b\",\"penulis_id\":2,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":999,\"foto\":null}', 5, NULL, NULL, NULL, NULL, '2026-01-14 20:41:58', '2026-01-14 20:41:58');
INSERT INTO `edit_histories` VALUES (13, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 19:32:07', '2026-01-26 19:32:07');
INSERT INTO `edit_histories` VALUES (14, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 19:41:13', '2026-01-26 19:41:13');
INSERT INTO `edit_histories` VALUES (15, 'users', 'login', 2, 'User logged in', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 19:41:23', '2026-01-26 19:41:23');
INSERT INTO `edit_histories` VALUES (16, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 19:41:40', '2026-01-26 19:41:40');
INSERT INTO `edit_histories` VALUES (17, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 19:59:50', '2026-01-26 19:59:50');
INSERT INTO `edit_histories` VALUES (18, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:00:00', '2026-01-26 20:00:00');
INSERT INTO `edit_histories` VALUES (19, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:18:56', '2026-01-26 20:18:56');
INSERT INTO `edit_histories` VALUES (20, 'users', 'login', 2, 'User logged in', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:19:03', '2026-01-26 20:19:03');
INSERT INTO `edit_histories` VALUES (21, 'users', 'logout', 2, 'User logged out', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:25:31', '2026-01-26 20:25:31');
INSERT INTO `edit_histories` VALUES (22, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:25:39', '2026-01-26 20:25:39');
INSERT INTO `edit_histories` VALUES (23, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:25:46', '2026-01-26 20:25:46');
INSERT INTO `edit_histories` VALUES (24, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:25:53', '2026-01-26 20:25:53');
INSERT INTO `edit_histories` VALUES (25, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:26:28', '2026-01-26 20:26:28');
INSERT INTO `edit_histories` VALUES (26, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 20:26:35', '2026-01-26 20:26:35');
INSERT INTO `edit_histories` VALUES (27, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 21:37:25', '2026-01-26 21:37:25');
INSERT INTO `edit_histories` VALUES (28, 'users', 'login', 10, 'User logged in', NULL, NULL, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 21:46:41', '2026-01-26 21:46:41');
INSERT INTO `edit_histories` VALUES (29, 'users', 'login', 10, 'User logged in', NULL, NULL, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 21:55:36', '2026-01-26 21:55:36');
INSERT INTO `edit_histories` VALUES (30, 'users', 'logout', 10, 'User logged out', NULL, NULL, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 21:55:39', '2026-01-26 21:55:39');
INSERT INTO `edit_histories` VALUES (31, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:09:54', '2026-01-26 22:09:54');
INSERT INTO `edit_histories` VALUES (32, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:19:48', '2026-01-26 22:19:48');
INSERT INTO `edit_histories` VALUES (33, 'users', 'login', 2, 'User logged in', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:20:01', '2026-01-26 22:20:01');
INSERT INTO `edit_histories` VALUES (34, 'users', 'logout', 2, 'User logged out', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:22:56', '2026-01-26 22:22:56');
INSERT INTO `edit_histories` VALUES (35, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:23:06', '2026-01-26 22:23:06');
INSERT INTO `edit_histories` VALUES (36, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:36:12', '2026-01-26 22:36:12');
INSERT INTO `edit_histories` VALUES (37, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:36:19', '2026-01-26 22:36:19');
INSERT INTO `edit_histories` VALUES (38, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:41:18', '2026-01-26 22:41:18');
INSERT INTO `edit_histories` VALUES (39, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:41:25', '2026-01-26 22:41:25');
INSERT INTO `edit_histories` VALUES (40, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:43:12', '2026-01-26 22:43:12');
INSERT INTO `edit_histories` VALUES (41, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:43:35', '2026-01-26 22:43:35');
INSERT INTO `edit_histories` VALUES (42, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:45:02', '2026-01-26 22:45:02');
INSERT INTO `edit_histories` VALUES (43, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 22:48:17', '2026-01-26 22:48:17');
INSERT INTO `edit_histories` VALUES (44, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:10:52', '2026-01-26 23:10:52');
INSERT INTO `edit_histories` VALUES (45, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:11:08', '2026-01-26 23:11:08');
INSERT INTO `edit_histories` VALUES (46, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:11:25', '2026-01-26 23:11:25');
INSERT INTO `edit_histories` VALUES (47, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:13:51', '2026-01-26 23:13:51');
INSERT INTO `edit_histories` VALUES (48, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:14:11', '2026-01-26 23:14:11');
INSERT INTO `edit_histories` VALUES (49, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:14:23', '2026-01-26 23:14:23');
INSERT INTO `edit_histories` VALUES (50, 'books', 'update', 14, 'Foto: diubah; File E-Book: diubah', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":null,\"file_buku\":null}', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\",\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}', 5, NULL, NULL, NULL, NULL, '2026-01-26 23:16:57', '2026-01-26 23:16:57');
INSERT INTO `edit_histories` VALUES (51, 'books', 'update', 14, 'Data buku diperbarui', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\"}', 5, NULL, NULL, NULL, NULL, '2026-01-26 23:17:24', '2026-01-26 23:17:24');
INSERT INTO `edit_histories` VALUES (52, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:17:36', '2026-01-26 23:17:36');
INSERT INTO `edit_histories` VALUES (53, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:17:44', '2026-01-26 23:17:44');
INSERT INTO `edit_histories` VALUES (54, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:18:11', '2026-01-26 23:18:11');
INSERT INTO `edit_histories` VALUES (55, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:24:55', '2026-01-26 23:24:55');
INSERT INTO `edit_histories` VALUES (56, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:27:17', '2026-01-26 23:27:17');
INSERT INTO `edit_histories` VALUES (57, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:35:47', '2026-01-26 23:35:47');
INSERT INTO `edit_histories` VALUES (58, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-26 23:35:54', '2026-01-26 23:35:54');
INSERT INTO `edit_histories` VALUES (59, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-27 00:18:40', '2026-01-27 00:18:40');
INSERT INTO `edit_histories` VALUES (60, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-01-27 07:45:45', '2026-01-27 07:45:45');
INSERT INTO `edit_histories` VALUES (61, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 20:59:35', '2026-02-03 20:59:35');
INSERT INTO `edit_histories` VALUES (62, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 21:45:56', '2026-02-03 21:45:56');
INSERT INTO `edit_histories` VALUES (63, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 21:46:03', '2026-02-03 21:46:03');
INSERT INTO `edit_histories` VALUES (64, 'books', 'update', 14, 'Judul: angelamerindukanlionel → angelamerindukanlionels', '{\"judul\":\"angelamerindukanlionel\",\"penulis_id\":3,\"penerbit_id\":1,\"tahun\":\"2000\",\"kategori_id\":2,\"stok\":2001,\"foto\":\"covers\\/WYTwXp88IvTnyo1PFUhxMBI67HqMY5XKaNxkrtfB.jpg\",\"file_buku\":\"ebooks\\/4X3AbDOstNJZWKJEFgI02fTiQlyxnvQEZSLSMQUl.jpg\"}', '{\"judul\":\"angelamerindukanlionels\",\"penulis_id\":\"3\",\"penerbit_id\":\"1\",\"tahun\":\"2000\",\"kategori_id\":\"2\",\"stok\":\"2001\"}', 5, NULL, NULL, NULL, NULL, '2026-02-03 22:10:15', '2026-02-03 22:10:15');
INSERT INTO `edit_histories` VALUES (65, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:12:37', '2026-02-03 22:12:37');
INSERT INTO `edit_histories` VALUES (66, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:12:45', '2026-02-03 22:12:45');
INSERT INTO `edit_histories` VALUES (67, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:21:00', '2026-02-03 22:21:00');
INSERT INTO `edit_histories` VALUES (68, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:21:07', '2026-02-03 22:24:18');
INSERT INTO `edit_histories` VALUES (69, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:24:18', '2026-02-03 22:24:18');
INSERT INTO `edit_histories` VALUES (70, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:24:42', '2026-02-03 22:24:42');
INSERT INTO `edit_histories` VALUES (71, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:24:49', '2026-02-03 22:26:20');
INSERT INTO `edit_histories` VALUES (72, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:25:02', '2026-02-03 22:25:02');
INSERT INTO `edit_histories` VALUES (73, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:26:20', '2026-02-03 22:26:20');
INSERT INTO `edit_histories` VALUES (74, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:27:43', '2026-02-03 22:27:43');
INSERT INTO `edit_histories` VALUES (75, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:30:31', '2026-02-03 22:30:42');
INSERT INTO `edit_histories` VALUES (76, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:30:42', '2026-02-03 22:30:42');
INSERT INTO `edit_histories` VALUES (77, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:30:55', '2026-02-03 22:30:55');
INSERT INTO `edit_histories` VALUES (78, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:31:04', '2026-02-03 22:31:18');
INSERT INTO `edit_histories` VALUES (79, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:31:18', '2026-02-03 22:31:18');
INSERT INTO `edit_histories` VALUES (80, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:31:44', '2026-02-03 22:31:44');
INSERT INTO `edit_histories` VALUES (81, 'users', 'login', 2, 'User logged in', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:31:52', '2026-02-03 22:31:52');
INSERT INTO `edit_histories` VALUES (82, 'users', 'logout', 2, 'User logged out', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:32:14', '2026-02-03 22:32:14');
INSERT INTO `edit_histories` VALUES (83, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:32:20', '2026-02-03 22:33:21');
INSERT INTO `edit_histories` VALUES (84, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:32:35', '2026-02-03 22:32:35');
INSERT INTO `edit_histories` VALUES (85, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:32:49', '2026-02-03 22:32:49');
INSERT INTO `edit_histories` VALUES (86, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:33:21', '2026-02-03 22:33:21');
INSERT INTO `edit_histories` VALUES (87, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:33:33', '2026-02-03 22:33:33');
INSERT INTO `edit_histories` VALUES (88, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:33:41', '2026-02-03 22:34:09');
INSERT INTO `edit_histories` VALUES (89, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:33:52', '2026-02-03 22:33:52');
INSERT INTO `edit_histories` VALUES (90, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:34:09', '2026-02-03 22:34:09');
INSERT INTO `edit_histories` VALUES (91, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:34:35', '2026-02-03 22:34:35');
INSERT INTO `edit_histories` VALUES (92, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:34:41', '2026-02-03 22:36:19');
INSERT INTO `edit_histories` VALUES (93, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:34:59', '2026-02-03 22:34:59');
INSERT INTO `edit_histories` VALUES (94, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:35:00', '2026-02-03 22:35:00');
INSERT INTO `edit_histories` VALUES (95, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:36:08', '2026-02-03 22:36:08');
INSERT INTO `edit_histories` VALUES (96, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:36:19', '2026-02-03 22:36:19');
INSERT INTO `edit_histories` VALUES (97, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:36:34', '2026-02-03 22:36:34');
INSERT INTO `edit_histories` VALUES (98, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:36:41', '2026-02-03 22:37:03');
INSERT INTO `edit_histories` VALUES (99, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:37:03', '2026-02-03 22:37:03');
INSERT INTO `edit_histories` VALUES (100, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:37:05', '2026-02-03 22:37:05');
INSERT INTO `edit_histories` VALUES (101, 'users', 'login', 3, 'User logged in', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:37:19', '2026-02-03 22:37:33');
INSERT INTO `edit_histories` VALUES (102, 'users', 'location_ping', 3, 'User location update', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:37:33', '2026-02-03 22:37:33');
INSERT INTO `edit_histories` VALUES (103, 'users', 'logout', 3, 'User logged out', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:37:42', '2026-02-03 22:37:42');
INSERT INTO `edit_histories` VALUES (104, 'users', 'login', 16, 'User logged in', NULL, NULL, 16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:39:14', '2026-02-03 22:39:14');
INSERT INTO `edit_histories` VALUES (105, 'users', 'logout', 16, 'User logged out', NULL, NULL, 16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:39:20', '2026-02-03 22:39:20');
INSERT INTO `edit_histories` VALUES (106, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:39:37', '2026-02-03 22:39:37');
INSERT INTO `edit_histories` VALUES (107, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:39:54', '2026-02-03 22:39:54');
INSERT INTO `edit_histories` VALUES (108, 'users', 'login', 4, 'User logged in', NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:40:02', '2026-02-03 22:40:02');
INSERT INTO `edit_histories` VALUES (109, 'users', 'logout', 4, 'User logged out', NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:40:35', '2026-02-03 22:40:35');
INSERT INTO `edit_histories` VALUES (110, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:40:41', '2026-02-03 22:44:13');
INSERT INTO `edit_histories` VALUES (111, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:40:56', '2026-02-03 22:40:56');
INSERT INTO `edit_histories` VALUES (112, 'books', 'delete', 12, 'Buku dihapus (Soft Delete)', NULL, NULL, 5, NULL, NULL, NULL, NULL, '2026-02-03 22:41:13', '2026-02-03 22:41:13');
INSERT INTO `edit_histories` VALUES (113, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:41:25', '2026-02-03 22:41:25');
INSERT INTO `edit_histories` VALUES (114, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:41:44', '2026-02-03 22:41:44');
INSERT INTO `edit_histories` VALUES (115, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:42:22', '2026-02-03 22:42:22');
INSERT INTO `edit_histories` VALUES (116, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:42:51', '2026-02-03 22:42:51');
INSERT INTO `edit_histories` VALUES (117, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:43:39', '2026-02-03 22:43:39');
INSERT INTO `edit_histories` VALUES (118, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:44:13', '2026-02-03 22:44:13');
INSERT INTO `edit_histories` VALUES (119, 'users', 'logout', 5, 'User logged out', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:44:19', '2026-02-03 22:44:19');
INSERT INTO `edit_histories` VALUES (120, 'users', 'login', 16, 'User logged in', NULL, NULL, 16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, NULL, '2026-02-03 22:45:04', '2026-02-03 22:45:04');
INSERT INTO `edit_histories` VALUES (121, 'users', 'login', 5, 'User logged in', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:45:33', '2026-02-03 22:48:22');
INSERT INTO `edit_histories` VALUES (122, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:45:46', '2026-02-03 22:45:46');
INSERT INTO `edit_histories` VALUES (123, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:47:30', '2026-02-03 22:47:30');
INSERT INTO `edit_histories` VALUES (124, 'users', 'location_ping', 5, 'User location update', NULL, NULL, 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1.02650000, 103.92590000, '2026-02-03 22:48:22', '2026-02-03 22:48:22');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jabatans
-- ----------------------------
DROP TABLE IF EXISTS `jabatans`;
CREATE TABLE `jabatans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jabatans
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for kategori
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES (1, 'pcycology', NULL, NULL);
INSERT INTO `kategori` VALUES (2, 'trading', NULL, NULL);
INSERT INTO `kategori` VALUES (3, 'love stotry', NULL, NULL);

-- ----------------------------
-- Table structure for levels
-- ----------------------------
DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of levels
-- ----------------------------
INSERT INTO `levels` VALUES (1, 'admin', NULL, NULL);
INSERT INTO `levels` VALUES (2, 'petugas', NULL, NULL);
INSERT INTO `levels` VALUES (3, 'peminjam', NULL, NULL);
INSERT INTO `levels` VALUES (4, 'manager', NULL, NULL);
INSERT INTO `levels` VALUES (6, 'Super Admin', '2026-01-14 01:04:18', '2026-01-14 01:04:18');

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menus
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2025_09_14_111957_create_penulis_table', 1);
INSERT INTO `migrations` VALUES (5, '2025_09_14_112004_create_penerbit_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_09_16_130231_create_books_table', 1);
INSERT INTO `migrations` VALUES (7, '2025_09_25_054201_add_foto_to_books_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_10_05_105010_create_data_masuk_buku_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_10_05_105016_create_peminjaman_buku_table', 1);
INSERT INTO `migrations` VALUES (10, '2025_10_05_130921_add_level_id_to_users_table', 1);
INSERT INTO `migrations` VALUES (11, '2025_10_10_090228_create_kategori_table', 1);
INSERT INTO `migrations` VALUES (12, '2025_10_11_090542_add_kategori_id_to_books_table', 1);
INSERT INTO `migrations` VALUES (13, '2025_10_14_161302_create_jabatans_table', 1);
INSERT INTO `migrations` VALUES (14, '2025_10_14_161338_create_penjagas_table', 1);
INSERT INTO `migrations` VALUES (15, '2025_10_14_161443_create_peminjams_table', 1);
INSERT INTO `migrations` VALUES (16, '2025_11_05_130937_create_levels_table', 1);
INSERT INTO `migrations` VALUES (17, '2026_01_12_173225_add_verification_code_to_users_table', 2);
INSERT INTO `migrations` VALUES (18, '2026_01_13_042817_update_status_in_peminjaman_buku_table', 2);
INSERT INTO `migrations` VALUES (19, '2026_01_13_063645_create_permissions_table', 3);
INSERT INTO `migrations` VALUES (20, '2026_01_13_063650_create_role_permissions_table', 4);
INSERT INTO `migrations` VALUES (21, '2026_01_13_063653_add_super_admin_level', 4);
INSERT INTO `migrations` VALUES (22, '2026_01_13_073418_add_email_verified_at_to_users_table', 4);
INSERT INTO `migrations` VALUES (23, '2026_01_13_073448_update_existing_users_email_verified_at', 4);
INSERT INTO `migrations` VALUES (24, '2026_01_14_002719_add_soft_deletes_to_books', 4);
INSERT INTO `migrations` VALUES (25, '2026_01_14_013336_create_settings_table', 5);
INSERT INTO `migrations` VALUES (26, '2026_01_14_015406_add_deleted_at_to_users_table', 6);
INSERT INTO `migrations` VALUES (27, '2026_01_14_081603_change_tanggal_columns_to_datetime_in_peminjaman_buku_table', 7);
INSERT INTO `migrations` VALUES (28, '2026_01_14_143029_add_deleted_by_to_tables', 8);
INSERT INTO `migrations` VALUES (29, '2026_01_14_200000_create_user_level_histories_table', 9);
INSERT INTO `migrations` VALUES (30, '2026_01_14_150000_create_edit_histories_table', 10);
INSERT INTO `migrations` VALUES (31, '2026_01_14_160000_add_old_new_values_to_edit_histories_table', 11);
INSERT INTO `migrations` VALUES (32, '2026_01_26_192000_create_password_resets_table', 12);
INSERT INTO `migrations` VALUES (33, '2026_01_26_193500_add_no_hp_to_users_table', 13);
INSERT INTO `migrations` VALUES (34, '2026_01_26_195000_add_location_and_ip_to_edit_histories', 14);
INSERT INTO `migrations` VALUES (35, '2026_01_26_203000_add_file_buku_to_books_table', 15);
INSERT INTO `migrations` VALUES (36, '2026_01_26_221728_add_denda_to_peminjaman_buku_table', 16);
INSERT INTO `migrations` VALUES (37, '2026_01_26_222150_add_whatsapp_to_users_table', 17);
INSERT INTO `migrations` VALUES (38, '2026_01_26_223300_create_request_buku_table', 18);
INSERT INTO `migrations` VALUES (39, '2026_01_26_233826_update_status_enum_add_rusak_hilang_to_peminjaman_buku', 19);
INSERT INTO `migrations` VALUES (40, '2026_01_27_080124_add_fonnte_token_to_settings_table', 20);
INSERT INTO `migrations` VALUES (45, '2026_01_27_083650_create_book_reports_table', 21);
INSERT INTO `migrations` VALUES (46, '2026_02_03_213006_create_menus_table', 21);
INSERT INTO `migrations` VALUES (47, '2026_02_03_213015_create_problem_reports_table', 21);
INSERT INTO `migrations` VALUES (48, '2026_02_03_213025_add_discord_webhook_to_settings_table', 21);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for peminjaman_buku
-- ----------------------------
DROP TABLE IF EXISTS `peminjaman_buku`;
CREATE TABLE `peminjaman_buku`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `book_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` datetime NOT NULL,
  `tanggal_kembali` datetime NULL DEFAULT NULL,
  `status` enum('pending_pinjam','dipinjam','pending_kembali','dikembalikan','rusak','hilang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending_pinjam',
  `denda` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `peminjaman_buku_book_id_foreign`(`book_id` ASC) USING BTREE,
  INDEX `peminjaman_buku_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `peminjaman_buku_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `peminjaman_buku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of peminjaman_buku
-- ----------------------------
INSERT INTO `peminjaman_buku` VALUES (2, 2, 3, '2025-10-23 00:00:00', '2025-10-23 00:00:00', 'dikembalikan', 0, NULL, NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (8, 14, 3, '2025-10-31 00:00:00', '2025-10-31 00:00:00', 'dikembalikan', 0, NULL, NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (9, 16, 3, '2025-10-31 00:00:00', '2025-10-31 00:00:00', 'dikembalikan', 0, NULL, NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (10, 10, 3, '2025-10-31 00:00:00', '2025-10-31 00:00:00', 'dikembalikan', 0, NULL, NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (11, 14, 3, '2026-01-13 00:00:00', '2026-01-13 00:00:00', 'dikembalikan', 0, '2026-01-13 15:05:26', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (13, 12, 3, '2026-01-13 15:54:35', '2026-01-13 23:32:42', 'dikembalikan', 0, '2026-01-13 15:11:07', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (14, 6, 3, '2026-01-13 15:54:21', '2026-01-13 23:32:47', 'dikembalikan', 0, '2026-01-13 15:11:27', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (15, 14, 3, '2026-01-13 00:00:00', '2026-01-13 23:33:11', 'dikembalikan', 0, '2026-01-13 15:20:56', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (16, 5, 3, '2026-01-13 23:03:54', '2026-01-13 23:32:26', 'dikembalikan', 0, '2026-01-13 23:03:12', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (17, 2, 3, '2026-01-13 23:06:33', '2026-01-13 23:32:22', 'dikembalikan', 0, '2026-01-13 23:05:44', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (18, 6, 3, '2026-01-13 23:34:36', '2026-01-14 00:47:55', 'dikembalikan', 0, '2026-01-13 23:34:13', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (19, 13, 3, '2026-01-13 23:45:12', '2026-01-14 00:46:22', 'dikembalikan', 0, '2026-01-13 23:44:43', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (23, 14, 3, '2026-01-14 00:46:38', '2026-01-14 00:54:23', 'dikembalikan', 0, '2026-01-14 00:18:07', NULL, '2026-01-14 01:38:46', NULL);
INSERT INTO `peminjaman_buku` VALUES (24, 13, 3, '2026-01-14 01:00:01', '2026-01-14 08:09:01', 'dikembalikan', 0, '2026-01-14 00:55:38', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (25, 14, 3, '2026-01-14 00:56:20', '2026-01-14 08:09:05', 'dikembalikan', 0, '2026-01-14 00:55:42', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (26, 1, 3, '2026-01-14 07:58:16', '2026-01-14 08:08:57', 'dikembalikan', 0, '2026-01-14 07:57:26', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (27, 16, 3, '2026-01-14 08:09:56', '2026-01-14 08:20:14', 'dikembalikan', 0, '2026-01-14 08:09:19', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (28, 14, 3, '2026-01-14 08:11:23', '2026-01-14 08:20:10', 'dikembalikan', 0, '2026-01-14 08:10:56', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (29, 13, 3, '2026-01-14 08:20:31', '2026-01-26 22:22:42', 'dikembalikan', -5585, '2026-01-14 08:19:43', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (30, 16, 3, '2026-01-14 08:21:50', '2026-01-14 08:41:06', 'dikembalikan', 0, '2026-01-14 08:21:18', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (31, 2, 3, '2026-01-14 08:24:47', '2026-01-14 08:41:03', 'dikembalikan', 0, '2026-01-14 08:24:40', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (32, 14, 11, '2026-01-14 08:42:05', '2026-01-14 08:42:39', 'dikembalikan', 0, '2026-01-14 08:41:52', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (37, 14, 3, '2026-01-26 11:12:18', '2026-02-03 22:32:08', 'dikembalikan', -1000, '2026-01-26 11:11:42', NULL, NULL, NULL);
INSERT INTO `peminjaman_buku` VALUES (38, 16, 3, '2026-01-26 20:25:19', '2026-02-03 22:32:04', 'dikembalikan', -1000, '2026-01-26 20:00:36', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for peminjams
-- ----------------------------
DROP TABLE IF EXISTS `peminjams`;
CREATE TABLE `peminjams`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `jenis_kelamin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `peminjams_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `peminjams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of peminjams
-- ----------------------------
INSERT INTO `peminjams` VALUES (1, 4, NULL, NULL, NULL, '2025-10-20 03:27:09', '2025-10-20 03:27:09');
INSERT INTO `peminjams` VALUES (2, 5, NULL, NULL, NULL, '2025-10-20 04:12:30', '2025-10-20 04:12:30');
INSERT INTO `peminjams` VALUES (4, 7, NULL, NULL, NULL, '2025-10-31 07:38:24', '2025-10-31 07:38:24');
INSERT INTO `peminjams` VALUES (5, 8, NULL, NULL, NULL, '2025-10-31 07:50:38', '2025-10-31 07:50:38');
INSERT INTO `peminjams` VALUES (6, 9, NULL, NULL, NULL, '2025-10-31 08:02:25', '2025-10-31 08:02:25');
INSERT INTO `peminjams` VALUES (7, 10, NULL, NULL, NULL, '2026-01-13 15:50:05', '2026-01-13 15:50:05');
INSERT INTO `peminjams` VALUES (8, 11, NULL, NULL, NULL, '2026-01-14 08:32:13', '2026-01-14 08:32:13');
INSERT INTO `peminjams` VALUES (11, 14, NULL, NULL, NULL, '2026-01-26 13:23:29', '2026-01-26 13:23:29');
INSERT INTO `peminjams` VALUES (12, 15, NULL, NULL, NULL, '2026-01-26 16:52:20', '2026-01-26 16:52:20');
INSERT INTO `peminjams` VALUES (13, 16, NULL, NULL, NULL, '2026-02-03 22:38:33', '2026-02-03 22:38:33');

-- ----------------------------
-- Table structure for penerbit
-- ----------------------------
DROP TABLE IF EXISTS `penerbit`;
CREATE TABLE `penerbit`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_penerbit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of penerbit
-- ----------------------------
INSERT INTO `penerbit` VALUES (1, 'avs', 'malas', NULL, NULL);
INSERT INTO `penerbit` VALUES (2, 'triv', 'hahaha', NULL, NULL);
INSERT INTO `penerbit` VALUES (3, 'ac', 'yoo', NULL, NULL);

-- ----------------------------
-- Table structure for penjagas
-- ----------------------------
DROP TABLE IF EXISTS `penjagas`;
CREATE TABLE `penjagas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `jabatan_id` bigint UNSIGNED NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `penjagas_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `penjagas_jabatan_id_foreign`(`jabatan_id` ASC) USING BTREE,
  CONSTRAINT `penjagas_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `penjagas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of penjagas
-- ----------------------------

-- ----------------------------
-- Table structure for penulis
-- ----------------------------
DROP TABLE IF EXISTS `penulis`;
CREATE TABLE `penulis`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_penulis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of penulis
-- ----------------------------
INSERT INTO `penulis` VALUES (1, 'Kalimasada', 'Kalimasada@gmail.com', NULL, NULL);
INSERT INTO `penulis` VALUES (2, 'Timothy Ronald', 'TimothyRonald@gmail.com', NULL, NULL);
INSERT INTO `penulis` VALUES (3, 'GabrielRey', 'GabrielRey@gmail.com', NULL, NULL);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_unique`(`name` ASC) USING BTREE,
  UNIQUE INDEX `permissions_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'buku.create', 'buku-create', 'buku', 'create', 'Tambah data buku', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (2, 'buku.read', 'buku-read', 'buku', 'read', 'Lihat data buku', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (3, 'buku.update', 'buku-update', 'buku', 'update', 'Edit data buku', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (4, 'buku.delete', 'buku-delete', 'buku', 'delete', 'Hapus data buku', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (5, 'buku-masuk.create', 'buku-masuk-create', 'buku-masuk', 'create', 'Tambah data buku masuk', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (6, 'buku-masuk.read', 'buku-masuk-read', 'buku-masuk', 'read', 'Lihat data buku masuk', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (7, 'buku-masuk.update', 'buku-masuk-update', 'buku-masuk', 'update', 'Edit data buku masuk', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (8, 'buku-masuk.delete', 'buku-masuk-delete', 'buku-masuk', 'delete', 'Hapus data buku masuk', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (9, 'peminjaman.create', 'peminjaman-create', 'peminjaman', 'create', 'Tambah data peminjaman', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (10, 'peminjaman.read', 'peminjaman-read', 'peminjaman', 'read', 'Lihat data peminjaman', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (11, 'peminjaman.update', 'peminjaman-update', 'peminjaman', 'update', 'Edit data peminjaman', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (12, 'peminjaman.delete', 'peminjaman-delete', 'peminjaman', 'delete', 'Hapus data peminjaman', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (13, 'peminjaman.approve', 'peminjaman-approve', 'peminjaman', 'approve', 'Setujui peminjaman/pengembalian', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (14, 'user.create', 'user-create', 'user', 'create', 'Tambah data user', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (15, 'user.read', 'user-read', 'user', 'read', 'Lihat data user', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (16, 'user.update', 'user-update', 'user', 'update', 'Edit data user', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (17, 'user.delete', 'user-delete', 'user', 'delete', 'Hapus data user', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (18, 'permission.manage', 'permission-manage', 'permission', 'manage', 'Kelola hak akses', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (19, 'laporan.read', 'laporan-read', 'laporan', 'read', 'Lihat laporan', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (20, 'laporan.export', 'laporan-export', 'laporan', 'export', 'Export laporan', '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `permissions` VALUES (21, 'log.read', 'log-read', 'log', 'read', 'Lihat log aktivitas', '2026-01-26 19:35:13', '2026-01-26 19:35:13');

-- ----------------------------
-- Table structure for problem_reports
-- ----------------------------
DROP TABLE IF EXISTS `problem_reports`;
CREATE TABLE `problem_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_proof` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('pending','processed','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `problem_reports_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `problem_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of problem_reports
-- ----------------------------
INSERT INTO `problem_reports` VALUES (1, 3, 'buku nya sampulnya sobek', NULL, 'pending', NULL, '2026-02-03 22:36:27', '2026-02-03 22:36:27');

-- ----------------------------
-- Table structure for request_buku
-- ----------------------------
DROP TABLE IF EXISTS `request_buku`;
CREATE TABLE `request_buku`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `judul_buku` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `request_buku_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `request_buku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of request_buku
-- ----------------------------
INSERT INTO `request_buku` VALUES (1, 3, 'bebeb', NULL, NULL, 'karna cantik bang', 'pending', NULL, '2026-01-26 22:44:58', '2026-01-26 22:44:58');
INSERT INTO `request_buku` VALUES (2, 3, 'hairy potter', 'nikolas tinja', 'horror fiction', NULL, 'pending', NULL, '2026-02-03 22:33:11', '2026-02-03 22:33:11');

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_permissions_level_id_permission_id_unique`(`level_id` ASC, `permission_id` ASC) USING BTREE,
  INDEX `role_permissions_permission_id_foreign`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `role_permissions_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES (1, 1, 1, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (2, 1, 2, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (3, 1, 3, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (4, 1, 4, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (5, 1, 5, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (6, 1, 6, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (7, 1, 7, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (8, 1, 8, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (9, 1, 9, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (10, 1, 10, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (11, 1, 11, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (12, 1, 12, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (13, 1, 13, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (14, 1, 14, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (15, 1, 15, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (16, 1, 16, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (17, 1, 17, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (18, 1, 19, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (19, 1, 20, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (20, 2, 1, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (21, 2, 2, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (22, 2, 3, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (23, 2, 4, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (24, 2, 5, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (25, 2, 6, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (26, 2, 7, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (27, 2, 8, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (28, 2, 9, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (29, 2, 10, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (30, 2, 11, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (31, 2, 12, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (32, 2, 13, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (33, 3, 2, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (34, 3, 10, '2026-01-13 07:43:00', '2026-01-13 07:43:00');
INSERT INTO `role_permissions` VALUES (58, 6, 1, NULL, NULL);
INSERT INTO `role_permissions` VALUES (59, 6, 2, NULL, NULL);
INSERT INTO `role_permissions` VALUES (60, 6, 3, NULL, NULL);
INSERT INTO `role_permissions` VALUES (61, 6, 4, NULL, NULL);
INSERT INTO `role_permissions` VALUES (62, 6, 5, NULL, NULL);
INSERT INTO `role_permissions` VALUES (63, 6, 6, NULL, NULL);
INSERT INTO `role_permissions` VALUES (64, 6, 7, NULL, NULL);
INSERT INTO `role_permissions` VALUES (65, 6, 8, NULL, NULL);
INSERT INTO `role_permissions` VALUES (66, 6, 9, NULL, NULL);
INSERT INTO `role_permissions` VALUES (67, 6, 10, NULL, NULL);
INSERT INTO `role_permissions` VALUES (68, 6, 11, NULL, NULL);
INSERT INTO `role_permissions` VALUES (69, 6, 12, NULL, NULL);
INSERT INTO `role_permissions` VALUES (70, 6, 13, NULL, NULL);
INSERT INTO `role_permissions` VALUES (71, 6, 14, NULL, NULL);
INSERT INTO `role_permissions` VALUES (72, 6, 15, NULL, NULL);
INSERT INTO `role_permissions` VALUES (73, 6, 16, NULL, NULL);
INSERT INTO `role_permissions` VALUES (74, 6, 17, NULL, NULL);
INSERT INTO `role_permissions` VALUES (75, 6, 18, NULL, NULL);
INSERT INTO `role_permissions` VALUES (76, 6, 19, NULL, NULL);
INSERT INTO `role_permissions` VALUES (77, 6, 20, NULL, NULL);
INSERT INTO `role_permissions` VALUES (79, 6, 21, '2026-01-26 19:35:13', '2026-01-26 19:35:13');
INSERT INTO `role_permissions` VALUES (80, 1, 21, '2026-01-26 19:35:13', '2026-01-26 19:35:13');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Perpustakaan Kekinian',
  `logo` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `manager_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `contact_info` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fonnte_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discord_webhook` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES (1, 'Perpustakaan Digital', 'settings/qtQtY2lEtCqQGiWhPx80tz6NS5kdijbRdEST1M4h.png', 'Admin Utama', 'Jalan Gadjah Mada No. 123, Batam', '021-12345678', NULL, '2026-01-14 01:57:38', '2026-02-03 22:42:11', NULL);

-- ----------------------------
-- Table structure for user_level_histories
-- ----------------------------
DROP TABLE IF EXISTS `user_level_histories`;
CREATE TABLE `user_level_histories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `old_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `new_level_id` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_level_histories
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `no_hp` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_expires_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_level_id_foreign`(`level_id` ASC) USING BTREE,
  CONSTRAINT `users_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'aaa', 'a@gmail.com', NULL, NULL, '2026-01-13 20:39:40', '$2y$12$8/e.dGBXpy8izgVsSysTY.Ib740B8pHxZaHW90A.T3yvqwT6zbpK.', 1, '2025-10-01 14:55:12', '2026-01-14 20:19:26', NULL, NULL, NULL, 5);
INSERT INTO `users` VALUES (2, 'b', 'b@gmail.com', NULL, NULL, '2026-01-13 20:39:43', '$2y$12$mXIwRFlZOR4.MZbh4uvZXObZSfZexD.pMRmYVEke34JJdFIagMH8S', 2, '2025-11-01 14:55:21', '2025-10-24 08:21:04', NULL, NULL, NULL, 5);
INSERT INTO `users` VALUES (3, 'c', 'c@gmail.com', NULL, NULL, '2026-01-13 20:39:47', '$2y$12$HJZWQZmd5cSD1Y7/cyTy0.4uIuiaBAR3r5w4WoxNkhfkHSC0BOTD.', 3, '2025-10-01 14:55:25', '2026-01-13 14:55:08', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (4, 'd', 'd@gmail.com', NULL, NULL, '2026-01-13 20:39:51', '$2y$12$S8oarg85/0dv/Wqh4rufTO9POFRdbLaHl1KGcy4Ein42axmUU0Ctu', 4, '2025-10-20 03:27:09', '2025-10-20 03:27:09', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (5, 'e', 'e@gmail.com', '082386338889', NULL, '2026-01-13 20:52:08', '$2y$12$Ldvy1ke9qaZBehMI4l589ubVfHA06UX3YQ7sMQELuruLbViLh6.7i', 6, '2025-10-20 04:12:30', '2026-02-03 21:10:56', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (7, 'jonathan', 'jonathan@gmail.com', NULL, NULL, '2025-10-31 07:38:24', 'jonathan', 3, '2025-10-31 07:38:24', '2025-10-31 07:38:24', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (8, 'james', 'james@gmail.com', NULL, NULL, '2025-10-31 07:50:38', '123', 3, '2025-10-31 07:50:38', '2025-10-31 07:50:38', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (9, 'terserah', 'terserah@gmail.com', NULL, NULL, '2025-10-31 08:02:25', 'terserrah', 3, '2025-10-31 08:02:25', '2025-10-31 08:02:25', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (10, 'a', 'jovian2010gntx@gmail.com', NULL, '082386338889', '2026-01-13 15:50:05', '$2y$12$1IZ9FbQSHmkh51B/6wehEeKUv0V1eCu4iIRI5TdWYFwBYmIpDNQEq', 3, '2026-01-13 15:50:05', '2026-01-13 15:50:05', 'p2WEHYQAR26we1MC2ijyC2OaHr39OMmspj2GPrhGyjwwBdyEzuhfdyFWUFaXVkL7', '2026-01-14 15:50:05', '2026-01-14 02:04:38', NULL);
INSERT INTO `users` VALUES (11, 'Johny', 'floatycandy@gmail.com', NULL, NULL, '2026-01-14 08:33:00', '$2y$12$IQG0xi4CQVduWseYkVJx8eIEP6KvJUFzGEmlbUkn4Qv0dftgjqpuq', 3, '2026-01-14 08:32:13', '2026-01-14 08:33:00', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (14, 'aaaaa', 'aaaa@gmail.com', NULL, NULL, NULL, '$2y$12$1uCnO5E1fL0yIGN2OZOMMOmzlUhRRgRHqqE7Hc90KkLyePUjX18p2', 3, '2026-01-26 13:23:29', '2026-01-26 13:23:29', 'H9K8B48dLQcwJK2Xhqg5qYmwoGnP1fG7QTJ57d5ZIPRBggGFu6pDhgpXjqWhXrNa', '2026-01-27 13:23:29', NULL, NULL);
INSERT INTO `users` VALUES (15, 'aaaaaaa', 'bebeb@gmail.com', NULL, NULL, NULL, '$2y$12$.cRnQ5lLoKB5/sOTHcFwO.iYdmEJoOzogEvrfFaNzAQbNxw9V/dqG', 3, '2026-01-26 16:52:20', '2026-01-26 16:52:20', 'cC4zFOM6chidyj1duT8RxAFGk6d1NHhFfiBBaVPNEyuAFOJU9oEkZy99ogzOadOM', '2026-01-27 16:52:20', NULL, NULL);
INSERT INTO `users` VALUES (16, 'yanto', 'jovianvian12@gmail.com', NULL, NULL, '2026-02-03 22:38:58', '$2y$12$1Ywy0J.9yMJT6vCUTSuGkOaVGAWXPI1UhQNO9RCYzACR4CGK/T2IO', 3, '2026-02-03 22:38:33', '2026-02-03 22:38:58', NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
