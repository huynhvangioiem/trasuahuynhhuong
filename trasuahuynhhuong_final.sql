-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th6 25, 2019 lúc 07:26 AM
-- Phiên bản máy phục vụ: 10.3.14-MariaDB
-- Phiên bản PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `id7034236_trasuahuynhhuong`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bodem`
--

CREATE TABLE `bodem` (
  `bodem_id` int(11) NOT NULL,
  `bodem_hoadonngay` int(11) NOT NULL,
  `bodem_hoadontong` int(11) NOT NULL,
  `bodem_status` int(1) NOT NULL,
  `bodem_sokm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bodem`
--

INSERT INTO `bodem` (`bodem_id`, `bodem_hoadonngay`, `bodem_hoadontong`, `bodem_status`, `bodem_sokm`) VALUES
(1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chiphi`
--

CREATE TABLE `chiphi` (
  `chiphi_id` int(11) NOT NULL,
  `chiphi_ten` varchar(100) CHARACTER SET utf8 NOT NULL,
  `chiphi_donvitinh` varchar(45) CHARACTER SET utf8 NOT NULL,
  `chiphi_soluong` float NOT NULL,
  `chiphi_dongia` int(11) NOT NULL,
  `chiphi_ngay` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doituongkm`
--

CREATE TABLE `doituongkm` (
  `doituongkm_id` int(11) NOT NULL,
  `khuyenmai_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `hoadon_ngay` date NOT NULL,
  `hoadon_so` int(11) NOT NULL,
  `order_id` varchar(5) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `hoadon_slkh` int(2) NOT NULL,
  `hoadon_giovao` datetime NOT NULL,
  `hoadon_giora` datetime NOT NULL,
  `hoadon_sotien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadonchitiet`
--

CREATE TABLE `hoadonchitiet` (
  `hoadonchitiet_id` int(11) NOT NULL,
  `hoadon_ngay` date NOT NULL,
  `hoadon_so` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `hoadonchitiet_yeucau` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `hoadonchitiet_soluong` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `indexbanner`
--

CREATE TABLE `indexbanner` (
  `indexbanner_id` int(11) NOT NULL,
  `indexbanner_banner` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `indexslogan`
--

CREATE TABLE `indexslogan` (
  `indexslogan_id` int(11) NOT NULL,
  `indexslogan_banner` varchar(100) CHARACTER SET utf8 NOT NULL,
  `indexslogan_slogan` mediumtext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Đang đổ dữ liệu cho bảng `indexslogan`
--

INSERT INTO `indexslogan` (`indexslogan_id`, `indexslogan_banner`, `indexslogan_slogan`) VALUES
(1, 'thiet-ke-website-do-an-vie-food1_03.png', 'NGON!');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `khuyenmai_id` int(11) NOT NULL,
  `khuyenmai_ten` mediumtext NOT NULL,
  `khuyenmai_mota` mediumtext NOT NULL,
  `khuyenmai_batdau` datetime NOT NULL,
  `khuyenmai_ketthuc` datetime NOT NULL,
  `khuyenmai_hinhanh` varchar(100) DEFAULT NULL,
  `khuyenmai_giatri` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mabaove`
--

CREATE TABLE `mabaove` (
  `mbv_id` int(11) NOT NULL,
  `mbv_giatri` varchar(45) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mabaove`
--

INSERT INTO `mabaove` (`mbv_id`, `mbv_giatri`) VALUES
(1, '6H3T8'),
(2, 'UXP4D'),
(3, '459CT'),
(4, 'W93BX'),
(5, 'caudzoa'),
(6, 'faupersc'),
(7, 'busneste'),
(8, 'wantle'),
(9, '748em'),
(10, '468l3');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_ten` varchar(100) CHARACTER SET utf8 NOT NULL,
  `menu_gia` int(11) NOT NULL,
  `menu_loai` int(1) NOT NULL,
  `menu_mota` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `menu_hinhanh` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `menu_ngaythem` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Đang đổ dữ liệu cho bảng `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_ten`, `menu_gia`, `menu_loai`, `menu_mota`, `menu_hinhanh`, `menu_ngaythem`) VALUES
(1, 'Trà sữa truyền thống', 12000, 0, '', 'thiet-ke-website-do-an-vie-food3_05.png', '2019-06-19'),
(2, 'Trà sữa matcha', 15000, 0, 'béo ngọt', 'thiet-ke-website-do-an-vie-food3_32.png', '2019-06-24'),
(3, 'Bánh Flan', 3000, 0, '', NULL, '2019-06-24'),
(4, 'Trà sữa khúc bạch', 15000, 0, '', NULL, '2019-06-24'),
(5, 'Milo sữa đá', 5000, 0, '', NULL, '2019-06-24'),
(6, 'Hồng trà', 5000, 0, '', 'thiet-ke-website-do-an-vie-food3_26.png', '2019-06-24'),
(7, 'Sữa tươi trân châu đường đen', 15000, 0, '', NULL, '2019-06-24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `nhanvien_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nhanvien_ten` varchar(100) CHARACTER SET utf8 NOT NULL,
  `nhanvien_hinhanh` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nhanvien_ngaysinh` date NOT NULL,
  `nhanvien_gioitinh` int(1) NOT NULL,
  `nhanvien_cmnd` varchar(12) CHARACTER SET utf8 NOT NULL,
  `nhanvien_diachi` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `nhanvien_sdt` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `nhanvien_vitri` varchar(45) CHARACTER SET utf8 NOT NULL,
  `nhanvien_batdau` date NOT NULL,
  `nhanvien_luong` int(11) NOT NULL,
  `nhanvien_tamung` int(11) DEFAULT NULL,
  `nhanvien_ngaytamung` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`nhanvien_id`, `user_id`, `nhanvien_ten`, `nhanvien_hinhanh`, `nhanvien_ngaysinh`, `nhanvien_gioitinh`, `nhanvien_cmnd`, `nhanvien_diachi`, `nhanvien_sdt`, `nhanvien_vitri`, `nhanvien_batdau`, `nhanvien_luong`, `nhanvien_tamung`, `nhanvien_ngaytamung`) VALUES
(1, 1, 'Huỳnh Văn Giỏi Em', 'nhanvien_1.jpg', '2019-11-23', 1, '352512250', 'Cần Thơ', '0335687425', 'Quản Trị Hệ Thống', '2019-06-01', 100000, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvienkhenthuong`
--

CREATE TABLE `nhanvienkhenthuong` (
  `nhanvienkhenthuong_id` int(11) NOT NULL,
  `nhanvien_id` int(11) NOT NULL,
  `nhanvienkhenthuong_ngay` datetime NOT NULL,
  `nhanvienkhenthuong_ten` mediumtext CHARACTER SET utf8 NOT NULL,
  `nhanvienkhenthuong_hinhthuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvienkyluat`
--

CREATE TABLE `nhanvienkyluat` (
  `nhanvienkyluat_id` int(11) NOT NULL,
  `nhanvien_id` int(11) NOT NULL,
  `nhanvienkyluat_ngay` datetime NOT NULL,
  `nhanvienkyluat_ten` mediumtext CHARACTER SET utf8 NOT NULL,
  `nhanvienkyluat_hinhthuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvienluong`
--

CREATE TABLE `nhanvienluong` (
  `nhanvienluong_id` int(11) NOT NULL,
  `nhanvien_id` int(11) NOT NULL,
  `nhanvienluong_sotien` int(11) NOT NULL,
  `nhanvienluong_ngaythanhtoan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvienngaynghi`
--

CREATE TABLE `nhanvienngaynghi` (
  `nhanvienngaynghi_id` int(11) NOT NULL,
  `nhanvien_id` int(11) NOT NULL,
  `nhanvienngaynghi_ngay` datetime NOT NULL,
  `nhanvienngaynghi_lydo` mediumtext CHARACTER SET utf8 NOT NULL,
  `nhanvienngaynghi_songay` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderchitiet`
--

CREATE TABLE `orderchitiet` (
  `orderchitiet_id` int(11) NOT NULL,
  `order_id` varchar(5) CHARACTER SET utf8 NOT NULL,
  `menu_id` int(11) NOT NULL,
  `orderchitiet_yeucau` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `orderchitiet_soluong` int(2) NOT NULL,
  `orderchitiet_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ordertable`
--

CREATE TABLE `ordertable` (
  `order_id` varchar(5) CHARACTER SET utf8 NOT NULL,
  `order_khu` varchar(1) CHARACTER SET utf8 NOT NULL,
  `order_soban` int(11) NOT NULL,
  `order_status` int(1) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_slkh` int(2) DEFAULT NULL,
  `order_giovao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ordertable`
--

INSERT INTO `ordertable` (`order_id`, `order_khu`, `order_soban`, `order_status`, `user_id`, `order_slkh`, `order_giovao`) VALUES
('1A', 'A', 1, 0, NULL, NULL, NULL),
('1B', 'B', 1, 0, NULL, NULL, NULL),
('2A', 'A', 2, 0, NULL, NULL, NULL),
('2B', 'B', 2, 0, NULL, NULL, NULL),
('3A', 'A', 3, 0, NULL, NULL, NULL),
('3B', 'B', 3, 0, NULL, NULL, NULL),
('4A', 'A', 4, 0, NULL, NULL, NULL),
('4B', 'B', 4, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongke`
--

CREATE TABLE `thongke` (
  `thongke_id` int(11) NOT NULL,
  `thongke_danhmuc` varchar(30) NOT NULL,
  `thongke_tu` date NOT NULL,
  `thongke_den` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `thongke`
--

INSERT INTO `thongke` (`thongke_id`, `thongke_danhmuc`, `thongke_tu`, `thongke_den`) VALUES
(1, 'LN', '2019-05-01', '2019-06-30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tuyendung`
--

CREATE TABLE `tuyendung` (
  `tuyendung_id` int(11) NOT NULL,
  `tuyendung_ten` mediumtext CHARACTER SET utf8 NOT NULL,
  `tuyendung_mota` mediumtext CHARACTER SET utf8 DEFAULT NULL,
  `tuyendung_file` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tuyendung_ngay` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `user_password` varchar(45) CHARACTER SET utf8 NOT NULL,
  `user_loai` int(11) NOT NULL,
  `user_ngaykichhoat` date NOT NULL,
  `user_permissmenu` int(1) NOT NULL,
  `user_permissoder` int(1) NOT NULL,
  `user_permissthongke` int(1) NOT NULL,
  `user_permisskhuyenmai` int(1) NOT NULL,
  `user_permissnhanvien` int(1) NOT NULL,
  `user_permissuser` int(1) NOT NULL,
  `user_permisschiphi` int(1) NOT NULL,
  `user_permisstrangchu` int(1) NOT NULL,
  `user_permisstuyendung` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_loai`, `user_ngaykichhoat`, `user_permissmenu`, `user_permissoder`, `user_permissthongke`, `user_permisskhuyenmai`, `user_permissnhanvien`, `user_permissuser`, `user_permisschiphi`, `user_permisstrangchu`, `user_permisstuyendung`) VALUES
(1, 'toilaai', 'f4843f082dfa143ff26c814c95fc0da0', 1, '2019-05-28', 1, 1, 1, 1, 1, 1, 1, 1, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bodem`
--
ALTER TABLE `bodem`
  ADD PRIMARY KEY (`bodem_id`);

--
-- Chỉ mục cho bảng `chiphi`
--
ALTER TABLE `chiphi`
  ADD PRIMARY KEY (`chiphi_id`),
  ADD KEY `fk_chiphi_user1_idx` (`user_id`);

--
-- Chỉ mục cho bảng `doituongkm`
--
ALTER TABLE `doituongkm`
  ADD PRIMARY KEY (`doituongkm_id`),
  ADD KEY `fk_doituongkhuyenmai_khuyenmai1_idx` (`khuyenmai_id`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`hoadon_ngay`,`hoadon_so`),
  ADD KEY `fk_hoadon_user1_idx` (`user_id`),
  ADD KEY `fk_hoadon_order1_idx` (`order_id`);

--
-- Chỉ mục cho bảng `hoadonchitiet`
--
ALTER TABLE `hoadonchitiet`
  ADD PRIMARY KEY (`hoadonchitiet_id`),
  ADD KEY `fk_hoadonchitiet_menu1_idx` (`menu_id`),
  ADD KEY `fk_hoadonchitiet_hoadon1_idx` (`hoadon_ngay`,`hoadon_so`);

--
-- Chỉ mục cho bảng `indexbanner`
--
ALTER TABLE `indexbanner`
  ADD PRIMARY KEY (`indexbanner_id`);

--
-- Chỉ mục cho bảng `indexslogan`
--
ALTER TABLE `indexslogan`
  ADD PRIMARY KEY (`indexslogan_id`);

--
-- Chỉ mục cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`khuyenmai_id`);

--
-- Chỉ mục cho bảng `mabaove`
--
ALTER TABLE `mabaove`
  ADD PRIMARY KEY (`mbv_id`);

--
-- Chỉ mục cho bảng `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`nhanvien_id`),
  ADD KEY `fk_nhanvien_user1_idx` (`user_id`);

--
-- Chỉ mục cho bảng `nhanvienkhenthuong`
--
ALTER TABLE `nhanvienkhenthuong`
  ADD PRIMARY KEY (`nhanvienkhenthuong_id`),
  ADD KEY `fk_nhanvienkhenthuong_nhanvien1_idx` (`nhanvien_id`);

--
-- Chỉ mục cho bảng `nhanvienkyluat`
--
ALTER TABLE `nhanvienkyluat`
  ADD PRIMARY KEY (`nhanvienkyluat_id`),
  ADD KEY `fk_nhanvienkyluat_nhanvien1_idx` (`nhanvien_id`);

--
-- Chỉ mục cho bảng `nhanvienluong`
--
ALTER TABLE `nhanvienluong`
  ADD PRIMARY KEY (`nhanvienluong_id`),
  ADD KEY `fk_nhanvienluong_nhanvien1_idx` (`nhanvien_id`);

--
-- Chỉ mục cho bảng `nhanvienngaynghi`
--
ALTER TABLE `nhanvienngaynghi`
  ADD PRIMARY KEY (`nhanvienngaynghi_id`),
  ADD KEY `fk_nhanvienngaynghi_nhanvien1_idx` (`nhanvien_id`);

--
-- Chỉ mục cho bảng `orderchitiet`
--
ALTER TABLE `orderchitiet`
  ADD PRIMARY KEY (`orderchitiet_id`),
  ADD KEY `fk_orderchitiet_order1_idx` (`order_id`),
  ADD KEY `fk_orderchitiet_menu1_idx` (`menu_id`);

--
-- Chỉ mục cho bảng `ordertable`
--
ALTER TABLE `ordertable`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_user1_idx` (`user_id`);

--
-- Chỉ mục cho bảng `thongke`
--
ALTER TABLE `thongke`
  ADD PRIMARY KEY (`thongke_id`);

--
-- Chỉ mục cho bảng `tuyendung`
--
ALTER TABLE `tuyendung`
  ADD PRIMARY KEY (`tuyendung_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chiphi`
--
ALTER TABLE `chiphi`
  MODIFY `chiphi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `doituongkm`
--
ALTER TABLE `doituongkm`
  MODIFY `doituongkm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoadonchitiet`
--
ALTER TABLE `hoadonchitiet`
  MODIFY `hoadonchitiet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `indexbanner`
--
ALTER TABLE `indexbanner`
  MODIFY `indexbanner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `indexslogan`
--
ALTER TABLE `indexslogan`
  MODIFY `indexslogan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `mabaove`
--
ALTER TABLE `mabaove`
  MODIFY `mbv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `nhanvien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `nhanvienkhenthuong`
--
ALTER TABLE `nhanvienkhenthuong`
  MODIFY `nhanvienkhenthuong_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhanvienkyluat`
--
ALTER TABLE `nhanvienkyluat`
  MODIFY `nhanvienkyluat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhanvienluong`
--
ALTER TABLE `nhanvienluong`
  MODIFY `nhanvienluong_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhanvienngaynghi`
--
ALTER TABLE `nhanvienngaynghi`
  MODIFY `nhanvienngaynghi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orderchitiet`
--
ALTER TABLE `orderchitiet`
  MODIFY `orderchitiet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thongke`
--
ALTER TABLE `thongke`
  MODIFY `thongke_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tuyendung`
--
ALTER TABLE `tuyendung`
  MODIFY `tuyendung_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chiphi`
--
ALTER TABLE `chiphi`
  ADD CONSTRAINT `fk_chiphi_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `fk_hoadon_order1` FOREIGN KEY (`order_id`) REFERENCES `ordertable` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_hoadon_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `hoadonchitiet`
--
ALTER TABLE `hoadonchitiet`
  ADD CONSTRAINT `fk_hoadonchitiet_hoadon1` FOREIGN KEY (`hoadon_ngay`,`hoadon_so`) REFERENCES `hoadon` (`hoadon_ngay`, `hoadon_so`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_hoadonchitiet_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_nhanvien_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhanvienkhenthuong`
--
ALTER TABLE `nhanvienkhenthuong`
  ADD CONSTRAINT `fk_nhanvienkhenthuong_nhanvien1` FOREIGN KEY (`nhanvien_id`) REFERENCES `nhanvien` (`nhanvien_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhanvienkyluat`
--
ALTER TABLE `nhanvienkyluat`
  ADD CONSTRAINT `fk_nhanvienkyluat_nhanvien1` FOREIGN KEY (`nhanvien_id`) REFERENCES `nhanvien` (`nhanvien_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhanvienluong`
--
ALTER TABLE `nhanvienluong`
  ADD CONSTRAINT `fk_nhanvienluong_nhanvien1` FOREIGN KEY (`nhanvien_id`) REFERENCES `nhanvien` (`nhanvien_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhanvienngaynghi`
--
ALTER TABLE `nhanvienngaynghi`
  ADD CONSTRAINT `fk_nhanvienngaynghi_nhanvien1` FOREIGN KEY (`nhanvien_id`) REFERENCES `nhanvien` (`nhanvien_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `orderchitiet`
--
ALTER TABLE `orderchitiet`
  ADD CONSTRAINT `fk_orderchitiet_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orderchitiet_order1` FOREIGN KEY (`order_id`) REFERENCES `ordertable` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `ordertable`
--
ALTER TABLE `ordertable`
  ADD CONSTRAINT `fk_order_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
