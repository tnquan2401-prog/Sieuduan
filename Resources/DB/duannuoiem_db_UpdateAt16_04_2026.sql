-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql308.infinityfree.com
-- Thời gian đã tạo: Th4 15, 2026 lúc 11:48 AM
-- Phiên bản máy phục vụ: 11.4.10-MariaDB
-- Phiên bản PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `if0_41658507_duannuoiem_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `description`) VALUES
(1, 'Áo'),
(2, 'Quần'),
(3, 'Váy'),
(4, 'Bộ'),
(5, 'Dép'),
(6, 'Giày'),
(7, 'Sách vở'),
(8, 'Nhu yếu phẩm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `season_id` tinyint(1) DEFAULT NULL,
  `quality` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `name`, `image`, `type_id`, `category_id`, `season_id`, `quality`, `description`, `TrangThai`, `created_at`) VALUES
(2, 'áo...', 'cba8c2bac8aa63224abdd53134c8123e.jpg', 0, 1, 1, 'Mới nguyên', 'dfghjkl.;', 1, '2026-04-14 06:42:11'),
(4, 'Quần áo bộ', 'HNB-04.jpg', 1, 4, 0, 'Cũ tốt', '', 1, '2026-04-14 06:42:11'),
(5, 'Quần áo bộ', 'HNB-04.jpg', 0, 4, 0, 'Cũ tốt', '', 1, '2026-04-14 06:42:11'),
(6, 'Áo', '0a0cc26eccb899c3ebec247de8c951ac.png', 0, 2, 0, 'Mới nguyên', 'dfghjktrygjkl', 1, '2026-04-14 06:42:11'),
(7, 'áo', '582bc1b586f8e2d35047344070debf17.png', 0, 1, 1, 'Mới 95%', 'quyên góp', 1, '2026-04-14 06:43:42'),
(8, 'áo đfs', 'e818ac95ff086e9ad969cb9924bd79f7.png', 0, 2, 0, 'Mới nguyên', 'sfsdfsf', 1, '2026-04-14 06:44:25'),
(9, 'áo', '81123c2f54120024eb87d8cd7e72f11b.png', 1, 2, 0, 'Cũ tốt', 'sfdfsdf', 1, '2026-04-14 06:45:18'),
(10, 'aoadasdada', '1f48ffed8fd6f7291da099cec7b293da.png', 0, 2, 0, 'Mới 95%', 'aesrdtyutrf', 1, '2026-04-14 06:52:53'),
(11, 'adsdsad', 'bb9c7f996ec1ad3733d3498ebc656eb5.png', 0, 2, 1, 'Mới nguyên', '', 1, '2026-04-14 07:01:42'),
(12, 'dcsdada', '85d067b24e9db031def179df3996d67d.png', 0, 3, 1, 'Mới nguyên', 'ada', 1, '2026-04-14 07:02:12'),
(15, 'ádas', '26f76c2d8e65dcdcf06634d6f9ac7053.png', 0, 8, 1, 'Mới nguyên', 'ádasd', 1, '2026-04-14 07:22:07'),
(16, 'sádfsdf', '0eaf8d7fbf3958c9fdf7b51facdd1760.png', 0, 7, 1, 'Mới nguyên', 'àasfasf', 1, '2026-04-14 09:05:37'),
(17, 'Bộ nỉ', '9aebdd05b45f1f8ebf60f1fe49f0a21e.png', 1, 4, 1, 'Mới nguyên', '', 1, '2026-04-14 15:46:32'),
(18, 'Áo thun ', 'be1118c55e2c7e79cfb5085cca1f9dbc.jpg', 0, 2, 1, 'Mới nguyên', '', 0, '2026-04-15 13:15:02'),
(19, 'Áo thun 2', '5cf5ab056fff8521a57d42c77b71b7c9.jpg', 0, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:16:45'),
(20, 'áo thun 3', 'c1b66129df114426cb0afe182cc35054.jpg', 0, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:17:34'),
(21, 'áo thun 4', 'd2ba3d0675167816fb3955344af67d88.jpg', 0, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:17:58'),
(22, 'áo thun 5', '0b9eaeb4a5a8668d4361f28dd59ed8a8.jpg', 0, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:18:14'),
(23, 'quần 1', 'd2acee232718dd8a894913705b00ee18.jpg', 0, 1, 1, 'Mới 95%', '', 0, '2026-04-15 13:19:29'),
(24, 'bộ 1', '262fef312020a26b97c6e11bb7fe017d.jpg', 0, 4, 1, 'Mới 95%', '', 0, '2026-04-15 13:19:59'),
(25, 'bộ 2', '0968b9f628f5214a5b95722e44e30a97.jpg', 0, 4, 1, 'Mới 95%', '', 0, '2026-04-15 13:20:37'),
(26, 'váy 1', '96f84b606b0d183a4b708cfd8286cb8f.jpg', 0, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:26:34'),
(27, 'váy 2', '331659a570689b133aa9f5f20e7ead6e.jpg', 0, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:27:00'),
(28, 'áo hè 1', 'f561dcdc8139fbff9f158ce0ca90f019.jpg', 0, 2, 0, 'Mới 95%', '', 0, '2026-04-15 13:27:44'),
(29, 'áo hè 2', '7447fae8085bed9c589cfb816c41647f.jpg', 0, 2, 0, 'Mới 95%', '', 0, '2026-04-15 13:28:02'),
(30, 'áo hè 3', '8ba7ad08cd8cdaf8336041d4c89c4db8.jpg', 0, 2, 0, 'Mới 95%', '', 0, '2026-04-15 13:28:20'),
(31, 'áo nam đông 1', 'd1e491485c30adea9730e04841298296.jpg', 1, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:29:09'),
(32, 'áo nam đông 2', '946ca62716818aa6258670746875d286.jpg', 1, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:29:26'),
(33, 'áo nam đông 2', '91d852f99082ef7369f9f205cf9880d6.jpg', 1, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:29:47'),
(34, 'áo nam đông 2', '51cd76acc3be9815c17a3160840917dd.jpg', 1, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:30:16'),
(35, 'áo nam đông 3', 'a06e55355efdf60640e72db12866737b.jpg', 1, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:36:26'),
(36, 'áo nam đông 4', 'bac988cee845e7c4c50a2d12c45bd1e0.jpg', 1, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:36:44'),
(37, 'áo nữ đông 1', '5fd7c2b187a7073dd7ca8da2ee2006e7.jpg', 1, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:37:50'),
(38, 'áo nữ đông 2', '5a528da449afe6ebf3833d7b5282990c.jpg', 1, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:38:16'),
(39, 'áo nữ đông 3', '4cbe795245f006ed193373806985b860.jpg', 1, 2, 1, 'Mới 95%', '', 0, '2026-04-15 13:38:35'),
(40, 'áo nữ đông 4', '0d40754830ba312379d9b092e452b61e.jpg', 1, 2, 1, 'Cũ tốt', '', 0, '2026-04-15 13:40:06'),
(41, 'váy nữ 1', '590598b6a4b8a3440dda49d5c6d78552.jpg', 1, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:45:12'),
(42, 'váy nữ 2', '93b7d94959cdb5688841bab24ce8d4fc.jpg', 1, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:45:31'),
(43, 'váy nữ 3', '5f2b7f9e0994fa49de903f26803e2f67.jpg', 1, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:45:55'),
(44, 'váy nữ 4', '383af85ec15119f74e79d4fae10b5465.jpg', 1, 3, 0, 'Mới 95%', '', 0, '2026-04-15 13:46:28'),
(45, 'váy nữ 5', '0acf339ad8ba815bdabb1eb11289d5e2.jpg', 1, 3, 0, 'Cũ tốt', '', 0, '2026-04-15 13:47:02'),
(46, 'quần nữ 1', '48853d05001edcc9979eab2683b2e568.jpg', 1, 1, 0, 'Mới 95%', '', 0, '2026-04-15 13:47:45'),
(47, 'quần nữ 2', '7295b616696eeb1bf35eada6ba99b8f0.jpg', 1, 1, 0, 'Cũ tốt', '', 0, '2026-04-15 13:48:10'),
(48, 'quần nữ 3', 'eae3692ab670ce71cdbfa57abcc82877.jpg', 1, 1, 0, 'Mới 95%', '', 0, '2026-04-15 13:48:46'),
(49, 'quần nữ 4', 'ff602982a2fd9bfff06c716fc6ee3ebb.jpg', 1, 1, 0, 'Cũ tốt', '', 0, '2026-04-15 13:49:33'),
(50, 'váy dài 1', '551dcb2a977148e9a3c28273a954aa3c.jpg', 1, 4, 0, 'Mới 95%', '', 0, '2026-04-15 13:54:35'),
(51, 'váy dài 2', 'cda553e43a6b07bf520737ec7c85a525.jpg', 1, 4, 0, 'Mới 95%', '', 0, '2026-04-15 13:55:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanphamnhan`
--

DROP TABLE IF EXISTS `sanphamnhan`;
CREATE TABLE `sanphamnhan` (
  `ID` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanphamnhan`
--

INSERT INTO `sanphamnhan` (`ID`, `product_id`, `user_id`, `TrangThai`) VALUES
(1, 5, 2, 1),
(2, 6, 3, 1),
(3, 4, 3, 1),
(6, 2, 3, 1),
(7, 17, 3, 0),
(8, 16, 4, 0),
(9, 11, 3, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `season`
--

DROP TABLE IF EXISTS `season`;
CREATE TABLE `season` (
  `id` tinyint(1) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `season`
--

INSERT INTO `season` (`id`, `description`) VALUES
(0, 'Hè'),
(1, 'Đông');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongtinnguoinhan`
--

DROP TABLE IF EXISTS `thongtinnguoinhan`;
CREATE TABLE `thongtinnguoinhan` (
  `ID` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `Fullname` text DEFAULT NULL,
  `Phone` varchar(10) DEFAULT NULL,
  `Address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thongtinnguoinhan`
--

INSERT INTO `thongtinnguoinhan` (`ID`, `users_id`, `Fullname`, `Phone`, `Address`) VALUES
(1, 3, 'Nguyễn Văn Quân', '0382294559', 'Ba Vì Hà Nội'),
(6, 2, 'Quân Nguyễn Đức Hồng', '0382294559', 'Ba Vì Hà Nội'),
(7, 4, 'sfdfsdf', '0998967534', 'fsdhdsfds');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id` tinyint(1) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `type`
--

INSERT INTO `type` (`id`, `description`) VALUES
(0, 'Trẻ em'),
(1, 'Người lớn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `Passwd` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Permission` text DEFAULT NULL,
  `Create_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ID`, `UserName`, `Passwd`, `Email`, `Permission`, `Create_At`) VALUES
(1, 'HQuan', '202cb962ac59075b964b07152d234b70', 'quanng30082k5@gmail.com', 'admin', '2026-04-14 06:42:11'),
(2, 'test_1', '7363a0d0604902af7b70b271a0b96480', '123@hihi.vn', 'customer', '2026-04-14 06:42:11'),
(3, 'test2', '30cde89544caa549a813d660c4b27967', 'intokiss1@gmail.com', 'customer', '2026-04-14 06:42:11'),
(4, 'admin', '21232', 'admin@hihi.vn', 'admin', '2026-04-14 06:42:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `season_id` (`season_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Chỉ mục cho bảng `sanphamnhan`
--
ALTER TABLE `sanphamnhan`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thongtinnguoinhan`
--
ALTER TABLE `thongtinnguoinhan`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `users_id` (`users_id`);

--
-- Chỉ mục cho bảng `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `sanphamnhan`
--
ALTER TABLE `sanphamnhan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `thongtinnguoinhan`
--
ALTER TABLE `thongtinnguoinhan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sanpham_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sanphamnhan`
--
ALTER TABLE `sanphamnhan`
  ADD CONSTRAINT `sanphamnhan_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `sanpham` (`MaSP`) ON DELETE CASCADE,
  ADD CONSTRAINT `sanphamnhan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thongtinnguoinhan`
--
ALTER TABLE `thongtinnguoinhan`
  ADD CONSTRAINT `thongtinnguoinhan_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
