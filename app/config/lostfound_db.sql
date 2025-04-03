-- phpMyAdmin SQL Dump 
-- Hệ thống quản lý đồ thất lạc - Phiên bản mới (PDU_LF)
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `PDU_LF`
--
CREATE DATABASE IF NOT EXISTS `PDU_LF` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `PDU_LF`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`) VALUES
(1, 'Điện thoại', 'Điện thoại di động, smartphone, điện thoại cơ bản', 'fa-mobile-alt'),
(2, 'Máy tính', 'Laptop, máy tính bảng, thiết bị điện tử', 'fa-laptop'),
(3, 'Thẻ căn cước/giấy tờ', 'Giấy tờ tùy thân, CMND, thẻ sinh viên', 'fa-id-card'),
(4, 'Ví/Túi xách', 'Ví tiền, túi xách, ba lô', 'fa-wallet'),
(5, 'Chìa khóa', 'Chìa khóa, móc khóa, chìa khóa xe', 'fa-key'),
(6, 'Sách/Tài liệu', 'Sách, tài liệu học tập, giáo trình', 'fa-book'),
(7, 'Trang sức', 'Đồng hồ, nhẫn, vòng tay, dây chuyền', 'fa-gem'),
(8, 'Quần áo', 'Quần áo, giày dép, phụ kiện thời trang', 'fa-tshirt'),
(9, 'Khác', 'Các vật dụng khác không thuộc các danh mục trên', 'fa-box-open');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `claims`
--

DROP TABLE IF EXISTS `claims`;
CREATE TABLE `claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `claimer_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verification_score` int(11) DEFAULT 0,
  `meeting_location` varchar(255) DEFAULT NULL,
  `meeting_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `claimer_id` (`claimer_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `claims`
--

INSERT INTO `claims` (`id`, `item_id`, `claimer_id`, `owner_id`, `status`, `verification_score`, `meeting_location`, `meeting_time`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 3, 'verified', 100, 'Sảnh tòa nhà A', '2025-04-01 16:45:24', NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(2, 9, 4, 5, 'pending', 70, 'Văn phòng khoa CNTT', '2025-04-02 16:45:24', NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `forum_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Bạn nên để ý đồ đạc của mình, không nên để đồ giá trị một mình. Tốt nhất là mang theo bên người.', '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(2, 1, 4, 'Tôi thường dán nhãn tên và số điện thoại vào các vật dụng của mình.', '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(3, 2, 2, 'Cảm ơn bạn đã chia sẻ kinh nghiệm hữu ích!', '2025-03-31 16:45:24', '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `forums`
--

DROP TABLE IF EXISTS `forums`;
CREATE TABLE `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `forums`
--

INSERT INTO `forums` (`id`, `title`, `content`, `user_id`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Làm thế nào để tránh mất đồ tại trường?', 'Tôi muốn hỏi mọi người có kinh nghiệm gì để tránh bị mất đồ tại trường không? Gần đây có nhiều bạn bị mất đồ.', 2, 45, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(2, 'Kinh nghiệm tìm đồ thất lạc', 'Tôi muốn chia sẻ kinh nghiệm khi bị mất đồ tại trường. Trước tiên hãy kiểm tra các địa điểm sau...', 3, 63, '2025-03-31 16:45:24', '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `images`
--

INSERT INTO `images` (`id`, `item_id`, `file_path`, `created_at`) VALUES
(1, 1, 'uploads/items/iphone13.png', '2025-03-31 16:45:24'),
(2, 2, 'uploads/items/cccd.png', '2025-03-31 16:45:24'),
(3, 3, 'uploads/items/laptop_dell.png', '2025-03-31 16:45:24'),
(4, 4, 'uploads/items/sach_cpp.png', '2025-03-31 16:45:24'),
(5, 5, 'uploads/items/chia_khoa.png', '2025-03-31 16:45:24'),
(6, 6, 'uploads/items/vi_tien.png', '2025-03-31 16:45:24'),
(7, 7, 'uploads/items/samsung_note.png', '2025-03-31 16:45:24'),
(8, 8, 'uploads/items/airpods.png', '2025-03-31 16:45:24'),
(9, 9, 'uploads/items/the_sinh_vien.png', '2025-03-31 16:45:24'),
(10, 10, 'uploads/items/sach_tienganh.png', '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('lost','found') NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `lost_found_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','active','resolved','rejected') DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL,
  `private_info` text DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `items`
--

INSERT INTO `items` (`id`, `title`, `description`, `type`, `category_id`, `location`, `lost_found_date`, `status`, `user_id`, `private_info`, `expiry_date`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Điện thoại iPhone 13 màu đen', 'Tôi đã đánh mất điện thoại iPhone 13 màu đen tại khu vực thư viện tầng 2. Điện thoại có ốp lưng màu trong suốt, bị trầy nhẹ ở góc phải màn hình.', 'lost', 1, 'Thư viện trường, tầng 2', '2025-04-01 06:57:34', 'active', 2, 'Mật khẩu màn hình: 123456', '2025-04-27 16:45:24', 26, '2025-03-31 16:45:24', '2025-04-01 06:57:34'),
(2, 'Thẻ sinh viên + CCCD', 'Tôi đánh mất bóp chứa thẻ sinh viên và căn cước công dân tại căng tin. Bóp màu nâu nhỏ.', 'lost', 3, 'Căng tin trường', '2025-03-30 16:45:24', 'active', 3, 'CCCD số: 03819100xxxx', '2025-04-29 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(3, 'Laptop Dell Inspiron màu bạc', 'Tôi để quên laptop tại phòng học H103 vào tiết 3-4 ngày hôm qua. Laptop Dell màu bạc có sticker hình mèo ở góc.', 'lost', 2, 'Phòng học H103', '2025-03-29 16:45:24', 'active', 4, 'Laptop có mật khẩu, tài khoản người dùng tên \"letha\"', '2025-04-28 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(4, 'Sách Giáo trình Lập Trình C++', 'Tôi đã để quên sách Giáo trình Lập Trình C++ tại ghế đá gần sân bóng. Sách bìa màu xanh, có ghi tên Nguyễn Văn A ở trang đầu.', 'lost', 6, 'Khu vực ghế đá gần sân bóng', '2025-03-26 16:45:24', 'active', 2, 'Bên trong có ghi chi chú và highlight nhiều chỗ', '2025-04-25 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(5, 'Chìa khóa xe máy Honda', 'Tôi đánh mất chìa khóa xe máy Honda, có móc khóa hình tròn màu đỏ tại khu vực nhà xe.', 'lost', 5, 'Nhà xe sinh viên', '2025-03-31 18:08:58', 'active', 5, 'Chìa khóa có mã số: KH-27381', '2025-04-29 16:45:24', 1, '2025-03-31 16:45:24', '2025-03-31 18:08:58'),
(6, 'Tìm thấy ví tiền màu đen', 'Tôi đã nhặt được một ví tiền màu đen tại khu vực ghế đá gần hồ. Trong ví có một số giấy tờ và tiền mặt.', 'found', 4, 'Khu vực ghế đá gần hồ', '2025-03-31 16:45:24', 'resolved', 3, 'Trong ví có CCCD, thẻ ngân hàng và khoảng 500.000đ tiền mặt', '2025-04-28 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(7, 'Tìm thấy điện thoại Samsung Note 10', 'Tôi nhặt được một điện thoại Samsung Note 10 màu xanh tại phòng tự học thư viện tầng 3. Máy vẫn hoạt động nhưng đã hết pin.', 'found', 1, 'Phòng tự học thư viện tầng 3', '2025-04-01 02:46:24', 'active', 2, 'Màn hình khóa, có thông báo cuộc gọi nhỡ từ \"Mẹ\"', '2025-04-29 16:45:24', 13, '2025-03-31 16:45:24', '2025-04-01 02:46:24'),
(8, 'Tìm thấy tai nghe Bluetooth', 'Tôi nhặt được tai nghe Bluetooth màu trắng, hiệu Apple AirPods tại căng tin. Tai nghe còn trong hộp sạc.', 'found', 1, 'Căng tin trường', '2025-03-28 16:45:24', 'active', 4, 'Hộp sạc có ghi tên \"Linh\" ở mặt sau', '2025-04-27 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(9, 'Tìm thấy thẻ sinh viên', 'Tôi nhặt được thẻ sinh viên tại khu vực cổng trường. Thẻ mang tên Trần Minh Đức.', 'found', 3, 'Cổng trường', '2025-03-27 16:45:24', 'active', 5, 'Mã sinh viên: CT2001, khoa CNTT', '2025-04-26 16:45:24', 0, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(10, 'Tìm thấy sách Tiếng Anh chuyên ngành', 'Tôi đã nhặt được quyển sách Tiếng Anh chuyên ngành Y tại phòng học Y203.', 'found', 6, 'Phòng học Y203', '2025-04-01 03:48:23', 'active', 3, 'Sách có ghi tên Nguyễn Thị Hương ở trang đầu', '2025-04-28 16:45:24', 2, '2025-03-31 16:45:24', '2025-04-01 03:48:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `claim_id` (`claim_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `content` text NOT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `content`, `read_status`, `reference_id`, `created_at`) VALUES
(1, 2, 'claim', 'Có người yêu cầu nhận lại đồ cho bài đăng của bạn', 0, 1, '2025-03-31 16:45:24'),
(2, 3, 'resolved', 'Báo cáo đồ thất lạc của bạn đã được giải quyết', 0, 6, '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) DEFAULT NULL,
  `rater_id` int(11) DEFAULT NULL,
  `rated_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `claim_id` (`claim_id`),
  KEY `rater_id` (`rater_id`),
  KEY `rated_id` (`rated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `permissions` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`) VALUES
(1, 'User', 'basic_access'),
(2, 'Moderator', 'moderate_content,manage_items'),
(3, 'Admin', 'full_access');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `faculty` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT 1,
  `trust_points` int(11) DEFAULT 0,
  `is_email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `faculty`, `class`, `student_id`, `role_id`, `trust_points`, `is_email_verified`, `verification_token`, `reset_token`, `reset_token_expiry`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@phuongdong.edu.vn', '$2y$10$1E0cXt9YsX16uFSJQvJNQO5j2NP4dEQ6xKtdeTks168x5Lb.TCVX2', 'Admin', NULL, NULL, NULL, NULL, 3, 100, 1, NULL, NULL, NULL, '2025-03-31 16:45:24', '2025-04-01 16:31:47'),
(2, 'nguyenvana', 'nguyenvana@gmail.com', '$2y$10$1E0cXt9YsX16uFSJQvJNQO5j2NP4dEQ6xKtdeTks168x5Lb.TCVX2', 'Nguyễn Văn A', '0901234567', 'Công nghệ thông tin', 'CNTT01', 'CT001', 1, 15, 1, NULL, NULL, NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(3, 'tranvanb', 'tranvanb@gmail.com', '$2y$10$1E0cXt9YsX16uFSJQvJNQO5j2NP4dEQ6xKtdeTks168x5Lb.TCVX2', 'Trần Văn B', '0912345678', 'Kinh tế', 'KT02', 'KT002', 1, 10, 1, NULL, NULL, NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(4, 'lenguyenc', 'lenguyenc@gmail.com', '$2y$10$1E0cXt9YsX16uFSJQvJNQO5j2NP4dEQ6xKtdeTks168x5Lb.TCVX2', 'Lê Nguyên C', '0923456789', 'Ngoại ngữ', 'NN03', 'NN003', 1, 5, 1, NULL, NULL, NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24'),
(5, 'phamthid', 'phamthid@gmail.com', '$2y$10$1E0cXt9YsX16uFSJQvJNQO5j2NP4dEQ6xKtdeTks168x5Lb.TCVX2', 'Phạm Thị D', '0934567890', 'Y dược', 'YD01', 'YD004', 1, 8, 1, NULL, NULL, NULL, '2025-03-31 16:45:24', '2025-03-31 16:45:24');

-- --------------------------------------------------------

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `claims_ibfk_2` FOREIGN KEY (`claimer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `claims_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `forums`
--
ALTER TABLE `forums`
  ADD CONSTRAINT `forums_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`rated_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 