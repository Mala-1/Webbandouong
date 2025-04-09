-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 06:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_douong`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `name`, `image`) VALUES
(1, '333', '333.png'),
(2, 'bivina', 'bivina.jpg'),
(3, 'budweiser', 'budweiser.jpg'),
(4, 'corona', 'corona.jpg'),
(5, 'heineken', 'heineken.png'),
(6, 'hoegaarden', 'hoegaarden.jpg'),
(7, 'huda', 'huda.jpg'),
(8, 'kronenbourg-1664-blanc', 'kronenbourg-1664-blanc.jpg'),
(9, 'larue', 'larue.png'),
(10, 'sai-gon', 'sai-gon.jpg'),
(11, 'san-migue', 'san-migue.jpg'),
(12, 'somersby', 'somersby.jpg'),
(13, 'strongbow', 'strongbow.jpg'),
(14, 'tiger', 'tiger.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `image`) VALUES
(1, 'Nước ngọt', 'nuoc-ngot.png'),
(2, 'Cà phê hòa tan', 'ca-phe-hoa-tan.png'),
(3, 'Nước ép trái cây', 'nuoc-ep-trai-cay.png'),
(4, 'Bia, nước có cồn', 'bia.png'),
(5, 'Nước suối', 'nuoc-suoi.png'),
(6, 'Nước tăng lực', 'nuoc-tang-luc.png'),
(7, 'Nước trà', 'nuoc-tra.png'),
(8, 'Nước yến', 'nuoc-yen.jpg'),
(9, 'Rượu', 'ruou.png'),
(10, 'Sữa', 'sua.png');

-- --------------------------------------------------------

--
-- Table structure for table `import_order`
--

CREATE TABLE `import_order` (
  `import_order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_order_details`
--

CREATE TABLE `import_order_details` (
  `import_order_detail_id` int(11) NOT NULL,
  `import_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `packaging_option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `payment_method_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `packaging_option_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packaging_options`
--

CREATE TABLE `packaging_options` (
  `packaging_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `packaging_type` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` int(10) NOT NULL,
  `unit_quantity` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_options`
--

INSERT INTO `packaging_options` (`packaging_option_id`, `product_id`, `packaging_type`, `stock`, `image`, `price`, `unit_quantity`) VALUES
(1, 2, 'Lon', 0, NULL, 0, '1 lon'),
(2, 2, 'Lốc', 0, 'loc-6-lon-tiger-bac.jpg', 110000, '6 lon'),
(3, 2, 'Thùng', 0, 'thung-24-lon-bia-tiger-bac.jpg', 400000, '24 lon'),
(4, 4, 'Lon', 0, NULL, 0, '1 lon'),
(5, 4, 'Lốc', 0, 'loc-6-lon-bia-tiger-platinum-wheat-lager.jpg', 125000, '6 lon'),
(6, 4, 'Thùng', 0, 'thung-20-lon-bia-tiger-platinum-wheat-lager.jpg', 386000, '20 lon'),
(7, 3, 'Lon', 0, NULL, 0, '1 lon'),
(8, 3, 'Lốc', 0, '4-lon-bia-tiger-soju-cheeky-plum.jpg', 83000, '4 lon'),
(9, 3, 'Thùng', 0, 'thung-20-lon-bia-tiger-soju-cheeky-plum.jpg', 405000, '20 lon'),
(10, 1, 'Lon', 0, NULL, 0, '1 lon'),
(11, 1, 'Lốc', 0, '4-lon-bia-tiger-soju-wonder-melon.jpg', 83000, '4 lon'),
(12, 1, 'Thùng', 0, 'thung-20-lon-bia-tiger-soju-wonder-melon.jpg', 405000, '20 lon'),
(13, 5, 'Lon', 0, NULL, 0, '1 lon'),
(14, 5, 'Lốc', 0, '6-lon-bia-sai-gon-lager-330ml.jpg', 70500, '6 lon'),
(15, 5, 'Thùng', 0, 'thung-24-lon-bia-sai-gon-lager-330ml.jpg', 26000, '24 lon'),
(16, 6, 'Lon', 0, NULL, 0, '1 lon'),
(17, 6, 'Lốc', 0, '6-lon-bia-sai-gon-export-premium-330ml-.jpg', 72000, '6 lon'),
(18, 6, 'Thùng', 0, 'thung-24-lon-bia-sai-gon-export-premium-330ml.jpg', 279000, '24 lon'),
(19, 7, 'Lon', 0, NULL, 0, '1 lon'),
(20, 7, 'Lốc', 0, '6-lon-bia-sai-gon-chill-330m.jpg', 99500, '6 lon'),
(21, 7, 'Thùng', 0, 'thung-24-lon-bia-sai-gon-chill-330ml.jpg', 385000, '24 lon'),
(22, 7, 'Thùng A', 0, 'thung-18-lon-bia-sai-gon-chill-330ml.jpg', 300000, '18 lon'),
(23, 8, 'Lon', 0, NULL, 0, '1 lon'),
(24, 8, 'Lốc', 0, '6-lon-bia-sai-gon-special-sleek-330ml.jpg', 91000, '6 lon'),
(25, 8, 'Thùng', 0, 'thung-24-lon-bia-sai-gon-special-sleek-330m.jpg', 345000, '24 lon'),
(26, 9, 'Lon', 0, NULL, 0, '1 lon'),
(27, 9, 'Lốc', 0, '6-lon-bia-heineken-silver-250ml.jpg', 94000, '6 lon'),
(28, 9, 'Thùng', 0, 'thung-24-lon-bia-heineken-silver-250ml.jpg', 349000, '24 lon'),
(29, 11, 'Lon', 0, NULL, 0, '1 lon'),
(30, 11, 'Lốc', 0, '412208-3-1_202501031413302535.jpg', 116000, '6 lon'),
(31, 11, 'Thùng', 0, '412208-4_202501031416211106.jpg', 445000, '24 lon'),
(32, 12, 'Lon', 0, NULL, 0, '1 lon'),
(33, 12, 'Thùng', 0, 'slide-3_202411040946382291.jpg', 355000, '28 lon'),
(34, 13, 'Lon', 0, NULL, 0, '1 lon'),
(35, 13, 'Lốc', 0, '6-lon-bia-budweiser-330ml-202110111020026125.jpg', 110000, '6 lon'),
(36, 13, 'Thùng A', 0, '412208_202412280909557313.jpg', 295000, '20 lon'),
(37, 14, 'Chai', 0, NULL, 0, '1 chai'),
(38, 14, 'Thùng', 0, 'thung-24-chai-budweiser-330ml-202103162327047901.jpg', 499000, '24 chai'),
(39, 15, 'Lon', 0, NULL, 0, '1 lon'),
(40, 15, 'Lốc', 0, '6-lon-bia-333-330ml-202103162311002405.jpg', 72500, '6 lon'),
(41, 15, 'Thùng', 0, 'thung-24-lon-bia-333-330ml-202003251341353307.jpg', 279000, '24 lon'),
(42, 16, 'Chai', 0, NULL, 0, '1 chai'),
(43, 16, 'Lốc', 0, '6-chai-bia-corona-extra-300ml-202310071329406112.jpg', 195000, '6 chai'),
(44, 16, 'Thùng', 0, 'thung-24-chai-bia-corona-extra-300ml-202309182038488193.jpg', 780000, '24 chai'),
(45, 17, 'Lon', 0, NULL, 0, '1 lon'),
(46, 17, 'Lốc', 0, 'strongbow-kiwi-va-thanh-long-lon-320ml-clone-202407101334461833.jpg', 122000, '6 lon'),
(47, 17, 'Thùng', 0, 'loc-6-lon-strongbow-kiwi-va-thanh-long-lon-320ml-clone-202407101338018343.jpg', 477000, '24 lon'),
(48, 18, 'Lon', 0, NULL, 0, '1 lon'),
(49, 18, 'Lốc', 0, 'loc-6-lon-strongbow-thom-va-luu-lon-320ml-202407101319115896.jpg', 122000, '6 lon'),
(50, 18, 'Thùng', 0, 'loc-6-lon-strongbow-thom-va-luu-lon-320ml-clone-202407101323141511.jpg', 477000, '24 lon'),
(54, 19, 'Lon', 0, NULL, 0, '1 lon'),
(55, 19, 'Lốc', 0, 'x-7_202411232125073563.jpg', 122000, '6 lon'),
(56, 19, 'Thùng', 0, 'x-2_202411222212549896.jpg', 477000, '24 lon'),
(57, 20, 'Lon', 0, NULL, 0, '1 lon'),
(58, 20, 'Lốc', 0, '6-lon-bia-budweiser-500ml-202103162341105242.jpg', 175000, '6 lon'),
(59, 20, 'Thùng', 0, 'thung-12-lon-bia-budweiser-500ml-202103162311285122.jpg', 349000, '12 lon'),
(60, 21, 'Lon', 0, NULL, 0, '1 lon'),
(61, 21, 'Thùng', 0, 'bia-333-pilsner-extra-smooth-lon-330ml-clone-202407171025584332.jpg', 335000, '24 lon'),
(62, 22, 'Lon', 0, NULL, 0, '1 chai'),
(63, 11, 'Lốc', 0, '6-chai-bia-hoegaarden-rosee-248ml-202103162255108112.jpg', 119000, '6 chai'),
(64, 22, 'Thùng', 0, 'thung-24-chai-bia-hoegaarden-rosee-248ml-202103170012177723.jpg', 460000, '24 chai'),
(65, 23, 'Lon', 0, NULL, 0, '1 lon'),
(66, 23, 'Lốc', 0, '6-lon-hoegaarden-white-330ml-202103162340330676.jpg', 181000, '6 lon'),
(67, 23, 'Thùng', 0, 'thung-24-lon-hoegaarden-white-330ml-202307281021092236.jpg', 690000, '24 lon'),
(68, 24, 'Chai', 0, NULL, 0, '1 chai'),
(69, 24, 'Lốc', 0, '6-chai-bia-hoegaarden-330ml-202105291336192265.jpg', 191000, '6 chai'),
(70, 24, 'Thùng', 0, 'thung-24-chai-bia-hoegaarden-330ml-202103162232104264.jpg', 800000, '24 chai'),
(71, 25, 'Lon', 0, NULL, 0, '1 lon'),
(72, 25, 'Lốc', 0, '6-lon-bia-huda-330ml-202309191329088559.jpg', 72500, '6 lon'),
(73, 25, 'Thùng', 0, 'thung-24-lon-bia-huda-330ml-202309191319343524.jpg', 275000, '24 lon'),
(74, 26, 'Lon', 0, NULL, 0, '1 lon'),
(75, 25, 'Thùng', 0, 'frame-3-4_202501031028028896.jpg', 262000, '24 lon'),
(76, 27, 'Lon', 0, NULL, 0, '1 lon'),
(77, 27, 'Thùng', 0, 'frame-3-2_202501031005435306.jpg', 265000, '24 lon'),
(78, 28, 'Lon', 0, NULL, 0, '1 lon'),
(79, 28, 'Lốc', 0, '6-lon-bia-larue-special-330ml-202303172038334130.jpg', 71500, '6 lon'),
(80, 28, 'Thùng', 0, 'thung-24-lon-bia-larue-special-330ml-202303130900306319.jpg', 268000, '24 lon'),
(81, 29, 'Lon', 0, NULL, 0, '1 lon'),
(82, 29, 'Lốc', 0, 'loc-6-lon-bia-blanc-1664-330ml-202407111440578192.jpg', 117000, '6 lon'),
(83, 29, 'Thùng', 0, 'thung-24-lon-bia-blanc-1664-330ml-202403011442206439.jpg', 410000, '24 lon'),
(84, 30, 'Lon', 0, NULL, 0, '1 lon'),
(85, 30, 'Lốc', 0, 'thung-6-lon-nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-tang-1-ong-lays-100g-vi-bat-ki-202408270949151109.jpg', 122000, '6 lon'),
(86, 30, 'Thùng', 0, 'thung-12-lon-nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-330ml-202211171010197682.jpg', 242000, '12 lon'),
(87, 30, 'Thùng', 0, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-clone-202401261601096353.jpg', 484000, '24 lon'),
(88, 31, 'Lon', 0, NULL, 0, '1 lon'),
(89, 31, 'Lốc', 0, '6-lon-bia-trai-cay-san-miguel-vi-vai-330ml-202104290934520276.jpg', 107000, '6 lon'),
(90, 31, 'Thùng', 0, 'thung-24-lon-bia-trai-cay-san-miguel-vi-vai-330ml-202105061025057813.jpg', 398000, '24 lon'),
(91, 32, 'Lon', 0, NULL, 0, '1 lon'),
(92, 32, 'Lốc', 0, 'slide_202410021413525790.jpg', 94000, '4 lon'),
(93, 32, 'Thùng', 0, 'bia-san-miguel-red-horse-thung-12-lon-cao-500ml-201812011311509389.jpg', 279000, '12 lon'),
(94, 33, 'Lon', 0, NULL, 0, '1 lon'),
(95, 33, 'Thùng', 0, 'thung-24-lon-bia-bivina-export-330ml-202306210939090700.jpg', 229000, '24 lon');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `name`) VALUES
(1, 'Thanh toán khi nhận hàng (COD)'),
(2, 'Chuyển khoản ngân hàng'),
(3, 'Thanh toán qua ví Momo');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `name`) VALUES
(1, 'Quản lý sản phẩm'),
(2, 'Quản lý đơn hàng'),
(3, 'Quản lý người dùng'),
(4, 'Quản lý đơn nhập'),
(5, 'Xem báo cáo'),
(6, 'Quản lý quyền');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(10) NOT NULL,
  `category_id` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category_id`, `size`, `brand_id`, `origin`) VALUES
(1, 'Bia Tiger Soju Wonder Melon vị dưa lưới 330ml', 'Bia Tiger quen thuộc được kết hợp cùng rượu soju dưa lưới vô cùng mới lạ, đảm bảo chính hãng bia Tiger nổi tiếng. Bia Tiger Soju Cheeky Plum dưa lưới 330ml với 20% rượu soju, hương vị dưa lưới thơm ngon, mang đến trải nghiệm uống dễ chịu, thích thú', 21000, 4, '330ml', 14, 'Việt Nam'),
(2, 'Bia Tiger Bạc 330ml', 'Loại bia còn gọi là bia Tiger bạc được sản xuất theo quy trình Cold Suspension độc đáo (kỹ thuật làm lạnh sâu đến -1 độ C). Bia Tiger Bạc 330ml với hoa bia được tinh chế đặc biệt giúp lưu giữ trọn vẹn hương vị tuyệt hảo vốn có của bia Tiger', 18800, 4, '330ml', 14, 'Việt Nam'),
(3, 'Bia Tiger Soju Cheeky Plum vị mận 330ml', 'Sản phẩm bia kết hợp cùng rượu soju hương vị trái cây vô cùng hấp dẫn, chính hãng bia Tiger nổi tiếng. Bia Tiger Soju Cheeky Plum vị mận 330ml với 20% rượu soju mận thơm mát, vị hài hòa dễ uống mang đến cho bạn trải nghiệm thú vị, thích thú, uống sảng khoái', 21000, 4, '330ml', 14, 'Việt Nam'),
(4, 'Bia Tiger Platinum Wheat Lager lon 330ml', 'Được lên men tự nhiên từ các thành phần nước, đại mạch, lúa mì, ngũ cốc, hoa bia, bổ sung thêm vỏ cam, hạt râu mùi, Pectin táo, hương cam giống tự nhiên và men, sản phẩm chính hãng bia Tiger nổi tiếng. Bia Tiger Platinum Wheat Lager lon 330ml mang hương vị độc đáo khác biệt', 21000, 4, '330ml', 14, 'Việt Nam'),
(5, 'Bia Sài Gòn Lager 330ml', 'Bia bược sản xuất tại Việt Nam từ nước, malt đại mạch, ngũ cốc và hoa bia, chính hãng bia Sài Gòn. Bia Sài Gòn Lager 330ml có hương vị đậm đà, thơm ngon, cùng hương thơm ngũ cốc dễ chịu giúp bạn thăng hoa hơn, sản khoái hơn trong những cuộc vui cùng gia đình và bạn bè.', 12000, 4, '330ml', 10, 'Việt Nam'),
(6, 'Bia Sài Gòn Export Premium 330ml', 'Bia chính hãng thương hiệu Bia Sài Gòn nổi tiếng tại Việt Nam, thừa hưởng công thức truyền đời từ năm 1875 đến nay. Bia Sài Gòn Export Premium 330ml được ủ với công nghệ lên men chậm, mang đến hương vị bia ngon tuyệt hảo và chất bia êm đằm mà sảng khoái', 12400, 4, '330ml', 10, 'Việt Nam'),
(7, 'Bia Sài Gòn Chill 330ml', 'Được sản xuất tại Việt Nam từ nước, malt đại mạch và hoa bia. Sản phẩm bia mới của thương hiệu bia Sài Gòn là bia Sài Gòn Chill lon 330ml với người dân Việt Nam với hương vị thơm ngon, đậm đà, dễ uống giúp bạn thăng hoa hơn, sảng khoái hơn trong những cuộc vui cùng gia đình và bạn bè, cực chill.', 17600, 4, '330ml', 10, 'Việt Nam'),
(8, 'Bia Sài Gòn Special Sleek 330ml', 'Sản phẩm bia ngon chất lượng từ nước, malt đại mạch và hoa bia, cam kết kết chính hãng thương hiệu bia Sài Gòn. Bia Sài Gòn Special Sleek 330ml đã quá quen thuộc với người dân Việt Nam với hương vị thơm ngon, đậm đà, nay thiết kế lon cao thanh lịch sang trọng mang đến sự đẳng cấp hơn', 15600, 4, '330ml', 10, 'Việt Nam'),
(9, 'Bia Heineken Silver 250ml', 'Chất lượng từ thương hiệu bia được ưa chuộng tại hơn 192 quốc gia trên thế giới đến từ Hà Lan. Bia Heineken Silver 250ml mang hương vị đặc trưng thơm ngon hương vị bia tuyệt hảo, cân bằng và êm dịu. Bên cạnh đó là thiết kế đẹp mắt, cho người dùng cảm giác sang trọng, nâng tầm đẳng cấp.\r\n', 15900, 4, '250ml', 5, 'Việt Nam'),
(11, 'Bia Heineken Sleek 330ml', 'Chất lượng từ thương hiệu bia được ưa chuộng tại hơn 192 quốc gia trên thế giới đến từ Hà Lan. Bia Heineken 330ml mang hương vị đặc trưng thơm ngon hương vị bia tuyệt hảo, cân bằng và êm dịu. Bên cạnh đó là thiết kế đẹp mắt, cho người dùng cảm giác sang trọng, nâng tầm đẳng cấp.', 20500, 4, '330ml', 5, 'Việt Nam'),
(12, 'Bia Budweiser 250ml', 'Bia Mỹ thơm ngon, chính hãng bia Budweiser đậm đà được sản xuất từ mạch nha cùng với hoa bia thượng hạng Hoa Kỳ và Châu Âu. Bia Budweiser lon 250ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia có thiết kế sang trọng, hiện đại thêm phần đẳng cấp', 15900, 4, '250ml', 3, 'Việt Nam'),
(13, 'Bia Budweiser 330ml', 'Bia Mỹ thơm ngon, chính hãng bia Budweiser đậm đà được sản xuất từ mạch nha cùng với hoa bia thượng hạng Hoa Kỳ và Châu Âu. Bia Budweiser lon 330ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia có thiết kế sang trọng, hiện đại thêm phần đẳng cấp', 18600, 4, '330ml', 3, 'Việt Nam'),
(14, 'Bia Budweiser 330ml', 'Hương vị bia là sự cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Công nghệ ủ bia bằng gỗ sồi của bia Budweiser đã tạo ra một hương vị tuyệt hảo không nhầm lẫn vào đâu được. Bia Budweiser chai 330ml thủy tinh sang trọng và đẳng cấp không thể bỏ qua', 22000, 4, '330ml', 3, 'Việt Nam'),
(15, 'Bia 333 330ml', 'Thơm ngon, màu bia vàng đẹp mắt, hương vị đậm đà mạnh mẽ, khẳng định đẳng cấp phái mạnh, chính hãng bia 333. Bia 333 330ml chất lượng bảo đảm an toàn vệ sinh từ thương hiệu nổi tiếng lâu đời tại Việt Nam cho những cuộc vui kéo dài, sảng khoái bên gia đình, bạn bè và những chiến hữu', 12400, 4, '330ml', 1, 'Việt Nam'),
(16, 'Bia Corona Extra 300ml', 'Hương vị bia Bỉ truyền thống thơm ngon rất được ưa chuộng trên thế giới, chính hãng bia Corona. Bia Corona Extra 300ml chai thủy tinh sang trọng cùng màu bia vàng tươi bắt mắt, mang đến cho bạn cảm giác uống sảng khoái, tươi mới và năng động', 34500, 4, '300ml', 4, 'Trung Quốc'),
(17, 'Strongbow Kiwi và thanh long 320ml', 'Strongbow Sparkling Ciders được lên men tự nhiên công phu và tỉ mỉ, Strongbow Kiwi và thanh long lon 320ml là sự kết hợp giữa bọt sủi - Sparkling, kích thích vị giác cùng hương vị độc đáo của kiwi và thanh long, tạo nên một thức uống sảng khoái, tươi mát cho mọi cuộc vui.', 21000, 4, '320ml', 13, 'Việt Nam'),
(18, 'Strongbow thơm và lựu 320ml', 'Strongbow Sparkling Ciders được lên men tự nhiên công phu và tỉ mỉ, Strongbow thơm và lựu lon 320ml là sự kết hợp giữa bọt sủi - Sparkling, kích thích vị giác cùng hương vị độc đáo của thơm và lựu, tạo nên một thức uống sảng khoái, tươi mát cho mọi cuộc vui.', 21000, 4, '320ml', 13, 'Việt Nam'),
(19, 'Strongbow táo 320ml', 'Nước táo lên men Strongbow là nước uống có cồn độ ngọt dịu và hậu vị sang trọng kéo dài. Strongbow táo lon 320ml vị táo nguyên bản sẽ làm cho bạn dễ chịu và khi thưởng thức sẽ không cảm nhận được nhiều vị cồn vì thức uống này được lên men trực tiếp từ trái cây', 21000, 4, '320ml', 13, 'Việt Nam'),
(20, 'Bia Budweiser 500ml', 'Thơm ngon đậm đà sản xuất từ mạch nha cùng hoa bia thượng hạng từ Hoa Kỳ và Châu Âu, sản phẩm chính hãng bia Budweiser. Bia Budweiser 500ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia thiết kế lon cao thanh lịch sang trọng, hiện đại, đẳng cấp.', 29500, 4, '500ml', 3, 'Việt Nam'),
(21, 'Bia 333 Pilsner Extra Smooth 330ml', 'Bia 333 Pilsner Extra Smooth lon 330ml là một loại bia được sản xuất với công nghệ ủ lạnh lâu từ Châu Âu, tạo nên vị bia êm cực êm và có nồng độ cồn 4.3%, Với mùi vị thơm nồng, đây là một loại bia dễ uống và phù hợp với nhiều đối tượng sử dụng. Bia 333 Pilsner được sản xuất trên quy trình công nghệ hiện đại, đảm bảo chất lượng và độ tươi mới.', 14200, 4, '330ml', 1, 'Việt Nam'),
(22, 'Bia Hoegaarden Rosée 248ml', 'Từ thương hiệu bia Bỉ nổi tiếng thế giới bia Hoegaarden. Bia Hoegaarden Rosée 248ml hương vị thơm ngon hòa quyện hương trái cây phúc bồn tử, dịu ngọt với 100% thành phần tự nhiên, nồng độ cồn 3.3% nhẹ nhàng, dễ chịu. Sản phẩm cam kết chính hãng, chất lượng và an toàn', 20500, 4, '248ml', 6, 'Việt Nam'),
(23, 'Bia Hoegaarden White 330ml\r\n\r\n', 'Từ các loại rau mùi cay kết hợp cùng vỏ cam Curacao, mang đến một hương vị bia không đâu sánh bằng. Bia Hoegaarden White 330ml thơm ngon hảo hạng, mát lạnh xua tan đi bao nhiêu muộn phiền và nóng nực của những ngày làm việc căng thẳng. Chất lượng từ thương hiệu bia Bỉ Hoegaarden', 31000, 4, '330ml', 6, 'Việt Nam'),
(24, 'Bia Hoegaarden 330ml', 'Với thành phần chính là các rau mùi cay kết hợp cùng vỏ cam Curacao, mang đến một hương vị bia không đâu sánh bằng. Bia Hoegaarden 330ml thơm ngon đậm đà chất lượng từ thương hiệu bia Bỉ nổi tiếng - bia Hoegaarden. Cam kết chính hãng, chất lượng và an toàn', 33000, 4, '330ml', 6, 'Việt Nam'),
(25, 'Bia Huda 330ml', 'Bia sản xuất theo quy trình hiện đại và không chứa hóa chất độc hại mang đến chất lượng hoàn hảo cho người sử dụng. Bia Huda 330ml​ có màu vàng óng, mùi thơm đặc trưng, hương vị đậm đà, hấp dẫn vị giác. Sản phẩm từ thương hiệu bia Huda chất lượng với công nghệ bia Đan Mạch ', 12400, 4, '330ml', 7, 'Việt Nam'),
(26, 'Bia Larue Xanh cao 330ml', 'Bia được sản xuất từ nguồn nguyên liệu thượng hạng cùng bí quyết nấu bia đến từ Châu Âu, chính hãng bia Larue. Bia Larue Xanh lon cao 330ml có mùi thơm đặc trưng, hương vị đậm đà, mang lại những trải nghiệm thú vị cho người thưởng thức. Cam kết bia chính hãng, uy tín, chất lượng', 11900, 4, '330ml', 9, 'Việt Nam'),
(27, 'Bia Larue Smooth Đà Nẵng 330ml', 'Bia được sản xuất từ nguồn nguyên liệu thượng hạng cùng bí quyết nấu bia đến từ Châu Âu, chính hãng bia Larue. Bia Larue Smooth Đà Nẵng 330ml có mùi thơm đặc trưng, hương vị đậm đà, mang lại những trải nghiệm thú vị cho người thưởng thức. Cam kết bia chính hãng, uy tín, chất lượng', 12200, 4, '330ml', 9, 'Việt Nam'),
(28, 'Bia Larue Special 330ml', 'Bia được sản xuất theo công nghệ hiện đại, mọi khâu từ tuyển chọn nguyên liệu tới chế biến, đóng gói đều diễn ra khép kín dưới sự giám sát và kiểm tra nghiêm ngặt, chính hãng bia Laure. Bia Larue Special 330ml hương vị bia đậm đà thơm ngon hấp dẫn.', 12300, 4, '330ml', 9, 'Việt Nam'),
(29, 'Bia Blanc 1664 330ml', 'Tinh túy hương vị bia Pháp từ năm 1664. Bia Blanc 1664 lon 330ml trên dây chuyền hiện đại cùng bí quyết nấu bia tuyệt hảo cho cảm giác uống sảng khoái đầy thích thú. Bia lon tiện dụng, thiết kế hiện đại, bia Blac1664 cam kết chính hãng và chất lượng', 19000, 4, '330ml', 8, 'Việt Nam'),
(30, 'Nước táo lên men Somersby Blackberry vị mâm xôi 330ml\r\n\r\n', 'Đây là đồ uống có cồn thơm ngon hấp dẫn được sản xuất tại Malaysia với công nghệ hiện đại tiên tiến của tập đoàn Carlsberg. Nước táo lên men Somersby vị mâm xôi 330ml chính hãng nước táo lên men Somersby thơm ngon hấp dẫn, vị dễ uống, thiết kế trẻ trung hiện đại, phù hợp với giới trẻ', 20500, 4, '330ml', 12, 'Malaysia'),
(31, 'Bia trái cây San Miguel vị vải 330ml', 'Chất bia vàng nhạt sánh mịn, dễ uống, khơi gợi vị bia trái cây tươi mát với hương vị vải riêng biệt, ngọt ngào và tinh tế, nồng độ cồn 3 độ. Bia trái cây San Miguel vị vải 330ml mang đến trải nghiệm vô cùng thú vị. Cam kết bia chính hãng bia San Miguel chất lượng, uy tín', 18100, 4, '330ml', 11, 'Việt Nam'),
(32, 'Bia San Miguel Red Horse 500ml', 'Bia làm từ hoa bia, ngũ cốc, nước cùng các hương liệu khác và được sản xuất theo phương pháp truyền thống của người Tây Ban Nha đem đến sắc bia vàng óng ả có hương vị hết sức lạ miệng. Bia San Miguel Red Horse 500ml nồng độ 8% mạnh mẽ, chính hãng bia San Miguel chất lượng quốc tế', 25000, 4, '500ml', 11, 'Việt Nam'),
(33, 'Bia Bivina Export 330ml\r\n\r\n', 'Sản phẩm bia uống thơm ngon chất lượng, vị êm đằm sảng khoái, nồng độ 4.3% dễ uống, phù hợp với nhiều đối tượng. Bia Bivina Export lon 330ml chính hãng bia Bivina thuộc tập đoàn Heineken nổi tiếng cho bạn những cuộc vui hết mình, uống cực đã', 11700, 4, '330ml', 2, 'Việt Nam');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image`) VALUES
(1, 2, 'bia-tiger-bac-01.jpg'),
(2, 4, 'bia-tiger-platinum-wheat-lager-330ml-01.jpg'),
(3, 4, 'bia-tiger-platinum-wheat-lager-330ml-02.jpg'),
(4, 4, 'bia-tiger-platinum-wheat-lager-330ml-03.jpg'),
(5, 4, 'bia-tiger-platinum-wheat-lager-330ml-04.jpg'),
(6, 4, 'bia-tiger-platinum-wheat-lager-330ml-05.jpg'),
(7, 3, 'tiger-soju-cheeky-plum-01.jpg'),
(8, 3, 'tiger-soju-cheeky-plum-02.jpg'),
(9, 3, 'tiger-soju-cheeky-plum-03.jpg'),
(10, 3, 'tiger-soju-cheeky-plum-04.jpg'),
(11, 3, 'tiger-soju-cheeky-plum-05.jpg'),
(12, 1, 'tiger-soju-wonder-melon-01.jpg'),
(13, 1, 'tiger-soju-wonder-melon-02.jpg'),
(14, 1, 'tiger-soju-wonder-melon-03.jpg'),
(15, 1, 'tiger-soju-wonder-melon-04.jpg'),
(16, 1, 'tiger-soju-wonder-melon-05.jpg'),
(17, 1, 'tiger-soju-wonder-melon-06.jpg'),
(18, 5, 'bia-sai-gon-lager-330ml-01.jpg'),
(19, 5, 'bia-sai-gon-lager-330ml-02.jpg'),
(20, 5, 'bia-sai-gon-lager-330ml-03.jpg'),
(21, 5, 'bia-sai-gon-lager-330ml-04.jpg'),
(22, 5, 'bia-sai-gon-lager-330ml-05.jpg'),
(23, 6, 'bia-sai-gon-export-premium-330ml-01.jpg'),
(24, 6, 'bia-sai-gon-export-premium-330ml-02.jpg'),
(25, 6, 'bia-sai-gon-export-premium-330ml-03.jpg'),
(26, 6, 'bia-sai-gon-export-premium-330ml-04.jpg'),
(27, 6, 'bia-sai-gon-export-premium-330ml-05.jpg'),
(28, 7, 'bia-sai-gon-chill-lon-330ml-01.jpg'),
(29, 7, 'bia-sai-gon-chill-lon-330ml-02.jpg'),
(30, 7, 'bia-sai-gon-chill-lon-330ml-03.jpg'),
(31, 7, 'bia-sai-gon-chill-lon-330ml-04.jpg'),
(32, 7, 'bia-sai-gon-chill-lon-330ml-05.jpg'),
(33, 8, 'bia-sai-gon-special-sleek-lon-330ml-01.jpg'),
(34, 8, 'bia-sai-gon-special-sleek-lon-330ml-02.jpg'),
(35, 8, 'bia-sai-gon-special-sleek-lon-330ml-03.jpg'),
(36, 8, 'bia-sai-gon-special-sleek-lon-330ml-04.jpg'),
(37, 8, 'bia-sai-gon-special-sleek-lon-330ml-05.jpg'),
(38, 9, 'bia-heineken-silver-250ml-01.jpg'),
(39, 9, 'bia-heineken-silver-250ml-02.jpg'),
(40, 9, 'bia-heineken-silver-250ml-03.jpg'),
(41, 9, 'bia-heineken-silver-250ml-04.jpg'),
(42, 11, 'bia-heineken-sleek-330ml-01.jpg'),
(43, 11, 'bia-heineken-sleek-330ml-02.jpg'),
(44, 11, 'bia-heineken-sleek-330ml-03.jpg'),
(45, 11, 'bia-heineken-sleek-330ml-04.jpg'),
(46, 12, 'Budweiser-01.jpg'),
(47, 12, 'Budweiser-02.jpg'),
(48, 12, 'Budweiser-03.jpg'),
(49, 12, 'Budweiser-04.jpg'),
(50, 12, 'Budweiser-05.jpg'),
(51, 13, 'bia-budweiser-lon-330ml-01.jpg'),
(52, 13, 'bia-budweiser-lon-330ml-02.jpg'),
(53, 13, 'bia-budweiser-lon-330ml-03.jpg'),
(54, 13, 'bia-budweiser-lon-330ml-04.jpg'),
(55, 13, 'bia-budweiser-lon-330ml-05.jpg'),
(56, 13, 'bia-budweiser-lon-330ml-06.jpg'),
(57, 14, 'bia-budweiser-330ml-01.jpg'),
(58, 14, 'bia-budweiser-330ml-02.jpg'),
(59, 14, 'bia-budweiser-330ml-03.jpg'),
(60, 14, 'bia-budweiser-330ml-04.jpg'),
(61, 14, 'bia-budweiser-330ml-05.jpg'),
(62, 15, 'bia-333-lon-330ml-01.jpg'),
(63, 15, 'bia-333-lon-330ml-02.jpg'),
(64, 15, 'bia-333-lon-330ml-03.jpg'),
(65, 15, 'bia-333-lon-330ml-04.jpg'),
(66, 16, 'bia-corona-extra-330ml-clone-01.jpg'),
(67, 16, 'bia-corona-extra-330ml-clone-02.jpg'),
(68, 16, 'bia-corona-extra-330ml-clone-03.jpg'),
(69, 16, 'bia-corona-extra-330ml-clone-04.jpg'),
(70, 16, 'bia-corona-extra-330ml-clone-05.jpg'),
(71, 17, 'strongbow-kiwi-va-thanh-long-lon-320ml-01.jpg'),
(72, 17, 'strongbow-kiwi-va-thanh-long-lon-320ml-02.jpg'),
(73, 18, 'strongbow-tao-lon-330ml-clone-202407101311136920.jpg'),
(74, 19, 'x-11_202411222233382085.jpg'),
(75, 20, 'bia-budweiser-500ml-01.jpg'),
(76, 20, 'bia-budweiser-500ml-02.jpg'),
(77, 20, 'bia-budweiser-500ml-03.jpg'),
(78, 20, 'bia-budweiser-500ml-lon-04.jpg'),
(79, 21, 'bia-333-pilsner-extra-smooth-lon-330ml-01.jpg'),
(80, 21, 'bia-333-pilsner-extra-smooth-lon-330ml-02.jpg'),
(81, 22, 'bia-hoegaarden-rosee-248ml-01.jpg'),
(82, 22, 'bia-hoegaarden-rosee-248ml-02.jpg'),
(83, 22, 'bia-hoegaarden-rosee-248ml-03.jpg'),
(84, 22, 'bia-hoegaarden-rosee-248ml-04.jpg'),
(85, 23, 'bia-hoegaarden-white-lon-330ml-01.jpg'),
(86, 23, 'bia-hoegaarden-white-lon-330ml-02.jpg'),
(87, 23, 'bia-hoegaarden-white-lon-330ml-03.jpg'),
(88, 23, 'bia-hoegaarden-white-lon-330ml-04.jpg'),
(89, 24, 'bia-hoegaarden-330ml-01.jpg'),
(90, 24, 'bia-hoegaarden-330ml-02.jpg'),
(91, 24, 'bia-hoegaarden-330ml-03.jpg'),
(92, 24, 'bia-hoegaarden-330ml-04.jpg'),
(93, 24, 'bia-hoegaarden-330ml-05.jpg'),
(94, 25, 'bia-huda-330ml-01.jpg'),
(95, 25, 'bia-huda-330ml-02.jpg'),
(96, 25, 'bia-huda-330ml-03.jpg'),
(97, 25, 'bia-huda-330ml-04.jpg'),
(98, 26, 'larue-xanh-cao-330ml-01.jpg'),
(99, 26, 'larue-xanh-cao-330ml-02.jpg'),
(100, 27, 'larue-smooth-danang-330ml-01.jpg'),
(101, 27, 'larue-smooth-danang-330ml-02.jpg'),
(102, 28, 'bia-larue-special-330ml-01.jpg'),
(103, 28, 'bia-larue-special-330ml-02.jpg'),
(104, 28, 'bia-larue-special-330ml-03.jpg'),
(105, 28, 'bia-larue-special-330ml-04.jpg'),
(106, 28, 'bia-larue-special-330ml-05.jpg'),
(107, 29, 'bia-blanc-1664-lon-330ml-01.jpg'),
(108, 29, 'bia-blanc-1664-lon-330ml-02.jpg'),
(109, 29, 'bia-blanc-1664-lon-330ml-03.jpg'),
(110, 29, 'bia-blanc-1664-lon-330ml-04.jpg'),
(111, 29, 'bia-blanc-1664-lon-330ml-05.jpg'),
(112, 29, 'bia-blanc-1664-lon-330ml-06.jpg'),
(113, 30, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-01.jpg'),
(114, 30, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-02.jpg'),
(115, 30, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-03.jpg'),
(116, 30, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-04.jpg'),
(117, 30, 'nuoc-tao-len-men-somersby-blackberry-vi-mam-xoi-lon-330ml-05.jpg'),
(118, 31, 'bia-trai-cay-san-miguel-vi-vai-lon-330ml-01.jpg'),
(119, 31, 'bia-trai-cay-san-miguel-vi-vai-lon-330ml-02.jpg'),
(120, 31, 'bia-trai-cay-san-miguel-vi-vai-lon-330ml-03.jpg'),
(121, 31, 'bia-trai-cay-san-miguel-vi-vai-lon-330ml-04.jpg'),
(122, 32, 'bia-san-miguel-red-horse-500ml-01.jpg'),
(123, 32, 'bia-san-miguel-red-horse-500ml-02.jpg'),
(124, 32, 'bia-san-miguel-red-horse-500ml-03.jpg'),
(125, 32, 'bia-san-miguel-red-horse-500ml-04.jpg'),
(126, 33, 'bia-bivina-export-lon-330ml-01.jpg'),
(127, 33, 'bia-bivina-export-lon-330ml-02.jpg'),
(128, 33, 'bia-bivina-export-lon-330ml-03.jpg'),
(129, 33, 'bia-bivina-export-lon-330ml-04.jpg'),
(130, 33, 'bia-bivina-export-lon-330ml-05.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `name`) VALUES
(1, 'user'),
(2, 'admin'),
(3, 'manager'),
(4, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_details`
--

CREATE TABLE `role_permission_details` (
  `role_permission_detail_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission_details`
--

INSERT INTO `role_permission_details` (`role_permission_detail_id`, `role_id`, `permission_id`, `action`) VALUES
(1, 2, 1, 'read'),
(2, 2, 1, 'write'),
(3, 2, 1, 'delete'),
(4, 2, 2, 'read'),
(5, 2, 2, 'write'),
(6, 2, 2, 'delete'),
(7, 2, 3, 'read'),
(8, 2, 3, 'write'),
(9, 2, 3, 'delete'),
(10, 2, 4, 'read'),
(11, 2, 4, 'write'),
(12, 2, 4, 'delete'),
(13, 2, 5, 'read'),
(14, 2, 5, 'write'),
(15, 2, 5, 'delete'),
(16, 2, 6, 'read'),
(17, 2, 6, 'write'),
(18, 2, 6, 'delete'),
(19, 3, 1, 'read'),
(20, 3, 1, 'write'),
(21, 3, 2, 'read'),
(22, 3, 2, 'write'),
(23, 3, 3, 'read'),
(24, 3, 3, 'write'),
(25, 3, 4, 'read'),
(26, 3, 4, 'write'),
(27, 3, 5, 'read'),
(28, 4, 1, 'read'),
(29, 4, 2, 'read'),
(30, 4, 2, 'write'),
(31, 4, 4, 'read');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `name`, `email`, `address`) VALUES
(1, 'Công ty TNHH Minh Tâm', 'minhtam@supplier.vn', '123 Nguyễn Trãi, Q5, TP.HCM'),
(2, 'Công ty CP StyleMax', 'stylemax@supplier.vn', '45 Lê Lai, Q1, TP.HCM'),
(3, 'Công ty TNHH Gia Khang', 'giakhang@supplier.vn', '78 Cộng Hòa, Q.Tân Bình, TP.HCM'),
(4, 'Công ty TNHH Song Hành', 'songhanh@supplier.vn', '56 Hai Bà Trưng, Q1, TP.HCM'),
(5, 'Công ty TNHH UrbanLook', 'urbanlook@supplier.vn', '88 Trần Hưng Đạo, Q5, TP.HCM'),
(6, 'Công ty TNHH Nhật Quang', 'nhatquang@supplier.vn', '101 Trường Chinh, Q.Tân Phú, TP.HCM'),
(7, 'Công ty TNHH ElegantCo', 'elegantco@supplier.vn', '12 Phạm Văn Đồng, Q.Thủ Đức, TP.HCM'),
(8, 'Công ty CP Hoàng Gia', 'hoanggia@supplier.vn', '99 Nguyễn Thái Học, Q1, TP.HCM'),
(9, 'Công ty TNHH Tâm Đức', 'tamduc@supplier.vn', '67 Phan Văn Trị, Q.Bình Thạnh, TP.HCM'),
(10, 'Công ty TNHH SmartWear', 'smartwear@supplier.vn', '88 Nguyễn Văn Linh, Q.7, TP.HCM'),
(11, 'Công ty TNHH Nam Phong', 'namphong@supplier.vn', '145 Trần Quang Khải, Q1, TP.HCM'),
(12, 'Công ty TNHH Fashina', 'fashina@supplier.vn', '22 Nguyễn Văn Cừ, Q10, TP.HCM'),
(13, 'Công ty CP Đại Hưng', 'daihung@supplier.vn', '200 Điện Biên Phủ, Q.Bình Thạnh, TP.HCM'),
(14, 'Công ty TNHH GoldStyle', 'goldstyle@supplier.vn', '33 Cách Mạng Tháng 8, Q3, TP.HCM'),
(15, 'Công ty TNHH Tín Nghĩa', 'tinnghia@supplier.vn', '77 Nguyễn Văn Đậu, Q.Bình Thạnh, TP.HCM'),
(16, 'Công ty TNHH EverVibe', 'evervibe@supplier.vn', '144 Lý Chính Thắng, Q3, TP.HCM'),
(17, 'Công ty TNHH Bình Minh', 'binhminh@supplier.vn', '10 Nguyễn Kiệm, Q.Phú Nhuận, TP.HCM'),
(18, 'Công ty TNHH M&T Distributors', 'mt@supplier.vn', '55 Võ Thị Sáu, Q1, TP.HCM'),
(19, 'Công ty TNHH Alpha Zone', 'alphazone@supplier.vn', '17 Nguyễn Hữu Cảnh, Q.Bình Thạnh, TP.HCM'),
(20, 'Công ty CP Phúc Hưng', 'phuchung@supplier.vn', '29 Phạm Ngũ Lão, Q1, TP.HCM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `import_order`
--
ALTER TABLE `import_order`
  ADD PRIMARY KEY (`import_order_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `import_order_details`
--
ALTER TABLE `import_order_details`
  ADD PRIMARY KEY (`import_order_detail_id`),
  ADD KEY `import_order_id` (`import_order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `packaging_option_id` (`packaging_option_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `packaging_option_id` (`packaging_option_id`);

--
-- Indexes for table `packaging_options`
--
ALTER TABLE `packaging_options`
  ADD PRIMARY KEY (`packaging_option_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permission_details`
--
ALTER TABLE `role_permission_details`
  ADD PRIMARY KEY (`role_permission_detail_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `import_order`
--
ALTER TABLE `import_order`
  MODIFY `import_order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_order_details`
--
ALTER TABLE `import_order_details`
  MODIFY `import_order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packaging_options`
--
ALTER TABLE `packaging_options`
  MODIFY `packaging_option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_permission_details`
--
ALTER TABLE `role_permission_details`
  MODIFY `role_permission_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `import_order`
--
ALTER TABLE `import_order`
  ADD CONSTRAINT `import_order_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  ADD CONSTRAINT `import_order_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `import_order_details`
--
ALTER TABLE `import_order_details`
  ADD CONSTRAINT `import_order_details_ibfk_1` FOREIGN KEY (`import_order_id`) REFERENCES `import_order` (`import_order_id`),
  ADD CONSTRAINT `import_order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `import_order_details_ibfk_3` FOREIGN KEY (`packaging_option_id`) REFERENCES `packaging_options` (`packaging_option_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`packaging_option_id`) REFERENCES `packaging_options` (`packaging_option_id`);

--
-- Constraints for table `packaging_options`
--
ALTER TABLE `packaging_options`
  ADD CONSTRAINT `packaging_options_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `role_permission_details`
--
ALTER TABLE `role_permission_details`
  ADD CONSTRAINT `role_permission_details_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `role_permission_details_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
