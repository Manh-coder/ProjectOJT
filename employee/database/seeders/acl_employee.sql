/*
 Navicat Premium Dump SQL

 Source Server         : LOCAL
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : acl_employee

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 24/12/2024 01:15:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES (1, 'Phòng Hành Chính', NULL, 1, '2024-11-12 00:15:51', 1, '2024-11-12 10:10:20', 1);
INSERT INTO `departments` VALUES (2, 'Phòng Nhân Sự', 1, 1, '2024-11-12 00:16:10', 1, '2024-11-12 00:16:10', 1);
INSERT INTO `departments` VALUES (3, 'Phòng Tài Chính', 2, 1, '2024-11-12 00:16:24', 1, '2024-11-12 00:16:24', 1);

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
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for leave_balances
-- ----------------------------
DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_leave_days` int NOT NULL DEFAULT 12,
  `used_leave_days` int NOT NULL DEFAULT 0,
  `unpaid_leave_days` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `leave_balances_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `leave_balances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_balances
-- ----------------------------
INSERT INTO `leave_balances` VALUES (1, 3, 12, 9, 0, NULL, '2024-12-13 14:32:20');
INSERT INTO `leave_balances` VALUES (2, 15, 11, 0, 0, '2024-12-22 21:26:05', '2024-12-22 21:26:05');

-- ----------------------------
-- Table structure for leave_requests
-- ----------------------------
DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `leave_requests_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_requests
-- ----------------------------
INSERT INTO `leave_requests` VALUES (1, 3, '2024-12-07', '2024-12-07', '12', 'approved', '2024-12-06 12:56:49', '2024-12-10 23:17:50');
INSERT INTO `leave_requests` VALUES (3, 3, '2024-12-07', '2024-12-08', '12345', 'approved', '2024-12-06 12:57:46', '2024-12-10 23:45:07');
INSERT INTO `leave_requests` VALUES (6, 3, '2024-12-10', '2024-12-11', '111111', 'rejected', '2024-12-10 23:25:46', '2024-12-10 23:26:00');
INSERT INTO `leave_requests` VALUES (7, 3, '2024-12-13', '2024-12-14', 'aaaaa', 'approved', '2024-12-13 12:20:24', '2024-12-13 14:17:17');
INSERT INTO `leave_requests` VALUES (15, 3, '2024-12-13', '2024-12-14', '1233333', 'approved', '2024-12-13 14:21:17', '2024-12-13 14:22:51');
INSERT INTO `leave_requests` VALUES (18, 3, '2024-12-29', '2024-12-29', 'XIn nghỉ', 'pending', '2024-12-22 14:53:21', '2024-12-22 14:53:21');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2024_10_22_125754_create_departments_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_10_22_125802_create_user_attendances_table', 1);
INSERT INTO `migrations` VALUES (7, '2024_11_11_095542_create_salary_level_table', 1);
INSERT INTO `migrations` VALUES (8, 'run_sql', 1);
INSERT INTO `migrations` VALUES (9, '2024_10_22_125744_create_employees_table', 2);
INSERT INTO `migrations` VALUES (10, '2024_11_17_235101_add_status_and_explanation_to_attendance_table', 2);
INSERT INTO `migrations` VALUES (11, '2024_11_18_080150_add_status_and_explanation_to_attendance_table', 3);
INSERT INTO `migrations` VALUES (12, '2024_11_24_001606_add_check_in_out_schedule_to_settings_table', 4);
INSERT INTO `migrations` VALUES (13, '2024_11_24_232657_create_user_notification_schedules_table', 5);
INSERT INTO `migrations` VALUES (14, '2024_11_25_003423_create_salaries_table', 6);
INSERT INTO `migrations` VALUES (15, '2024_12_06_120600_create_leave_requests_table', 7);
INSERT INTO `migrations` VALUES (16, '2024_12_06_120716_create_leave_balances_table', 8);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for salaries
-- ----------------------------
DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `valid_days` int NOT NULL,
  `invalid_days` int NOT NULL,
  `salary` decimal(10, 2) NOT NULL,
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `salaries_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `salaries_processed_by_foreign`(`processed_by` ASC) USING BTREE,
  INDEX `salaries_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `salaries_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `salaries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of salaries
-- ----------------------------
INSERT INTO `salaries` VALUES (13, 3, 4, 2, 2280000.00, '2024-11', 1, '2024-11-29 15:59:52', NULL, '2024-11-29 12:18:00', '2024-11-29 15:59:52');
INSERT INTO `salaries` VALUES (14, 15, 0, 0, 0.00, '2024-11', 1, '2024-11-29 16:03:46', NULL, '2024-11-29 12:18:00', '2024-11-29 16:03:46');
INSERT INTO `salaries` VALUES (15, 3, 1, 0, 540000.00, '2024-12', 1, '2024-12-22 01:38:20', NULL, '2024-12-01 22:45:46', '2024-12-22 01:38:20');
INSERT INTO `salaries` VALUES (16, 15, 0, 0, 0.00, '2024-12', 1, '2024-12-21 23:34:20', NULL, '2024-12-01 22:45:46', '2024-12-21 23:34:20');
INSERT INTO `salaries` VALUES (17, 3, 0, 0, 0.00, '2024-02', 1, '2024-12-22 20:08:09', NULL, '2024-12-22 20:08:09', '2024-12-22 20:08:09');
INSERT INTO `salaries` VALUES (18, 15, 0, 0, 0.00, '2024-02', 1, '2024-12-22 20:08:09', NULL, '2024-12-22 20:08:09', '2024-12-22 20:08:09');

-- ----------------------------
-- Table structure for salary_level
-- ----------------------------
DROP TABLE IF EXISTS `salary_level`;
CREATE TABLE `salary_level`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `monthly` int NULL DEFAULT NULL,
  `daily` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of salary_level
-- ----------------------------
INSERT INTO `salary_level` VALUES (1, 1, '2024-11-12 00:29:28', '2024-11-17 23:32:17', 12000000, 600000);
INSERT INTO `salary_level` VALUES (2, 2, '2024-11-12 10:16:04', '2024-11-12 10:30:17', 9000000, 300000);
INSERT INTO `salary_level` VALUES (3, 3, '2024-11-14 14:44:36', '2024-11-14 14:46:18', 6000000, 200000);

-- ----------------------------
-- Table structure for user_attendance
-- ----------------------------
DROP TABLE IF EXISTS `user_attendance`;
CREATE TABLE `user_attendance`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `datetime_ci` datetime NULL DEFAULT NULL,
  `type` enum('in','out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `datetime_co` datetime NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL,
  `explanation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('valid','invalid','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invalid',
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `check_in_time` time NOT NULL DEFAULT '07:00:00',
  `check_out_time` time NOT NULL DEFAULT '17:00:00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_attendance
-- ----------------------------
INSERT INTO `user_attendance` VALUES (1, 3, '2024-11-12 00:34:30', NULL, NULL, NULL, '2024-11-12 00:34:30', '2024-12-01 22:42:37', '2024-11-12 00:45:23', '2024-11-12', 'có một số chuyện đột suất', 'invalid', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (2, 3, '2024-11-13 01:16:57', NULL, NULL, NULL, '2024-11-13 01:16:57', '2024-12-01 22:42:37', '2024-11-13 01:17:01', '2024-11-13', '123444', 'invalid', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (4, 3, '2024-11-14 14:41:41', NULL, NULL, NULL, '2024-11-14 14:41:41', '2024-12-01 22:42:37', '2024-11-14 14:41:56', '2024-11-14', NULL, 'valid', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (40, 3, '2024-11-18 07:23:13', NULL, NULL, NULL, '2024-11-18 07:23:13', '2024-12-01 22:42:37', '2024-11-18 17:25:40', '2024-11-18', NULL, 'valid', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (44, 3, '2024-11-22 15:52:31', NULL, NULL, NULL, '2024-11-22 15:52:31', '2024-12-01 22:42:37', NULL, '2024-11-22', '123444', 'valid', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (46, 3, '2024-11-29 12:18:25', NULL, NULL, NULL, '2024-11-29 12:18:25', '2024-12-01 22:42:37', '2024-11-29 14:15:05', '2024-11-29', '1234', 'pending', 0, '10:00:00', '18:00:00');
INSERT INTO `user_attendance` VALUES (47, 3, '2024-12-01 22:43:27', NULL, NULL, NULL, '2024-12-01 22:43:27', '2024-12-01 22:44:41', NULL, '2024-12-01', '123', 'valid', 0, '07:00:00', '17:00:00');
INSERT INTO `user_attendance` VALUES (48, 3, '2024-12-22 14:50:05', NULL, NULL, NULL, '2024-12-22 14:50:05', '2024-12-22 14:50:05', NULL, '2024-12-22', NULL, 'invalid', 0, '07:00:00', '17:00:00');

-- ----------------------------
-- Table structure for user_notification_schedules
-- ----------------------------
DROP TABLE IF EXISTS `user_notification_schedules`;
CREATE TABLE `user_notification_schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `check_in_time` time NULL DEFAULT NULL,
  `check_out_time` time NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_notification_schedules_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_notification_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_notification_schedules
-- ----------------------------
INSERT INTO `user_notification_schedules` VALUES (1, 3, '04:56:00', '17:56:00', '2024-11-24 23:52:16', '2024-12-01 22:43:03');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `salary_level_id` int NULL DEFAULT NULL,
  `position` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `number_leave` int NULL DEFAULT NULL,
  `age` int NULL DEFAULT 20,
  `gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'male',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'a@gmail.com', 'admin', NULL, '$2y$12$JzxLR2flpCMGk5Pe7W906O6HAvwpeWB0jpOh3gtkWppkTsLidG5Ru', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 20, 'male');
INSERT INTO `users` VALUES (3, 'Nguyen Tien Manh', 'manhnthe170866@fpt.edu.vn', 'ManhNguyen', NULL, '$2y$12$JzxLR2flpCMGk5Pe7W906O6HAvwpeWB0jpOh3gtkWppkTsLidG5Ru', NULL, '2024-11-12 00:30:25', '2024-11-12 01:08:43', 1, 2, 1, 'Trưởng Phòng', '0123456789', NULL, 20, 'male');
INSERT INTO `users` VALUES (15, 'Nguyen Tien Dat', 'nguyentiendat@gmail.com', 'DatNguyen', NULL, '$2y$12$JzxLR2flpCMGk5Pe7W906O6HAvwpeWB0jpOh3gtkWppkTsLidG5Ru', NULL, '2024-11-15 21:27:29', '2024-11-15 21:27:29', 2, 2, 2, 'Nhân Viên', '0123456789', NULL, 20, 'male');

SET FOREIGN_KEY_CHECKS = 1;
