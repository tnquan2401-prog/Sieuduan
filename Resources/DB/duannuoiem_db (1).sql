-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 14, 2026 lúc 10:56 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `duannuoiem_db`
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
(2, 'áo...', 'cba8c2bac8aa63224abdd53134c8123e.jpg', 0, 1, 1, 'Mới nguyên', 'dfghjkl.;', 0, '2026-04-14 06:42:11'),
(4, 'Quần áo bộ', 'HNB-04.jpg', 1, 4, 0, 'Cũ tốt', '', 1, '2026-04-14 06:42:11'),
(5, 'Quần áo bộ', 'HNB-04.jpg', 0, 4, 0, 'Cũ tốt', '', 1, '2026-04-14 06:42:11'),
(6, 'Áo', '0a0cc26eccb899c3ebec247de8c951ac.png', 0, 2, 0, 'Mới nguyên', 'dfghjktrygjkl', 1, '2026-04-14 06:42:11'),
(7, 'áo', '582bc1b586f8e2d35047344070debf17.png', 0, 1, 1, 'Mới 95%', 'quyên góp', 1, '2026-04-14 06:43:42'),
(8, 'áo đfs', 'e818ac95ff086e9ad969cb9924bd79f7.png', 0, 2, 0, 'Mới nguyên', 'sfsdfsf', 1, '2026-04-14 06:44:25'),
(9, 'áo', '81123c2f54120024eb87d8cd7e72f11b.png', 1, 2, 0, 'Cũ tốt', 'sfdfsdf', 1, '2026-04-14 06:45:18'),
(10, 'aoadasdada', '1f48ffed8fd6f7291da099cec7b293da.png', 0, 2, 0, 'Mới 95%', 'aesrdtyutrf', 1, '2026-04-14 06:52:53'),
(11, 'adsdsad', 'bb9c7f996ec1ad3733d3498ebc656eb5.png', 0, 2, 1, 'Mới nguyên', '', 0, '2026-04-14 07:01:42'),
(12, 'dcsdada', '85d067b24e9db031def179df3996d67d.png', 0, 3, 1, 'Mới nguyên', 'ada', 0, '2026-04-14 07:02:12'),
(15, 'ádas', '26f76c2d8e65dcdcf06634d6f9ac7053.png', 0, 8, 1, 'Mới nguyên', 'ádasd', 1, '2026-04-14 07:22:07');

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
(3, 4, 3, 1);

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
(6, 2, 'Quân Nguyễn Đức Hồng', '0382294559', 'Ba Vì Hà Nội');

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
(2, 'test_1', '202cb962ac59075b964b07152d234b70', '123@hihi.vn', 'customer', '2026-04-14 06:42:11'),
(3, 'test2', '30cde89544caa549a813d660c4b27967', 'intokiss1@gmail.com', 'customer', '2026-04-14 06:42:11'),
(4, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@hihi.vn', 'admin', '2026-04-14 06:42:11');

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
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `sanphamnhan`
--
ALTER TABLE `sanphamnhan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `thongtinnguoinhan`
--
ALTER TABLE `thongtinnguoinhan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
