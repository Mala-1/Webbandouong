-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 02:56 PM
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
(14, 'tiger', 'tiger.jpg'),
(15, 'Coca cola', 'coca-cola-2309202010534.png'),
(16, 'Pepsi', 'pepsi-2307202415540.png'),
(17, 'fanta', 'fanta-15032021112040.jpg'),
(18, '7 up', 'frame-2_202503121056045336.jpg'),
(19, 'mirinda', 'mirinda_202411270859187901.jpg'),
(20, 'schweppes', 'schweppes-0504202194054.jpg'),
(21, 'sprite', 'sprite-24092020111818.png'),
(22, 'trà ô long', 'tea_202411270905119668.jpg'),
(23, 'Trà dr.thanh', 'drthanh-0504202113325.jpg'),
(24, '0 độ', 'khong-do-0604202113025.jpg'),
(25, 'c2', 'c2-14032021212318.jpg'),
(26, 'wonderfarm', 'wonderfarm-05042021173431.jpg'),
(27, 'boncha', '38142-id_202503251036372570.jpg'),
(28, 'red bull', 'redbull-04042021224712.jpg'),
(29, 'sting', 'sting-1509202216502.jpg'),
(30, 'revive', 'revice_202411270902102658.jpg'),
(31, 'lipovitan', 'lipovitan-0404202118632.jpg'),
(32, 'monster', 'monster-energy-14032021225816.jpg'),
(33, 'number 1', 'number1-150320219153.jpg'),
(34, 'aquafina', 'aquafina-15092022164854.jpg'),
(35, 'lavie', 'la-vie-16012023163848.jpg'),
(36, 'dasani', 'dasani-0404202118112.jpg'),
(37, 'good-mood', 'good-mood-15092022164718.jpg'),
(38, 'khanh-hoa', 'khanh-hoa-0604202103456.jpg'),
(39, 'sai-gon-anpha', 'sai-gon-anpha-0504202191210.jpg'),
(40, 'song-yen', 'song-yen-13032021151130.jpg'),
(41, 'green-bird', 'green-bird-05042021133923.jpg'),
(42, 'winsnest', 'winsnest-05042021173323.jpg'),
(43, 'nunest', 'nunest-1503202191439.jpg'),
(44, 'rice', 'rice-08122023134310.jpg'),
(45, 'twister', 'twister_202411270859599776.jpg'),
(46, 'vinamilk', 'vinamilk-12072023161451.jpg'),
(47, 'ice', 'ice-060420210170.jpg'),
(48, 'jele', 'jele-0604202103444.jpg'),
(49, 'teppy', 'teppy-230920209348.png'),
(50, 'a1-food', 'a1-food-2010202162610.jpg'),
(51, 'minute-maid', 'minute-maid-1403202122447.jpg'),
(52, 'mogu-mogu', 'mogu-mogu-14032021223718.jpg'),
(53, 'woongjin', 'woongjin-05042021173535.jpg'),
(54, 'deedo-fruitku', 'deedo-fruitku-1503202122850.jpg'),
(55, 'nutriboost', 'nutriboost-12032021113715.jpg'),
(56, 'yomost', 'yomost-0504202122036.jpg'),
(57, 'kirin', 'kirin-140320210131.jpg'),
(58, 'oggi', 'oggi-2025-03-18t101304016_202503181013127329.jpg'),
(59, 'kun', '23201-id_202501171604047009.jpg'),
(60, 'nescafe', 'nescafe-14032021235351.jpg'),
(61, 'vinacafe', 'vinacafe-13032021142756.jpg'),
(62, 'g7', 'g7-05042021145038.jpg'),
(63, 'wake-up', 'wake-up-1503202185914.jpg'),
(64, 'trung-nguyen', 'trung-nguyen-0504202116614.jpg'),
(65, 'maccoffee', 'maccoffee-13032021225237.jpg'),
(66, 'ong-bau', 'ong-bau-22032021132457.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 7, '2025-04-16 09:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `cart_detail_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `packaging_option_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`price` * `quantity`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`cart_detail_id`, `cart_id`, `packaging_option_id`, `quantity`, `price`) VALUES
(1, 1, 2, 12, 110000.00);

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
(95, 33, 'Thùng', 0, 'thung-24-lon-bia-bivina-export-330ml-202306210939090700.jpg', 229000, '24 lon'),
(96, 34, 'Chai', 0, NULL, 0, '1 chai'),
(97, 34, 'Lốc', 0, 'rh6y45_202410121036392022.jpg', 45000, '6 chai'),
(98, 34, 'Thùng', 0, 'thung-24-chai-nuoc-ngot-coca-cola-390ml-202401031354327265.jpg', 175000, '24 chai'),
(99, 35, 'Chai', 0, NULL, 0, '1 chai'),
(100, 35, 'Lốc', 0, 'j65yh65tg_202410100954258310.jpg', 45000, '6 chai'),
(101, 35, 'Thùng', 0, 'thung-24-chai-nuoc-ngot-coca-cola-390ml-202401031354327265.jpg', 175000, '24 chai'),
(102, 36, 'Lon', 0, NULL, 0, '1 lon'),
(103, 36, 'Lốc', 0, 'untitled-2_202410100917022096.jpg', 39000, '6 lon'),
(104, 36, 'Thùng', 0, 'nuoc-ngot-coke-zero-sleek-lon-320ml-thung-24-lon_202503260844576684.jpg', 138000, '24 lon'),
(105, 37, 'Lon', 0, NULL, 0, '1 lon'),
(106, 37, 'Lốc', 0, '6-lon-nuoc-ngot-coca-cola-light-330ml-202209111902227891.jpg', 61000, '6 lon'),
(107, 37, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-coca-cola-light-330ml-202209130026545869.jpg', 239000, '24 lon'),
(108, 38, 'Lon', 0, NULL, 0, '1 lon'),
(109, 38, 'Lốc', 0, '6-lon-nuoc-ngot-coca-cola-320ml-202303181532309738.jpg', 49000, '6 lon'),
(110, 38, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-coca-cola-320ml-202304170912479439.jpg', 180000, '24 lon'),
(111, 39, 'Chai', 0, NULL, 0, '1 chai'),
(112, 39, 'Lốc', 0, '543ed_202410101053449397.jpg', 119000, '6 chai'),
(113, 40, 'Lon', 0, NULL, 0, '1 lon'),
(114, 40, 'Lốc', 0, '6-lon-nuoc-ngot-pepsi-khong-calo-320ml-202405140930319101.jpg', 63000, '6 lon'),
(115, 40, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-pepsi-khong-calo-320ml-202405140932308541.jpg', 249000, '24 lon'),
(116, 41, 'Lon', 0, NULL, 0, '1 lon'),
(117, 41, 'Lốc', 0, '6-lon-nuoc-ngot-pepsi-cola-320ml-202405140913350279.jpg', 63000, '6 lon'),
(118, 41, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-pepsi-cola-320ml-202405140910323798.jpg', 249000, '24 lon'),
(119, 42, 'Lon', 0, NULL, 0, '1 lon'),
(120, 42, 'Lốc', 0, '6-lon-nuoc-ngot-pepsi-khong-calo-vi-chanh-320ml-202403141047526251.jpg', 63000, '6 lon'),
(121, 42, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-pepsi-khong-calo-vi-chanh-320ml-202403141042047286.jpg', 249000, '24 lon'),
(122, 43, 'Chai', 0, NULL, 0, '1 chai'),
(123, 43, 'Lốc', 0, '6-chai-nuoc-ngot-pepsi-khong-calo-390ml-202405131646146534.jpg', 45000, '6 chai'),
(124, 43, 'Thùng', 0, 'thung-24-chai-nuoc-ngot-pepsi-khong-calo-390ml-202405131648242013.jpg', 175000, '24 chai'),
(125, 44, 'Chai', 0, NULL, 0, '1 chai'),
(126, 44, 'Lốc', 0, '6-chai-nuoc-ngot-pepsi-cola-390ml-202405131544132338.jpg', 46000, '6 chai'),
(127, 44, 'Thùng', 0, 'thung-24-chai-nuoc-ngot-pepsi-cola-390ml-202405131540090674.jpg', 182000, '24 chai'),
(128, 45, 'Chai', 0, NULL, 0, '1 chai'),
(129, 45, 'Thùng', 0, 'thung-12-chai-nuoc-ngot-pepsi-cola-15-lit-202405131533460165.jpg', 230000, '12 chai'),
(130, 46, 'Lon', 0, NULL, 0, '1 lon'),
(131, 46, 'Lốc', 0, 'x-4_202411192106150084.jpg', 39000, '6 lon'),
(132, 46, 'Thùng', 0, 'z-3_202411041457060787.jpg', 138000, '24 lon'),
(133, 47, 'Lon', 0, NULL, 0, '1 lon'),
(134, 47, 'Lốc', 0, '-202211252250350400.jpg', 39000, '6 lon'),
(135, 47, 'Thùng', 0, 'z-7_202411041506073838.jpg', 138000, '24 lon'),
(136, 48, 'Lon', 0, NULL, 0, '1 lon'),
(137, 48, 'Lốc', 0, 'z-25_202411041512084868.jpg', 39000, '6 lon'),
(138, 48, 'Thùng', 0, 'z-24_202411041513527461.jpg', 138000, '24 lon'),
(139, 49, 'Lon', 0, NULL, 0, '1 lon'),
(140, 49, 'Lốc', 0, 'z-12_202411041615087225.jpg', 39000, '6 lon'),
(141, 49, 'Thùng', 0, 'z-11_202411041615591527.jpg', 138000, '24 lon'),
(142, 50, 'Lon', 0, NULL, 0, '1 lon'),
(143, 50, 'Lốc', 0, '6-lon-nuoc-ngot-soda-chanh-7-up-320ml-202308160848544121.jpg', 60000, '6 lon'),
(144, 50, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-soda-chanh-7-up-320ml-202308160850478764.jpg', 230000, '24 lon'),
(145, 51, 'Lon', 0, NULL, 0, '1 lon'),
(146, 51, 'Lốc', 0, '6-lon-nuoc-ngot-7-up-vi-chanh-320ml-202312252111462336.jpg', 61000, '6 lon'),
(147, 51, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-7-up-vi-chanh-320ml-202312252116245308.jpg', 240000, '24 lon'),
(148, 52, 'Chai', 0, NULL, 0, '1 chai'),
(149, 52, 'Lốc', 0, '6-chai-nuoc-ngot-7-up-vi-chanh-390ml-202407131705125343.jpg', 46000, '6 chai'),
(150, 52, 'Thùng', 0, 'thung-24-chai-nuoc-ngot-7-up-vi-chanh-390ml-202407091545538869.jpg', 182000, '24 chai'),
(151, 52, 'Chai', 0, NULL, 0, '1 chai'),
(152, 53, 'Thùng', 0, 'thung-12-chai-nuoc-ngot-7-up-vi-chanh-15-lit-202312252136117150.jpg', 230000, '12 chai'),
(153, 54, 'Lon', 0, NULL, 0, '1 lon'),
(154, 54, 'Lốc', 0, '195224_202411192119415521.jpg', 39000, '6 lon'),
(155, 54, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-sprite-huong-chanh-320ml-202407121623253988.jpg', 138000, '24 lon'),
(156, 55, 'Chai', 0, NULL, 0, '1 chai'),
(157, 55, 'Lốc', 0, '6-chai-nuoc-ngot-sprite-huong-chanh-15-lit-202308121643425033.jpg', 119000, '6 chai'),
(158, 56, 'Lon', 0, NULL, 0, '1 lon'),
(159, 56, 'Lốc', 0, '6-lon-soda-schweppes-330ml-202401091731044088.jpg', 40000, '6 lon'),
(160, 56, 'Thùng', 0, 'thung-24-lon-soda-schweppes-330ml-202401091735165254.jpg', 153000, '24 lon'),
(161, 57, 'Lon', 0, NULL, 0, '1 lon'),
(162, 57, 'Lốc', 0, '6-lon-nuoc-ngot-mirinda-vi-cam-320ml-202312252200360166.jpg', 61000, '6 lon'),
(163, 57, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-mirinda-huong-cam-320ml-202312252205201062.jpg', 240000, '24 lon'),
(164, 58, 'Lon', 0, NULL, 0, '1 lon'),
(165, 58, 'Lốc', 0, '6-lon-nuoc-ngot-mirinda-vi-soda-kem-320ml-202312260911089105.jpg', 61000, '6 lon'),
(166, 58, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-mirinda-vi-soda-kem-320ml-202312260920012511.jpg', 240000, '24 lon'),
(167, 59, 'Lon', 0, NULL, 0, '1 lon'),
(168, 59, 'lốc', 0, '6-lon-nuoc-ngot-mirinda-huong-xa-xi-320ml-202312252224294658.jpg', 61000, '6 lon'),
(169, 59, 'Thùng', 0, 'thung-24-lon-nuoc-ngot-mirinda-huong-xa-xi-320ml-202312252228594666.jpg', 240000, '24 lon'),
(170, 60, 'Chai', 0, NULL, 0, '1 chai'),
(171, 60, 'Lốc', 0, '6-chai-tra-o-long-tea-plus-350ml-202302271012519321.jpg', 50000, '6 chai'),
(172, 60, 'Thùng', 0, 'frame-2-6-1_202412101432419855.jpg', 198000, '24 chai'),
(173, 61, 'Chai', 0, NULL, 0, '1 chai'),
(174, 61, 'Lốc', 0, 'tra-o-long-xanh-huong-chanh-tea-plus-450ml-clone-202405151655119605.jpg', 62500, '6 chai'),
(175, 61, 'Thùng', 0, 'loc-6-chai-tra-o-long-xanh-huong-chanh-tea-plus-450ml-clone-202405151658588893.jpg', 249000, '24 chai'),
(176, 62, 'chai', 0, NULL, 0, '1 chai'),
(177, 62, 'lốc', 0, 'slide-2_202410281556099557.jpg', 62500, '6 chai'),
(178, 62, 'thùng', 0, 'slide_202410281556538384.jpg', 249000, '24 chai'),
(179, 63, 'chai', 0, NULL, 0, '1 chai'),
(180, 63, 'lốc', 0, '6-chai-tra-thanh-nhiet-drthanh-330ml-202103290819533934.jpg', 67000, '6 chai'),
(181, 63, 'thùng', 0, 'thung-24-chai-tra-thanh-nhiet-drthanh-330ml-202103081048353680.jpg', 265000, '24 chai'),
(182, 64, 'chai', 0, NULL, 0, '1 chai'),
(183, 64, 'lốc', 0, '6-chai-tra-thanh-nhiet-drthanh-455ml-202103290421187551.jpg', 76000, '6 chai'),
(184, 64, 'thùng', 0, 'muy6j_202410140900075508.jpg', 300000, '24 chai'),
(185, 65, 'chai', 0, NULL, 0, '1 chai'),
(186, 65, 'lốc', 0, '6-chai-tra-xanh-khong-do-vi-chanh-455ml-202103290825331304.jpg', 62000, '6 chai'),
(187, 65, 'thùng', 0, 'thung-24-chai-tra-xanh-khong-do-vi-chanh-455ml-202406270829049236.jpg', 245000, '24 chai'),
(188, 66, 'chai', 0, NULL, 0, '1 chai'),
(189, 66, 'lốc', 0, '6-chai-tra-xanh-c2-huong-chanh-230ml-202212051659416703.jpg', 25000, '6 chai'),
(190, 66, 'thùng', 0, 'thung-24-chai-tra-xanh-c2-huong-chanh-230ml-202212051702208119.jpg', 95000, '24 chai'),
(191, 67, 'chai', 0, NULL, 0, '1 chai'),
(192, 67, 'lốc', 0, 'loc-6-chai-tra-den-c2-huong-dao-230ml-202111271341434630.jpg', 25000, '6 chai'),
(193, 67, 'thùng', 0, 'thung-24-chai-tra-den-c2-huong-dao-230ml-202310230828541688.jpg', 95000, '24 chai'),
(194, 68, 'chai', 0, NULL, 0, '1 chai'),
(195, 68, 'lốc', 0, '6-chai-tra-xanh-c2-huong-chanh-360ml-201909270927566578.jpg', 46000, '6 chai'),
(196, 68, 'thùng', 0, 'thung-24-chai-tra-xanh-c2-huong-chanh-360ml-201912091023304107.jpg', 175000, '24 chai'),
(197, 69, 'chai', 0, NULL, 0, '1 chai'),
(198, 69, 'lốc', 0, '6-chai-hong-tra-c2-vi-vai-455ml_202504110850017115.jpg', 39000, '6 chai'),
(199, 69, 'thùng', 0, 'thung-24-chai-hong-tra-c2-vi-vai-455ml_202504110848502097.jpg', 150000, '24 chai'),
(200, 70, 'chai', 0, NULL, 0, '1 chai'),
(201, 70, 'lốc', 0, '6-chai-tra-den-dua-luoi-bac-ha-c2-freeze-455ml-202303151101552897.jpg', 58000, '6 chai'),
(202, 70, 'thùng', 0, 'thung-24-chai-tra-den-dua-luoi-bac-ha-c2-freeze-455ml-202303151101204685.jpg', 226000, '24 chai'),
(203, 71, 'lon', 0, NULL, 0, '1 lon'),
(204, 71, 'lốc', 0, '6-lon-tra-bi-dao-wonderfarm-310ml-202103290403574758.jpg', 49000, '6 lon'),
(205, 71, 'thùng', 0, 'thung-24-lon-tra-bi-dao-wonderfarm-310ml-202103290404279934.jpg', 193000, '24 lon'),
(206, 72, 'chai', 0, NULL, 0, '1 chai'),
(207, 72, 'lốc', 0, 'tra-bi-dao-wonderfarm-chai-440ml-clone-202407171914009151.jpg', 56000, '6 chai'),
(208, 72, 'thùng', 0, 'loc-6-chai-tra-bi-dao-wonderfarm-440ml-clone-202407171920131616.jpg', 220000, '24 chai'),
(209, 73, 'chai', 0, NULL, 0, '1 chai'),
(210, 73, 'lốc', 0, '6-chai-tra-bi-dao-wonderfarm-280ml-202103290407303221.jpg', 42500, '6 chai'),
(211, 73, 'thùng', 0, 'thung-24-chai-tra-bi-dao-wonderfarm-280ml-202103290407075474.jpg', 170000, '24 chai'),
(212, 74, 'chai', 0, NULL, 0, '1 chai'),
(213, 74, 'lốc', 0, 'loc-6-chai-tra-mat-ong-boncha-vi-o-long-dao-chai-450ml-202404221908486109.jpg', 59000, '6 chai'),
(214, 74, 'thùng', 0, 'thung-24-chai-tra-mat-ong-boncha-vi-o-long-dao-chai-450ml-202404221909009761.jpg', 235000, '24 chai'),
(215, 75, 'chai', 0, NULL, 0, '1 chai'),
(216, 75, 'lốc', 0, '6-chai-tra-mat-ong-boncha-vi-viet-quat-450ml-202401271402415702.jpg', 59000, '6 chai'),
(217, 75, 'thùng', 0, '6-chai-tra-mat-ong-boncha-vi-viet-quat-450ml-clone-202401121616157573.jpg', 235000, '24 chai'),
(218, 76, 'lon', 0, NULL, 0, '1 lon'),
(219, 76, 'Lốc', 0, '6-lon-nuoc-tang-luc-redbull-250ml-202103272201250743.jpg', 55000, '6 lon'),
(220, 76, 'Thùng', 0, '24-lon-nuoc-tang-luc-redbull-250ml-202103282348189198.jpg', 215000, '24 lon'),
(221, 77, 'Lon', 0, NULL, 0, '1 lon'),
(222, 77, 'lốc', 0, '6-lon-nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-202403091724125899.jpg', 55000, '6 lon'),
(223, 77, 'thùng', 0, 'thung-24-lon-nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-202403091724212175.jpg', 215000, '24 lon'),
(224, 78, 'chai', 0, NULL, 0, '1 chai'),
(225, 78, 'lốc', 0, '6-chai-nuoc-tang-luc-sting-gold-330ml-202103272139112743.jpg', 64000, '6 chai'),
(226, 78, 'thùng', 0, '24-chai-nuoc-tang-luc-sting-gold-330ml-202407091628328734.jpg', 255000, '24 chai'),
(227, 79, 'chai', 0, NULL, 0, '1 chai'),
(228, 79, 'lốc', 0, '6-chai-sting-huong-dau-330ml-202103272146121296.jpg', 67000, '6 chai'),
(229, 79, 'thùng', 0, '24-chai-nuoc-tang-luc-sting-huong-dau-330ml-202103300950550501.jpg', 265000, '24 chai'),
(230, 80, 'Lon', 0, NULL, 0, '1 lon'),
(231, 80, 'lốc', 0, '6-lon-nuoc-tang-luc-sting-gold-330ml-202103272136595116.jpg', 67000, '6 lon'),
(232, 80, 'thùng', 0, '24-lon-nuoc-tang-luc-sting-gold-320ml-202407111450192542.jpg', 265000, '24 lon'),
(233, 81, 'Lon', 0, NULL, 0, '1 lon'),
(234, 81, 'lốc', 0, '6-lon-nuoc-tang-luc-sting-huong-dau-320ml-202111061732537179.jpg', 55000, '6 lon'),
(235, 81, 'thùng', 0, '24-lon-nuoc-tang-luc-sting-huong-dau-320ml-202407091625327728.jpg', 221000, '24 lon'),
(236, 82, 'chai', 0, NULL, 0, '1 chai'),
(237, 82, 'lốc', 0, '6-chai-nuoc-bu-khoang-revive-muoi-khoang-500ml-202103312248177034.jpg', 65000, '6 chai'),
(238, 82, 'thùng', 0, 'thung-24-chai-nuoc-bu-khoang-revive-muoi-khoang-500ml-202407121558394200.jpg', 252000, '24 chai'),
(239, 83, 'chai', 0, NULL, 0, '1 chai'),
(240, 83, 'lốc', 0, '6-chai-nuoc-bu-khoang-revive-chanh-muoi-390ml-202103272142426493.jpg', 60500, '6 chai'),
(241, 83, 'thùng', 0, 'thung-24-chai-nuoc-bu-khoang-revive-chanh-muoi-390ml-202407121630583041.jpg', 235000, '24 chai'),
(242, 84, 'Lon', 0, NULL, 0, '1 lon'),
(243, 84, 'lốc', 0, '6-lon-nuoc-tang-luc-monster-energy-355ml-202103272049543278.jpg', 110000, '6 lon'),
(244, 84, 'thùng', 0, '24-lon-nuoc-tang-luc-monster-energy-355ml-202103272055551940.jpg', 420000, '24 lon'),
(245, 85, 'Lon', 0, NULL, 0, '1 lon'),
(246, 85, 'lốc', 0, '6-lon-nuoc-tang-luc-monster-energy-ultra-355ml-202103272059257521.jpg', 110000, '6 lon'),
(247, 85, 'thùng', 0, '24-lon-nuoc-tang-luc-monster-energy-ultra-355ml-202103272058317494.jpg', 420000, '24 lon'),
(248, 86, 'lon', 0, NULL, 0, '1 lon'),
(249, 86, 'lốc', 0, '232335_202410311731146260.jpg', 45000, '6 lon'),
(250, 86, 'thùng', 0, 'thung-24-lon-nuoc-tang-luc-lipovitan-mat-ong-250ml-202306242224456842.jpg', 170000, '24 lon'),
(251, 87, 'chai', 0, NULL, 0, '1 chai'),
(252, 87, 'lốc', 0, '6-chai-nuoc-tang-luc-number1-330ml-202103300903421408.jpg', 47000, '6 chai'),
(253, 87, 'thùng', 0, 'bhji_202410140927105012.jpg', 188000, '24 chai'),
(254, 88, 'chai', 0, NULL, 0, '1 chai'),
(255, 88, 'thùng', 0, 'frame-3475096_202502101332191728.jpg', 114000, '24 chai'),
(256, 89, 'chai', 0, NULL, 0, '1 chai'),
(257, 89, 'lốc', 0, '6-chai-nuoc-tinh-khiet-aquafina-500ml-202407121614415215.jpg', 31000, '6 chai'),
(258, 89, 'thùng', 0, 'untitled-1_202411021058125450.jpg', 120000, '24 chai'),
(259, 90, 'lon', 0, NULL, 0, '1 lon'),
(260, 90, 'lốc', 0, '6-lon-nuoc-giai-khat-co-ga-aquafina-soda-320ml-202106230913408613.jpg', 43000, '6 lon'),
(261, 90, 'thùng', 0, 'thung-24-lon-nuoc-giai-khat-co-ga-aquafina-soda-320ml-202106230915468237.jpeg', 170000, '24 lon'),
(262, 91, 'chai', 0, NULL, 0, '1 chai'),
(263, 91, 'thùng', 0, 'thung-12-chai-nuoc-tinh-khiet-aquafina-15-lit-202407121116529385.jpg', 132000, '12 chai'),
(264, 92, 'chai', 0, NULL, 0, '1 chai'),
(265, 92, 'lốc', 0, '6-chai-nuoc-khoang-la-vie-350ml-202112310823096348.jpg', 27000, '6 chai'),
(266, 92, 'thùng', 0, 'thung-24-chai-nuoc-khoang-la-vie-350ml-202112310823372509.jpg', 85000, '24 chai'),
(267, 93, 'chai', 0, NULL, 0, '1 chai'),
(268, 93, 'lốc', 0, 'slide-5_202410161048454594.jpg', 30000, '6 chai'),
(269, 93, 'thùng', 0, 'slide-3_202410161047556527.jpg', 95000, '24 chai'),
(270, 94, 'chai', 0, NULL, 0, '1 chai'),
(271, 94, 'Thùng', 0, 'thung-4-chai-nuoc-khoang-la-vie-5-lit-202306121041069614.jpg', 100000, '4 chai'),
(272, 95, 'chai', 0, NULL, 0, '1 chai'),
(273, 95, 'lốc', 0, '6-chai-nuoc-tinh-khiet-dasani-510ml-202303081129555125.jpg', 25000, '6 chai'),
(274, 95, 'thùng', 0, 'thung-24-chai-nuoc-tinh-khiet-dasani-510ml-202312272241389347.jpg', 79000, '24 chai'),
(275, 96, 'chai', 0, NULL, 0, '1 chai'),
(276, 96, 'lốc', 0, '6-chai-nuoc-tinh-khiet-dasani-15-lit-202002222118375888.jpg', 40000, '6 chai'),
(277, 97, 'chai', 0, NULL, 0, '1 chai'),
(278, 97, 'lốc', 0, '6-chai-nuoc-uong-good-mood-vi-sua-chua-455ml-202407091449176471.jpg', 49000, '6 chai'),
(279, 97, 'thùng', 0, 'thung-24-chai-nuoc-uong-good-mood-vi-sua-chua-455ml-202407091453001404.jpg', 190000, '24 chai'),
(280, 98, 'hộp', 0, NULL, 0, '1 hộp'),
(281, 99, 'Hộp', 0, NULL, 0, '1 hộp'),
(282, 100, 'hộp', 0, NULL, 0, '1 hộp'),
(289, 101, 'hộp', 0, NULL, 0, '1 hộp'),
(290, 102, 'chai', 0, NULL, 0, '1 chai'),
(291, 102, 'lốc', 0, 'nuoc-yen-dong-trung-ha-thao-ky-tu-va-hat-chia-song-yen-huong-hat-sen-185ml-clone-202408221421570614.jpg', 169000, '6 chai'),
(292, 103, 'hộp', 0, NULL, 0, '1 hộp'),
(293, 104, 'Hộp', 0, NULL, 0, '1 hộp'),
(294, 105, 'chai', 0, NULL, 0, '1 chai'),
(295, 105, 'lốc', 0, '1469306996-3_202412230945228374.jpg', 210000, '6 chai'),
(296, 105, 'thùng', 0, 'thung-48-chai-che-duong-nhan-to-yen-va-trung-thao-green-bird-185ml-202306120924463382.jpg', 1680000, '48 chai'),
(297, 106, 'chai', 0, NULL, 0, '1 chai'),
(298, 106, 'lốc', 0, 'loc-6-chai-nuoc-yen-collagen-green-bird-185ml-202204190906362298.jpg', 174000, '6 chai'),
(299, 106, 'thùng', 0, 'thung-48-chai-nuoc-yen-sao-collagen-green-bird-185ml-202111270845056757.jpg', 1296000, '48 chai'),
(300, 107, 'chai', 0, NULL, 0, '1 chai'),
(301, 107, 'lốc', 0, '1469306996-5_202412231000516828.jpg', 174000, '6 chai'),
(302, 107, 'thùng', 0, 'thung-48-chai-nuoc-yen-sao-dong-trung-ha-thao-green-bird-185ml-202006191057260649.jpg', 1296000, '48 chai'),
(303, 108, 'chai', 0, NULL, 0, '1 chai'),
(304, 108, 'lốc', 0, '1469306996-8_202412231013423807.jpg', 162000, '6 chai'),
(305, 108, 'thùng', 0, 'thung-48-chai-nuoc-yen-sao-hat-chia-green-bird-185ml-202006191100565441.jpg', 1200000, '48 chai'),
(308, 109, 'chai', 0, NULL, 0, '1 chai'),
(309, 110, 'lon', 0, NULL, 0, '1 lon'),
(310, 110, 'lốc', 0, 'hop-6-hu-to-yen-chung-san-duong-phen-winsnest-420ml-202012191044399438.jpg', 189000, '6 lon'),
(311, 111, 'lon', 0, NULL, 0, '1 lon'),
(312, 111, 'lốc', 0, '412208-4-1_202412161540278584.jpg', 49000, '6 lon'),
(313, 111, 'thùng', 0, '412208_202501161542418774.jpg', 240000, '30 lon'),
(314, 112, 'chai', 0, NULL, 0, '1 chai'),
(315, 113, 'chai', 0, NULL, 0, '1 chai'),
(316, 114, 'chai', 0, NULL, 0, '1 chai'),
(317, 115, 'chai', 0, NULL, 0, '1 chai'),
(318, 116, 'chai', 0, NULL, 0, '1 chai'),
(319, 117, 'chai', 0, NULL, 0, '1 chai'),
(320, 117, 'thùng', 0, '12-chai-nuoc-cam-ep-twister-tropicana-1-lit-202312261718527283.jpg', 270000, '12 chai'),
(321, 118, 'hộp', 0, NULL, 0, '1 hộp'),
(322, 119, 'hộp', 0, NULL, 0, '1 hộp'),
(323, 120, 'chai', 0, NULL, 0, '1 chai'),
(324, 120, 'lốc', 0, '6-chai-nuoc-trai-cay-ice-vi-dao-490ml-202103270855598253.jpg', 50000, '6 chai'),
(325, 120, 'thùng', 0, 'thung-24-chai-nuoc-trai-cay-ice-vi-dao-490ml-202103270856475399.jpg', 195000, '24 chai'),
(326, 121, 'hộp', 0, NULL, 0, '1 hộp'),
(327, 122, 'hộp', 0, NULL, 0, '1 hộp'),
(328, 123, 'hộp', 0, NULL, 0, '1 hộp'),
(329, 124, 'chai', 0, NULL, 0, '1 chai'),
(330, 124, 'lốc', 0, '145393_202411200946235513.jpg', 126000, '6 chai'),
(331, 125, 'chai', 0, NULL, 0, '1 chai'),
(332, 125, 'lốc', 0, '6-chai-nuoc-suong-sao-a1-food-280ml-202311021058378149.jpg', 60000, '6 chai'),
(333, 125, 'thùng', 0, '6-chai-nuoc-suong-sao-a1-food-280ml-clone-202310181616061092.jpg', 190000, '24 chai'),
(334, 126, 'lon', 0, NULL, 0, '1 lon'),
(335, 126, 'lốc', 0, '225632-2_202411201122096668.jpg', 57000, '6 lon'),
(336, 126, 'thùng', 0, '225630_202411201102331704.jpg', 206000, '24 lon'),
(337, 127, 'chai', 0, NULL, 0, '1 chai'),
(338, 128, 'chai', 0, NULL, 0, '1 chai'),
(339, 129, 'chai', 0, NULL, 0, '1 chai'),
(340, 130, 'chai', 0, NULL, 0, '1 chai'),
(341, 131, 'chai', 0, NULL, 0, '1 chai'),
(342, 132, 'chai', 0, NULL, 0, '1 chai'),
(343, 132, 'lốc', 0, 'frame-1-2-1_202412040916024601.jpg', 75000, '6 chai'),
(344, 132, 'thùng', 0, '79240_202412040924016561.jpg', 290000, '24 chai'),
(345, 133, 'chai', 0, NULL, 0, '1 chai'),
(346, 133, 'lốc', 0, '6-chai-sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-202307291745336895.jpg', 43000, '6 chai'),
(347, 133, 'thùng', 0, 'thung-24-chai-sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-202307291745442914.jpg', 180000, '24 chai'),
(348, 134, 'chai', 0, NULL, 0, '1 chai'),
(349, 134, 'lốc', 0, 'loc-6-chai-sua-trai-cay-nutriboost-huong-dau-297ml-202103290151490562.jpg', 43000, '6 chai'),
(350, 134, 'thùng', 0, 'thung-24-chai-sua-trai-cay-nutriboost-huong-dau-297ml-202112281501517996.jpg', 200000, '24 chai'),
(351, 135, 'chai', 0, NULL, 0, '1 chai'),
(352, 135, 'lốc', 0, 'loc-6-chai-sua-trai-cay-nutriboost-huong-cam-297ml-202103290219146560.jpg', 50000, '6 chai'),
(353, 135, 'thùng', 0, 'thung-24-chai-sua-trai-cay-nutriboost-huong-cam-297ml-202202122203008831.jpg', 189000, '24 chai'),
(354, 136, 'chai', 0, NULL, 0, '1 chai'),
(355, 136, 'lốc', 0, 'loc-6-chai-sua-trai-cay-nutriboost-huong-dau-1-lit-202103290213130900.jpg', 162000, '6 chai'),
(356, 137, 'chai', 0, NULL, 0, '1 chai'),
(357, 137, 'lốc', 0, 'loc-6-chai-sua-trai-cay-nutriboost-huong-cam-1-lit-202103290214217215.jpg', 162000, '6 chai'),
(358, 138, 'hộp', 0, NULL, 0, '1 hộp'),
(359, 138, 'lốc', 0, 'loc-4-hop-thach-trai-cay-yomost-huong-dau-180ml-202406181624456681.jpg', 37500, '4 hộp'),
(360, 138, 'thùng', 0, 'thung-48-hop-thach-trai-cay-yomost-huong-dau-180ml-202406181625341197.jpg', 43000, '48 hộp'),
(361, 140, 'chai', 0, NULL, 0, '1 chai'),
(362, 139, 'lốc', 0, '6-chai-tra-sua-kirin-tea-break-345ml-202103312255324275.jpg', 67000, '6 chai'),
(363, 139, 'thùng', 0, 'thung-24-chai-tra-sua-kirin-tea-break-345ml-202101190949343300.jpg', 248000, '24 chai'),
(365, 140, 'lốc', 0, 'sua-trai-cay-oggi-vitadairy-huong-cam-hop-110ml-202406260959212521.jpg', 17000, '4 hộp'),
(366, 140, 'thùng', 0, 'thung-48-hop-sua-trai-cay-oggi-vitadairy-huong-cam-hop-110ml-202406261019128518.jpg', 172000, '48 hộp'),
(367, 141, 'lốc', 0, 'cdntgddvnproductsimages2947204883bhx-202304131320228561_202412190917240756.jpg', 29000, '4 hộp'),
(368, 141, 'thùng', 0, '204883-thumb-moi_202412181655464750.jpg', 270000, '48 hộp'),
(369, 142, 'lốc', 0, 'cdntgddvnproductsimages2947198351bhx-202304030934104441_202412190915277318.jpg', 29000, '4 hộp'),
(370, 142, 'thùng', 0, 'cdntgddvnproductsimages2947198351bhx-202304030933521994_202412190854142197.jpg', 270000, '48 hộp'),
(371, 143, 'lốc', 0, 'cdntgddvnproductsimages2947198343bhx-202304121052114211_202412181500481106.jpg', 29000, '4 hộp'),
(372, 143, 'thùng', 0, 'cdntgddvnproductsimages2947198345bhx-202304121052215626_202412190909167931.jpg', 270000, '48 hộp'),
(373, 144, 'hộp', 0, 'hop-5-goi-nuoc-cot-ca-phe-sua-nescafe-75ml-202310071649230451.jpg', 51000, '5 gói'),
(374, 144, 'thùng', 0, 'z-1_202410311815288274.jpg', 300000, '30 gói'),
(375, 145, 'hộp', 0, 'hop-5-goi-nuoc-cot-ca-phe-den-nescafe-75ml-202310071643274769.jpg', 51000, '5 gói'),
(376, 145, 'thùng', 0, 'z_202410311815587622.jpg', 300000, '30 gói'),
(381, 146, 'hộp', 0, NULL, 0, '1 hộp'),
(382, 147, 'hộp', 0, NULL, 0, '1 hộp'),
(383, 148, 'bịch', 0, NULL, 0, '1 bịch'),
(384, 149, 'hộp', 0, NULL, 0, '1 hộp'),
(385, 150, 'bịch', 0, NULL, 0, '1 bịch'),
(386, 151, 'bịch', 0, NULL, 0, '1 bịch'),
(387, 152, 'Hộp', 0, NULL, 0, '1 hộp'),
(388, 153, 'hộp', 0, NULL, 0, '1 hộp'),
(389, 154, 'hộp', 0, NULL, 0, '1 hộp'),
(390, 155, 'hộp', 0, NULL, 0, '1 hộp'),
(391, 156, 'bịch', 0, NULL, 0, '1 bịch'),
(392, 157, 'hộp', 0, NULL, 0, '1 hộp'),
(393, 159, 'hộp', 0, NULL, 0, '1 hộp'),
(394, 160, 'hộp', 0, NULL, 0, '1 hộp'),
(395, 161, 'hộp', 0, NULL, 0, '1 hộp'),
(396, 162, 'hộp', 0, NULL, 0, '1 hộp');

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
  `origin` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category_id`, `size`, `brand_id`, `origin`, `is_deleted`) VALUES
(1, 'Bia Tiger Soju Wonder Melon vị dưa lưới 330ml', 'Bia Tiger quen thuộc được kết hợp cùng rượu soju dưa lưới vô cùng mới lạ, đảm bảo chính hãng bia Tiger nổi tiếng. Bia Tiger Soju Cheeky Plum dưa lưới 330ml với 20% rượu soju, hương vị dưa lưới thơm ngon, mang đến trải nghiệm uống dễ chịu, thích thú', 21000, 4, '330ml', 14, 'Việt Nam', 0),
(2, 'Bia Tiger Bạc 330ml', 'Loại bia còn gọi là bia Tiger bạc được sản xuất theo quy trình Cold Suspension độc đáo (kỹ thuật làm lạnh sâu đến -1 độ C). Bia Tiger Bạc 330ml với hoa bia được tinh chế đặc biệt giúp lưu giữ trọn vẹn hương vị tuyệt hảo vốn có của bia Tiger', 18800, 4, '330ml', 14, 'Việt Nam', 0),
(3, 'Bia Tiger Soju Cheeky Plum vị mận 330ml', 'Sản phẩm bia kết hợp cùng rượu soju hương vị trái cây vô cùng hấp dẫn, chính hãng bia Tiger nổi tiếng. Bia Tiger Soju Cheeky Plum vị mận 330ml với 20% rượu soju mận thơm mát, vị hài hòa dễ uống mang đến cho bạn trải nghiệm thú vị, thích thú, uống sảng khoái', 21000, 4, '330ml', 14, 'Việt Nam', 0),
(4, 'Bia Tiger Platinum Wheat Lager lon 330ml', 'Được lên men tự nhiên từ các thành phần nước, đại mạch, lúa mì, ngũ cốc, hoa bia, bổ sung thêm vỏ cam, hạt râu mùi, Pectin táo, hương cam giống tự nhiên và men, sản phẩm chính hãng bia Tiger nổi tiếng. Bia Tiger Platinum Wheat Lager lon 330ml mang hương vị độc đáo khác biệt', 21000, 4, '330ml', 14, 'Việt Nam', 0),
(5, 'Bia Sài Gòn Lager 330ml', 'Bia bược sản xuất tại Việt Nam từ nước, malt đại mạch, ngũ cốc và hoa bia, chính hãng bia Sài Gòn. Bia Sài Gòn Lager 330ml có hương vị đậm đà, thơm ngon, cùng hương thơm ngũ cốc dễ chịu giúp bạn thăng hoa hơn, sản khoái hơn trong những cuộc vui cùng gia đình và bạn bè.', 12000, 4, '330ml', 10, 'Việt Nam', 0),
(6, 'Bia Sài Gòn Export Premium 330ml', 'Bia chính hãng thương hiệu Bia Sài Gòn nổi tiếng tại Việt Nam, thừa hưởng công thức truyền đời từ năm 1875 đến nay. Bia Sài Gòn Export Premium 330ml được ủ với công nghệ lên men chậm, mang đến hương vị bia ngon tuyệt hảo và chất bia êm đằm mà sảng khoái', 12400, 4, '330ml', 10, 'Việt Nam', 0),
(7, 'Bia Sài Gòn Chill 330ml', 'Được sản xuất tại Việt Nam từ nước, malt đại mạch và hoa bia. Sản phẩm bia mới của thương hiệu bia Sài Gòn là bia Sài Gòn Chill lon 330ml với người dân Việt Nam với hương vị thơm ngon, đậm đà, dễ uống giúp bạn thăng hoa hơn, sảng khoái hơn trong những cuộc vui cùng gia đình và bạn bè, cực chill.', 17600, 4, '330ml', 10, 'Việt Nam', 0),
(8, 'Bia Sài Gòn Special Sleek 330ml', 'Sản phẩm bia ngon chất lượng từ nước, malt đại mạch và hoa bia, cam kết kết chính hãng thương hiệu bia Sài Gòn. Bia Sài Gòn Special Sleek 330ml đã quá quen thuộc với người dân Việt Nam với hương vị thơm ngon, đậm đà, nay thiết kế lon cao thanh lịch sang trọng mang đến sự đẳng cấp hơn', 15600, 4, '330ml', 10, 'Việt Nam', 0),
(9, 'Bia Heineken Silver 250ml', 'Chất lượng từ thương hiệu bia được ưa chuộng tại hơn 192 quốc gia trên thế giới đến từ Hà Lan. Bia Heineken Silver 250ml mang hương vị đặc trưng thơm ngon hương vị bia tuyệt hảo, cân bằng và êm dịu. Bên cạnh đó là thiết kế đẹp mắt, cho người dùng cảm giác sang trọng, nâng tầm đẳng cấp.\r\n', 15900, 4, '250ml', 5, 'Việt Nam', 0),
(11, 'Bia Heineken Sleek 330ml', 'Chất lượng từ thương hiệu bia được ưa chuộng tại hơn 192 quốc gia trên thế giới đến từ Hà Lan. Bia Heineken 330ml mang hương vị đặc trưng thơm ngon hương vị bia tuyệt hảo, cân bằng và êm dịu. Bên cạnh đó là thiết kế đẹp mắt, cho người dùng cảm giác sang trọng, nâng tầm đẳng cấp.', 20500, 4, '330ml', 5, 'Việt Nam', 0),
(12, 'Bia Budweiser 250ml', 'Bia Mỹ thơm ngon, chính hãng bia Budweiser đậm đà được sản xuất từ mạch nha cùng với hoa bia thượng hạng Hoa Kỳ và Châu Âu. Bia Budweiser lon 250ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia có thiết kế sang trọng, hiện đại thêm phần đẳng cấp', 15900, 4, '250ml', 3, 'Việt Nam', 0),
(13, 'Bia Budweiser 330ml', 'Bia Mỹ thơm ngon, chính hãng bia Budweiser đậm đà được sản xuất từ mạch nha cùng với hoa bia thượng hạng Hoa Kỳ và Châu Âu. Bia Budweiser lon 330ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia có thiết kế sang trọng, hiện đại thêm phần đẳng cấp', 18600, 4, '330ml', 3, 'Việt Nam', 0),
(14, 'Bia Budweiser 330ml', 'Hương vị bia là sự cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Công nghệ ủ bia bằng gỗ sồi của bia Budweiser đã tạo ra một hương vị tuyệt hảo không nhầm lẫn vào đâu được. Bia Budweiser chai 330ml thủy tinh sang trọng và đẳng cấp không thể bỏ qua', 22000, 4, '330ml', 3, 'Việt Nam', 0),
(15, 'Bia 333 330ml', 'Thơm ngon, màu bia vàng đẹp mắt, hương vị đậm đà mạnh mẽ, khẳng định đẳng cấp phái mạnh, chính hãng bia 333. Bia 333 330ml chất lượng bảo đảm an toàn vệ sinh từ thương hiệu nổi tiếng lâu đời tại Việt Nam cho những cuộc vui kéo dài, sảng khoái bên gia đình, bạn bè và những chiến hữu', 12400, 4, '330ml', 1, 'Việt Nam', 0),
(16, 'Bia Corona Extra 300ml', 'Hương vị bia Bỉ truyền thống thơm ngon rất được ưa chuộng trên thế giới, chính hãng bia Corona. Bia Corona Extra 300ml chai thủy tinh sang trọng cùng màu bia vàng tươi bắt mắt, mang đến cho bạn cảm giác uống sảng khoái, tươi mới và năng động', 34500, 4, '300ml', 4, 'Trung Quốc', 0),
(17, 'Strongbow Kiwi và thanh long 320ml', 'Strongbow Sparkling Ciders được lên men tự nhiên công phu và tỉ mỉ, Strongbow Kiwi và thanh long lon 320ml là sự kết hợp giữa bọt sủi - Sparkling, kích thích vị giác cùng hương vị độc đáo của kiwi và thanh long, tạo nên một thức uống sảng khoái, tươi mát cho mọi cuộc vui.', 21000, 4, '320ml', 13, 'Việt Nam', 0),
(18, 'Strongbow thơm và lựu 320ml', 'Strongbow Sparkling Ciders được lên men tự nhiên công phu và tỉ mỉ, Strongbow thơm và lựu lon 320ml là sự kết hợp giữa bọt sủi - Sparkling, kích thích vị giác cùng hương vị độc đáo của thơm và lựu, tạo nên một thức uống sảng khoái, tươi mát cho mọi cuộc vui.', 21000, 4, '320ml', 13, 'Việt Nam', 0),
(19, 'Strongbow táo 320ml', 'Nước táo lên men Strongbow là nước uống có cồn độ ngọt dịu và hậu vị sang trọng kéo dài. Strongbow táo lon 320ml vị táo nguyên bản sẽ làm cho bạn dễ chịu và khi thưởng thức sẽ không cảm nhận được nhiều vị cồn vì thức uống này được lên men trực tiếp từ trái cây', 21000, 4, '320ml', 13, 'Việt Nam', 0),
(20, 'Bia Budweiser 500ml', 'Thơm ngon đậm đà sản xuất từ mạch nha cùng hoa bia thượng hạng từ Hoa Kỳ và Châu Âu, sản phẩm chính hãng bia Budweiser. Bia Budweiser 500ml cho hương vị cân bằng tuyệt đối giữa vị cay không quá nặng với vị ngọt ngào tinh tế, giòn tan. Bia thiết kế lon cao thanh lịch sang trọng, hiện đại, đẳng cấp.', 29500, 4, '500ml', 3, 'Việt Nam', 0),
(21, 'Bia 333 Pilsner Extra Smooth 330ml', 'Bia 333 Pilsner Extra Smooth lon 330ml là một loại bia được sản xuất với công nghệ ủ lạnh lâu từ Châu Âu, tạo nên vị bia êm cực êm và có nồng độ cồn 4.3%, Với mùi vị thơm nồng, đây là một loại bia dễ uống và phù hợp với nhiều đối tượng sử dụng. Bia 333 Pilsner được sản xuất trên quy trình công nghệ hiện đại, đảm bảo chất lượng và độ tươi mới.', 14200, 4, '330ml', 1, 'Việt Nam', 0),
(22, 'Bia Hoegaarden Rosée 248ml', 'Từ thương hiệu bia Bỉ nổi tiếng thế giới bia Hoegaarden. Bia Hoegaarden Rosée 248ml hương vị thơm ngon hòa quyện hương trái cây phúc bồn tử, dịu ngọt với 100% thành phần tự nhiên, nồng độ cồn 3.3% nhẹ nhàng, dễ chịu. Sản phẩm cam kết chính hãng, chất lượng và an toàn', 20500, 4, '248ml', 6, 'Việt Nam', 0),
(23, 'Bia Hoegaarden White 330ml\r\n\r\n', 'Từ các loại rau mùi cay kết hợp cùng vỏ cam Curacao, mang đến một hương vị bia không đâu sánh bằng. Bia Hoegaarden White 330ml thơm ngon hảo hạng, mát lạnh xua tan đi bao nhiêu muộn phiền và nóng nực của những ngày làm việc căng thẳng. Chất lượng từ thương hiệu bia Bỉ Hoegaarden', 31000, 4, '330ml', 6, 'Việt Nam', 0),
(24, 'Bia Hoegaarden 330ml', 'Với thành phần chính là các rau mùi cay kết hợp cùng vỏ cam Curacao, mang đến một hương vị bia không đâu sánh bằng. Bia Hoegaarden 330ml thơm ngon đậm đà chất lượng từ thương hiệu bia Bỉ nổi tiếng - bia Hoegaarden. Cam kết chính hãng, chất lượng và an toàn', 33000, 4, '330ml', 6, 'Việt Nam', 0),
(25, 'Bia Huda 330ml', 'Bia sản xuất theo quy trình hiện đại và không chứa hóa chất độc hại mang đến chất lượng hoàn hảo cho người sử dụng. Bia Huda 330ml​ có màu vàng óng, mùi thơm đặc trưng, hương vị đậm đà, hấp dẫn vị giác. Sản phẩm từ thương hiệu bia Huda chất lượng với công nghệ bia Đan Mạch ', 12400, 4, '330ml', 7, 'Việt Nam', 0),
(26, 'Bia Larue Xanh cao 330ml', 'Bia được sản xuất từ nguồn nguyên liệu thượng hạng cùng bí quyết nấu bia đến từ Châu Âu, chính hãng bia Larue. Bia Larue Xanh lon cao 330ml có mùi thơm đặc trưng, hương vị đậm đà, mang lại những trải nghiệm thú vị cho người thưởng thức. Cam kết bia chính hãng, uy tín, chất lượng', 11900, 4, '330ml', 9, 'Việt Nam', 0),
(27, 'Bia Larue Smooth Đà Nẵng 330ml', 'Bia được sản xuất từ nguồn nguyên liệu thượng hạng cùng bí quyết nấu bia đến từ Châu Âu, chính hãng bia Larue. Bia Larue Smooth Đà Nẵng 330ml có mùi thơm đặc trưng, hương vị đậm đà, mang lại những trải nghiệm thú vị cho người thưởng thức. Cam kết bia chính hãng, uy tín, chất lượng', 12200, 4, '330ml', 9, 'Việt Nam', 0),
(28, 'Bia Larue Special 330ml', 'Bia được sản xuất theo công nghệ hiện đại, mọi khâu từ tuyển chọn nguyên liệu tới chế biến, đóng gói đều diễn ra khép kín dưới sự giám sát và kiểm tra nghiêm ngặt, chính hãng bia Laure. Bia Larue Special 330ml hương vị bia đậm đà thơm ngon hấp dẫn.', 12300, 4, '330ml', 9, 'Việt Nam', 0),
(29, 'Bia Blanc 1664 330ml', 'Tinh túy hương vị bia Pháp từ năm 1664. Bia Blanc 1664 lon 330ml trên dây chuyền hiện đại cùng bí quyết nấu bia tuyệt hảo cho cảm giác uống sảng khoái đầy thích thú. Bia lon tiện dụng, thiết kế hiện đại, bia Blac1664 cam kết chính hãng và chất lượng', 19000, 4, '330ml', 8, 'Việt Nam', 0),
(30, 'Nước táo lên men Somersby Blackberry vị mâm xôi 330ml\r\n\r\n', 'Đây là đồ uống có cồn thơm ngon hấp dẫn được sản xuất tại Malaysia với công nghệ hiện đại tiên tiến của tập đoàn Carlsberg. Nước táo lên men Somersby vị mâm xôi 330ml chính hãng nước táo lên men Somersby thơm ngon hấp dẫn, vị dễ uống, thiết kế trẻ trung hiện đại, phù hợp với giới trẻ', 20500, 4, '330ml', 12, 'Malaysia', 0),
(31, 'Bia trái cây San Miguel vị vải 330ml', 'Chất bia vàng nhạt sánh mịn, dễ uống, khơi gợi vị bia trái cây tươi mát với hương vị vải riêng biệt, ngọt ngào và tinh tế, nồng độ cồn 3 độ. Bia trái cây San Miguel vị vải 330ml mang đến trải nghiệm vô cùng thú vị. Cam kết bia chính hãng bia San Miguel chất lượng, uy tín', 18100, 4, '330ml', 11, 'Việt Nam', 0),
(32, 'Bia San Miguel Red Horse 500ml', 'Bia làm từ hoa bia, ngũ cốc, nước cùng các hương liệu khác và được sản xuất theo phương pháp truyền thống của người Tây Ban Nha đem đến sắc bia vàng óng ả có hương vị hết sức lạ miệng. Bia San Miguel Red Horse 500ml nồng độ 8% mạnh mẽ, chính hãng bia San Miguel chất lượng quốc tế', 25000, 4, '500ml', 11, 'Việt Nam', 0),
(33, 'Bia Bivina Export 330ml\r\n\r\n', 'Sản phẩm bia uống thơm ngon chất lượng, vị êm đằm sảng khoái, nồng độ 4.3% dễ uống, phù hợp với nhiều đối tượng. Bia Bivina Export lon 330ml chính hãng bia Bivina thuộc tập đoàn Heineken nổi tiếng cho bạn những cuộc vui hết mình, uống cực đã', 11700, 4, '330ml', 2, 'Việt Nam', 0),
(34, 'Nước ngọt có ga Coca Cola 390ml', 'Từ thương hiệu nước giải khát hàng đầu thế giới, nước ngọt Coca Cola chai 390ml xua tan nhanh mọi cảm giác mệt mỏi, căng thẳng, đặc biệt thích hợp sử dụng với các hoạt động ngoài trời. Bên cạnh đó thiết kế dạng chai nhỏ gọn, tiện lợi dễ dàng bảo quản khi không sử dụng hết', 7800, 1, '390ml', 15, 'Việt Nam', 0),
(35, 'Nước ngọt Coca Cola Zero 390ml', 'Nước giải khát nổi tiếng thế giới được ưa chuộng tại nhiều nhiều quốc gia. Nước ngọt Coca Cola Zero chai 390ml chính hãng nước ngọt Coca Cola cho cơ thể cảm giác nhẹ nhàng, ăn ngon hơn, không đường không calo phù hợp cho những ai yêu thích nước ngọt nhưng vẫn muốn giữ thói quen ăn uống lành mạnh', 7800, 1, '390ml', 15, 'Việt Nam', 0),
(36, 'Nước ngọt Coca Cola Zero 320ml', 'Nước ngọt nổi tiếng thế giới được ưa chuộng tại nhiều nhiều quốc gia. Nước ngọt Coca Cola Zero lon 320ml chính hãng nước ngọt Coca Cola cho cơ thể cảm giác nhẹ nhàng, ăn ngon hơn, không đường không calo phù hợp cho những ai yêu thích nước ngọt nhưng vẫn muốn giữ thói quen ăn uống lành mạnh', 10600, 1, '320ml', 15, 'Việt Nam', 0),
(37, 'Nước ngọt Coca Cola Light 320ml', 'Từ thương hiệu nước ngọt nổi tiếng thế giới được ưa chuộng tại nhiều nhiều quốc gia. Nước ngọt không đường Coca Cola Light lon 320ml chính hãng nước ngọt Coca Cola là dòng sản phẩm nước uống có ga không đường, dành cho người ăn kiêng, không lo tăng cân', 10600, 1, '320ml', 15, 'Việt Nam', 0),
(38, 'Nước ngọt Coca Cola 320ml', 'Là loại nước ngọt được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Nước ngọt Coca Cola 320ml chính hãng nước ngọt Coca Cola với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời.', 10600, 1, '320ml', 15, 'Việt Nam', 0),
(39, 'Nước ngọt Coca Cola nguyên bản (giảm đường) 1.5 lít\r\n\r\n', 'Nước ngọt giúp giải tỏa cơn khát, cung cấp nguồn năng lượng cùng hàm lượng khoáng chất dồi dào, cho bạn khơi lại hứng khởi. Nước ngọt Coca Cola vị nguyên bản giảm đường phù hợp cho những ai thích nước ngọt và lối sống ít đường, lành mạnh. Cam kết chính hãng từ nước ngọt Coca Cola', 21000, 1, '1.5 lít', 15, 'Việt Nam', 0),
(40, 'Nước ngọt Pepsi không calo 320ml', 'Là loại nước ngọt được nhiều người yêu thích đến từ thương hiệu nước ngọt Pepsi nổi tiếng thế giới với hương vị thơm ngon, sảng khoái. Nước ngọt Pepsi không calo lon 320ml với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, sản phẩm không calo lành mạnh, tốt cho sức khỏe', 10600, 1, '320ml', 16, 'Việt Nam', 0),
(41, 'Pepsi', 'Sản phẩm từ thương hiệu nước ngọt Pepsi nổi tiếng toàn cầu với mùi vị thơm ngon với hỗn hợp hương tự nhiên cùng chất tạo ngọt tổng hợp, giúp xua tan cơn khát và cảm giác mệt mỏi. Nước ngọt Pepsi Cola lon 320ml bổ sung năng lượng làm việc mỗi ngày. Cam kết nước ngọt chính hãng, chất lượng và an toàn', 10600, 1, '320ml', 16, 'Việt Nam', 0),
(42, 'Nước ngọt Pepsi không calo vị chanh 320ml', 'Sự kết hợp hài hòa của vị chanh thanh mát, giải nhiệt và mang lại cảm giác sảng khoái và tốt cho sức khỏe. Nước ngọt Pepsi không calo vị chanh lon 320ml cực kỳ thích hợp cho những người thích uống nước ngọt nhưng vẫn muốn giữ lối sống ăn thanh đạm, ít đường. Sản phẩm chất lượng từ nước ngọt Pepsi', 10600, 1, '320ml', 16, 'Việt Nam', 0),
(43, 'Nước ngọt Pepsi không calo 390ml', 'Sản phẩm nước ngọt được nhiều người yêu thích đến từ thương hiệu nước ngọt Pepsi nổi tiếng với hương vị thơm ngon, sảng khoái. Nước ngọt Pepsi không calo chai 390ml giúp bạn giải khát, xua tan mệt mỏi, căng thẳng, không chứa đường, không calo, lành mạnh', 7800, 1, '390ml', 16, 'Việt Nam', 0),
(44, 'Nước ngọt Pepsi Cola 390ml\r\n\r\n', 'Từ thương hiệu nước ngọt Pepsi nổi tiếng toàn cầu với mùi vị thơm ngon với hỗn hợp hương tự nhiên cùng chất tạo ngọt tổng hợp, giúp xua tan cơn khát và cảm giác mệt mỏi.  Nước ngọt Pepsi Cola 390ml bổ sung năng lượng làm việc mỗi ngày. Cam kết nước ngọt chính hãng, chất lượng và an toàn', 7800, 1, '390ml', 16, 'Việt Nam', 0),
(45, 'Nước ngọt Pepsi Cola 1.5 lít', 'Nước ngọt được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Nước ngot Pepsi chai 1.5 lít chính hãng nước ngọt Pepsi với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời.', 21000, 1, '1.5 lít', 16, 'Việt Nam', 0),
(46, 'Nước ngọt Fanta hương cam 320ml', 'Sản phẩm nước ngọt có gas của thương hiệu nước ngọt Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước ngọt Fanta hương cam lon 320ml thơm ngon kích thích vị giác, chứa nhiều vitamin C sẽ cung cấp năng lượng cho cơ thể', 9500, 1, '320ml', 17, 'Việt Nam', 0),
(47, 'Nước ngọt Fanta hương xá xị 320ml', 'Là sản phẩm nước ngọt có gas của thương hiệu nước ngọt Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước ngọt Fanta hương xá xị lon 320ml thơm ngon kích thích vị giác, cung cấp năng lượng cho cơ thể.\r\n', 9500, 1, '320ml', 17, 'Việt Nam', 0),
(48, 'Nước ngọt Fanta hương soda kem trái cây 320ml', 'Sản phẩm nước ngọt có gas của thương hiệu nước ngọt Fanta nổi tiếng giúp giải khát sau khi hoạt động ngoài trời, giải tỏa căng thẳng, mệt mỏi khi học tập, làm việc. Nước ngọt Fanta hương trái cây lon 320ml thơm ngon vị trái cây độc đáo giúp xua tan cơn khát và kích thích vị giác.', 9500, 1, '320ml', 17, 'Việt Nam', 0),
(49, 'Nước ngọt Fanta hương nho 320ml', 'Sản phẩm nước ngọt có ga thơm ngon hấp dẫn đến từ thương hiệu nước ngọt Fanta nổi tiếng trên thế giới. Nước ngọt Fanta hương nho lon 320ml chua ngọt tươi mới, chai lớn phù hợp sử dụng cho gia đình, nhóm bạn bè mang đến cảm giác sảng khoái, hứng khởi', 9500, 1, '320ml', 17, 'Việt Nam', 0),
(50, 'Nước ngọt soda chanh 7 Up 320ml', 'Nước ngọt chính hãng thương hiệu nước ngọt 7Up uy tín được nhiều người ưa chuộng. Nước ngọt soda chanh 7 Up lon 320ml chứa nước ép chanh thật, không đường không calo, cung cấp vitamin C và mang đến cảm giác sảng khoái, tràn đầy sức sống', 10200, 1, '320ml', 18, 'Việt Nam', 0),
(51, 'Nước ngọt 7 Up vị chanh 320ml', 'Nước ngọt chính hãng thương hiệu nước ngọt 7Up uy tín được nhiều người ưa chuộng. Nước ngọt 7 Up hương chanh lon 320ml có vị ngọt vừa phải và hương vị gas the mát, giúp bạn xua tan nhanh chóng cơn khát, giảm cảm giác ngấy, kích thích vị giác giúp ăn ngon hơn, cung cấp năng lượng', 10200, 1, '320ml', 18, 'Việt Nam', 0),
(52, 'Nước ngọt 7 Up vị chanh 390ml\r\n', 'Sản phẩm nước ngọt chất lượng từ thương hiệu nước ngọt 7Up uy tín được nhiều người ưa chuộng. Nước ngọt 7 Up hương chanh chai 390ml có vị ngọt vừa phải và hương vị gas the mát, giúp bạn xua tan nhanh chóng cơn khát, giảm cảm giác ngấy, kích thích vị giác giúp ăn ngon hơn, cung cấp năng lượng\r\n', 7800, 1, '390ml', 18, 'Việt Nam', 0),
(53, 'Nước ngọt 7 Up vị chanh 1.5 lít\r\n', 'Nước ngọt chính hãng nước ngọt 7Up uy tín được nhiều người ưa chuộng. Nước ngọt 7 Up hương chanh 1.5 lít có vị ngọt vừa phải và hương vị gas the mát, giúp bạn xua tan nhanh chóng cơn khát, giảm cảm giác ngấy, kích thích vị giác giúp ăn ngon hơn, cung cấp năng lượng cho tinh thần tươi vui mỗi ngày', 21000, 1, '1.5 lít', 18, 'Việt Nam', 0),
(54, 'Nước ngọt Sprite hương chanh 320ml', 'Nước ngọt có ga thơm ngon, được ưa chuộng tại hơn 190 quốc gia. Nước ngọt Sprite hương chanh lon 320ml vị chanh tươi mát cùng, vị ga bùng nổ sảng khoái, giúp bạn đập tan cơn khát, kích thích vị giác giúp bạn ăn ngon miệng hơn. Sản phẩm cam kết chính hãng nước ngọt Sprite chất lượng và an toàn', 9500, 1, '320ml', 21, 'Việt Nam', 0),
(55, 'Nước ngọt Sprite hương chanh 1.5 lít', 'Nước ngọt thơm ngon được yêu chuộng tại hơn 190 quốc gia trên thế giới. Nước có ga Sprite chanh 1.5 lít chính hãng nước ngọt Sprite chai lớn tiết kiệm cho cho gia đình hay nhóm bạn bè giúp sảng khoái, thư giãn với bọt ga sảng khoái tê đầu lưỡi, giải khát tức thì kích thích vị giác', 21000, 1, '1.5 lít', 21, 'Việt Nam', 0),
(56, 'Soda Schweppes 320ml', 'Nước ngọt sản xuất theo dây chuyền công nghệ hiện đại kiểm định nghiêm ngặt. Nước Soda Schweppes lon 320ml là thức uống giải khát giúp bổ sung vitamin và khoáng chất tốt cho cơ thể, giúp hanh chóng để bù nước cho cơ thể. Cam kết chất lượng an toàn từ thương hiệu nước ngọt Schweppes', 7000, 1, '320ml', 20, 'Việt Nam', 0),
(57, 'Nước ngọt Mirinda hương cam 320ml', 'Nước ngọt giải khát từ thương hiệu nước ngọt Mirinda nổi tiếng được nhiều người ưa chuộng. Nước ngọt Mirinda cam lon 320ml với hương vị cam đặc trưng, không chỉ giải khát, mà còn bổ sung thêm vitamin C giúp lấy lại năng lượng cho hoạt động hàng ngày. Cam kết chính hãng và an toàn', 10200, 1, '320ml', 19, 'Việt Nam', 0),
(58, 'Nước ngọt Mirinda vị soda kem 320ml', 'Sản phẩm nước ngọt giải khát từ thương hiệu nước ngọt Mirinda nổi tiếng được nhiều người ưa chuộng với hương vị độc đáo hấp dẫn. Nước ngọt Mirinda vị Soda kem lon 320ml có vị ngọt dịu, không chỉ giúp xua tan cơn khát tức thì mà còn giúp kích thích vị giác, cho bữa ăn thêm ngon miệng', 10200, 1, '320ml', 19, 'Việt Nam', 0),
(59, 'Nước ngọt Mirinda hương xá xị 320ml', 'Nước ngọt giải khát từ thương hiệu nước ngọt Mirinda nổi tiếng được nhiều người ưa chuộng với hương và vị hấp dẫn. Nước ngọt Mirinda hương xá xị lon 320ml có hương xá xị tự nhiên, độc đáo giúp bạn giải nhanh cơn khát,  với vị gas nhẹ là thức uống giải khát tuyệt vời dành cho mọi lứa tuổi.', 10200, 1, '320ml', 19, 'Việt Nam', 0),
(60, 'Trà ô long Tea Plus 320ml', 'Trà ô long Tea Plus 320ml với hương vị ngọt nhẹ thanh mát, mùi thơm đặc trưng cùng hoạt chất OTTP giúp hạn chế hấp thu chất chéo. Trà ô long Tea Plus giúp làm lắng nhẹ những cơn ưu tư mang đến cảm giác nhẹ nhàng. Thưởng thức nước trà ngay mỗi ngày để cuộc sống thêm nhẹ.', 8500, 7, '320ml', 22, 'Việt Nam', 0),
(61, 'Trà ô long xanh hương chanh Tea Plus 450ml', 'Trà ô long xanh hương chanh Tea Plus 450ml là một sản phẩm mới của nhãn hiệu Trà Ô Long TEA+, một thương hiệu trà được nhiều người dùng ưa chuộng trong hơn 10 năm tại thị trường Việt Nam. Sản phẩm được chế biến cẩn thận từ những lá trà tươi được thu hái tại các vùng trồng trà chất lượng cao. Qua quy trình chế biến và pha trộn đặc biệt, nước trà đem lại ly trà ngon mềm mại, hài hòa với hương thơm tự nhiên và hương vị đậm đà.', 10500, 7, '450ml', 22, 'Việt Nam', 0),
(62, 'Trà ô long Tea Plus vị đào 450ml', 'Trà ô long Tea Plus vị đào 450ml là sự kết hợp từ trà ô long và đào thơm mát, mang đến hương vị sản phẩm đặc trưng. Sản phẩm chứa chiết xuất từ trái cây thật, bổ sung thêm vitamin C không chỉ giải nhiệt tốt ma còn tăng cường sức đề kháng, chống lão hóa,...\r\n', 10500, 7, '450ml', 22, 'Việt Nam', 0),
(63, 'Trà thanh nhiệt Dr.Thanh 330ml', 'Trà thanh nhiệt Dr.Thanh 330ml có đường là sản phẩm nước trà đóng chai tiện dụng của thương hiệu trà thanh nhiệt Dr.Thanh với vị thanh mát, ngọt nhẹ, dễ uống, hòa phối tỉ mỉ từ 9 loại thảo mộc tự nhiên, giúp bạn thanh lọc cơ thể, không lo nóng trong người và tràn đầy sức sống.', 11500, 7, '330ml', 23, 'Việt Nam', 0),
(64, 'Trà thanh nhiệt Dr.Thanh 455ml', 'Được sản xuất với công nghệ chiết lạnh vô trùng Aseptic độc quyền từ thương hiệu Dr.Thanh giúp giữ lại tinh chất của 9 loại thảo mộc thiên nhiên mang lại hương vị trà thơm ngon, thanh mát và vị ngọt dịu tự nhiên, tốt cho sức khỏe, giúp giải nhanh cơn khát và thanh lọc cơ thể', 13000, 7, '455ml', 23, 'Việt Nam', 0),
(65, 'Trà xanh Không Độ vị chanh 455ml', 'Là sự kết hợp hài hòa, tươi mát giữa vị trà xanh chát dịu từ vùng đất Thái Nguyên và vị chanh tươi chua vừa tạo nên điểm nhấn, cùng vị ngọt nhẹ nhàng không gắt, mang đến sản phẩm giải khát tuyệt vời. Trà xanh Không độ chứa EGCG cao giúp giảm căng thẳng, mệt mỏi, tăng sức đề kháng và giúp tỉnh táo', 10500, 7, '455ml', 24, 'Việt Nam', 0),
(66, 'Trà xanh C2 hương chanh 225ml', 'Được sản xuất từ những lá trà xanh tự nhiên hòa quyện cùng hương chanh tươi mát cho bạn một thức uống giải khát tuyệt vời. Trà xanh chứa hàm lượng chất chống oxy hóa cao cùng vitamin C dồi dào từ chanh giúp bạn luôn giữ trạng thái năng động và hứng khởi.', 6000, 7, '225ml', 25, 'Việt Nam', 0),
(67, 'Trà đen C2 hương đào chai 225ml', 'Chắt lọc từ 100% trà tự nhiên chế biến và đóng chai trong cùng 1 ngày bởi trà C2, đem đến hương vị trà đậm đà tuyệt vời. Trà đen C2 hương đào chai 225ml mang lại cho bạn lựa chọn mới trong thưởng thức trà, giúp giải nhanh cơn khát, bổ sung năng lượng cho ngày dài năng động và sảng khoái', 6000, 7, '225ml', 25, 'Việt Nam', 0),
(68, 'Trà xanh C2 hương chanh 360ml', 'Được chiết xuất từ những lá trà xanh tự nhiên từ vùng cao nguyên Việt Nam hòa quyện cùng hương chanh tươi mát cho bạn một thức uống giải khát tuyệt vời những ngày nóng bức. Trà xanh chứa hàm lượng chất chống oxy hóa cao, giúp bạn luôn giữ trạng thái năng động và hứng khởi.', 8000, 7, '360ml', 25, 'Việt Nam', 0),
(69, 'Hồng trà vải C2 455ml', 'Được làm từ lá trà lên men tự nhiên kết hợp cùng quả vải mang đến cho bạn một hương vị trà vải sảng khoái, giải khát tột đỉnh. Trong trà có chứa nhiều chất chống oxy hóa giúp bạn luôn năng động, tỉnh táo và khỏe khoắn suốt ngày dài.', 10000, 7, '455ml', 25, 'Việt Nam', 0),
(70, 'Trà đen dưa lưới bạc hà C2 Freeze 455ml', 'Nước trà giải khát, hương vị mới lạ thơm ngon cực mát lạnh từ thương hiệu trà C2. Trà đen dưa lưới bạc hà C2 Freeze chai 455ml the mát đầy sảng khoái, được ủ từ là trà 100% tự nhiên, nhân đôi vị the mát từ dưa lưới và bạc hà, làm dịu ngày hè nắng nóng', 10200, 7, '455ml', 25, 'Việt Nam', 0),
(71, 'Trà bí đao Wonderfarm 310ml', 'Được làm từ những quả bí đao tươi, sản xuất trên dây chuyền hiện đại, giữ được trọn vẹn hương vị bí đao thơm ngon. Sản phẩm có hương vị ngọt nhẹ vừa phải, thơm mát vị đặc trưng của bí đao và có tác dụng giải nhiệt, thanh lọc cơ thể, bù nước rất tốt.', 8600, 7, '310ml', 26, 'Việt Nam', 0),
(72, 'Trà bí đao Wonderfarm 440ml', 'Trà bí đao Wonderfarm chai 440ml cung cấp nhiều vitamin như Caroten, B1, B2, B3, C và các vitamin khác. Hương thơm nhẹ nhàng của trà cũng làm kích thích vị giác người dùng, có tác dụng thanh lọc cơ thể, mát gan, giải nhiệt và giải khát hiệu quả. Trà bí đao Wonderfarm có thiết kế đóng chai nhỏ gọn cũng giúp bạn dễ dàng mang theo bất cứ đâu bạn muốn.', 56000, 7, '440ml', 26, 'Việt Nam', 0),
(73, 'Trà bí đao Wonderfarm 280ml', 'Được làm từ những quả bí đao tươi, sản xuất trên dây chuyền hiện đại, giữ được trọn vẹn hương vị bí đao thơm ngon, thanh mát cùng những dưỡng chất thiết yếu. Sản phẩm có hương vị ngọt nhẹ vừa phải, thơm mát vị đặc trưng của bí đao và có tác dụng giải nhiệt, thanh lọc cơ thể, bù nước rất tốt.', 7200, 7, '280ml', 26, 'Việt Nam', 0),
(74, 'Trà mật ong Boncha vị ô long đào 450ml', 'Trà mật ong Boncha vị ô long đào chai 450ml là sự kết hợp từ những trái đào mọng nước tươi ngon và là trà Ô Long cao cấp. Trà Boncha với 100% mật ong nguyên chất mang lại cảm giác sảng khoái, thanh mát, đánh bay cơn nóng bức. Nước trà là một lựa chọn lý tưởng để thưởng thức trong mọi dịp, đặc biệt là những chai trà đóng chai pha sẵn, vừa tiện lợi vừa giái khát.', 10000, 7, '450ml', 27, 'Việt Nam', 0),
(75, 'Trà mật ong Boncha vị việt quất 450ml', 'Nước trà đóng chai tiện lợi, thơm ngon và khác biệt với 100% mật ong nguyên chất kết hợp với việt quất thời thượng cuộn trào cùng trà xanh nguyên lá. Trà mật ong Boncha vị việt quất chai 450ml chính hãng trà Boncha giải khát giải nhiệt cho năng lượng đầy hứng khởi.', 10000, 7, '450ml', 27, 'Việt Nam', 0),
(76, 'Nước tăng lực Redbull 250ml', 'Nước tăng lực Redbull thành phần tự nhiên, mùi vị thơm ngon, sảng khoái. Nước tăng lực Redbull 250ml giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin và các khoáng chất, giúp xua tan cơn khát và cảm giác mệt mỏi. Nước tăng lực không có đường hóa học, không chứa hóa chất độc hại, đảm bảo an toàn', 10800, 6, '250ml', 28, 'Việt Nam', 0),
(77, 'Nước tăng lực Redbull Thái kẽm và vitamin 250ml', 'Nước tăng lực Redbull thành phần tự nhiên, thơm ngon, sảng khoái là thương hiệu nước tăng lực rất được ưa thích trên thế giới. Nước tăng lực Redbull Thái Kẽm Vitamin 250ml bổ sung thêm kẽm, vitamin và nhiều dinh dưỡng  giúp cơ thể bù đắp nước, bổ sung năng lượng cho bạn hoạt động dẻo dai', 12500, 6, '250ml', 28, 'Việt Nam', 0),
(78, 'Nước tăng lực Sting Gold 330ml', 'Nước tăng lực Sting thơm ngon của thương hiệu Sting được sản xuất từ các thành phần tự nhiên, mùi vị thơm ngon, sảng khoái. Nước tăng lực Sting Gold 330ml giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin C và E, giúp xua tan cơn khát và cảm giác mệt mỏi. Cam kết chính hãng và an toàn', 10800, 6, '330ml', 29, 'Việt Nam', 0),
(79, 'Nước tăng lực Sting hương dâu 330ml', 'Nước tăng lực Sting với mùi vị thơm ngon, sảng khoái, bổ sung hồng sâm chất lượng. Sting giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin C và E, giúp xua tan cơn khát và cảm giác mệt mỏi cùng dâu cho nhẹ nhàng và dễ chịu. Cam kết chính hãng, chất lượng và an toàn', 11200, 6, '330ml', 29, 'Việt Nam', 0),
(80, 'Nước tăng lực Sting Gold 320ml', 'Nước tăng lực Sting với thành phần tự nhiên kết hợp với hương vị nhân sâm tạo nên sự kết hợp độc đáo với mùi vị thơm ngon, sảng khoái. Sản phẩm giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin C và E, giúp xua tan cơn khát và cảm giác mệt mỏi, cho bạn cảm giác cuộn trào hứng khởi', 11200, 6, '320ml', 29, 'Việt Nam', 0),
(81, 'Nước tăng lực Sting Sleek hương dâu 320ml', 'Nước tăng lực Sting với mùi vị thơm ngon, sảng khoái, cùng hương dâu dễ chịu. Sting giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin C và E, giúp xua tan cơn khát và cảm giác mệt mỏi cùng dâu cho nhẹ nhàng và dễ chịu. Cam kết chính hãng, chất lượng và an toàn', 11500, 6, '320ml', 29, 'Việt Nam', 0),
(82, 'Nước bù khoáng Revive muối khoáng 500ml', 'Nước bù khoáng tác dụng bù khoáng, giảm mất nước, hỗ trợ cung cấp năng lượng và thuận tiện mang theo bên mình cho mọi chuyến đi. Nước bù khoáng Revive muối khoáng 500ml giúp bạn tự tin, năng động ngày hè oi bức. Nước khoáng Revive xua tan mệt mỏi, vận động cùng bạn', 11500, 6, '500ml', 30, 'Việt Nam', 0),
(83, 'Nước bù khoáng Revive chanh muối 390ml', 'Nước bù khoáng bổ sung khoáng chất, giảm mất nước, hỗ trợ cung cấp năng lượng và thuận tiện mang theo bên mình cho mọi chuyến đi. Nước bù khoáng Revive chanh muối 390ml giúp bạn tự tin, năng động ngày hè oi bức. Nước khoáng Revive xua tan mệt mỏi, vận động cùng bạn', 10200, 6, '390ml', 29, 'Việt Nam', 0),
(84, 'Nước tăng lực Monster Energy 355ml', 'Với thành phần chính bao gồm các loại vitamin và thảo dược sẽ cung cấp cho bạn vitamin B3, vitamin B6, vitamin B12, natri giúp cơ thể bạn khỏe mạnh. Sản phẩm với hương vị tươi mát sẽ lập tức đập tan cơn khát và giải tỏa cái nóng của mùa hè, đem đến cho người dùng sự tỉnh táo và sảng khoái.', 27000, 6, '355ml', 32, 'Malaysia', 0),
(85, 'Nước tăng lực Monster Energy Ultra 355ml', 'Nước tăng lực thơm ngon sản xuất tại Hà Lan. Nước tăng lực Monster Energy Ultra 355ml chính hãng nước tăng lực Monster Energy giải khát nhanh chóng, cug cấp cho cơ thể nguồn năng lượng mạnh mẽ, thể hiện đẳng cấp, phong cách sống khác biệt của những người trẻ năng động', 27000, 6, '355ml', 32, 'Hà Lan', 0),
(86, 'Nước tăng lực Lipovitan mật ong 250ml', 'Nước tăng lực làm từ thành phần và hương liệu tổng hợp cao cấp từ thương hiệu nước tăng lực Lipovitan mang đến chất lượng tuyệt hảo. Nước tăng lực Lipovitan hương vị mật ong 250ml thơm ngon, hấp dẫn giúp bạn đập tan cơn khát, bổ sung thêm năng lượng cho cơ thể khỏe khắn, mạnh mẽ, tràn đầy hứng khởi', 9500, 6, '250ml', 31, 'Việt Nam', 0),
(87, 'Nước tăng lực Number1 330ml', ' Sản phẩm nước tăng lực chất lượng thơm ngon của thương hiệu nước tăng lực Number 1. Nước tăng lực Number 1 330ml với sự kết hợp của Vitamin B3, Taurine, Inositol và Caffein giúp người sử dụng nạp nhanh năng lượng đồng thời duy trì sự tỉnh táo để chinh phục mọi thử thách.', 10500, 6, '330ml', 33, 'Việt Nam', 0),
(88, 'Nước tinh khiết Aquafina 355ml', 'Nước tinh khiết của thương hiệu nước suối Aquafina được lấy từ nguồn nước ngầm đảm bảo  trải qua quy trình khử trùng, lọc sạch các tạp chất. Nước tinh khiết Aquafina 355ml đã đạt tới trình độ nước tinh khiết có tác dụng dịu cơn khát, khi uống sẽ có cảm giác hơi ngọt ở miệng, rất dễ uống.', 4900, 5, '355ml', 34, 'Việt Nam', 0),
(89, 'Nước tinh khiết Aquafina 500ml', 'Được lấy từ nguồn nước ngầm đảm bảo  trải qua quy trình khử trùng, lọc sạch các tạp chất. Nước tinh khiết Aquafina 500ml đã đạt tới trình độ nước tinh khiết có tác dụng dịu cơn khát, khi uống sẽ có cảm giác hơi ngọt ở miệng, rất dễ uống. Nhỏ gọn tiện lợi dễ mang bên mình', 5300, 5, '500ml', 34, 'Việt Nam', 0),
(90, 'Nước giải khát có ga Aquafina Soda 320ml', 'Khoác lên mình thiết kế dạng lon nhôm bắt mắt phối hợp cùng các chi tiết đã được tối giản tối đa nhằm mang lại nét trẻ trung, năng động, hiện đại cho sản phẩm mới của thương hiệu nước suối Aquafina. Nước suối khoáng có ga Aquafina Soda 320ml rất phù hợp để pha chế nên các loại cocktail, soda mix,…', 7200, 5, '320ml', 34, 'Việt Nam', 0),
(91, 'Nước tinh khiết Aquafina 1.5 lít', 'Được lấy từ nguồn nước ngầm đảm bảo trải qua quy trình khử trùng, lọc sạch các tạp chất. Nước tinh khiết Aquafina 1.5 lít đã đạt tới trình độ nước tinh khiết có tác dụng dịu cơn khát, khi uống sẽ có cảm giác hơi ngọt ở miệng, rất dễ uống. Chai lớn tiết kiệm, dùng lâu hơn', 11300, 5, '1.5 lít', 34, 'Việt Nam', 0),
(92, 'Nước khoáng La Vie 350ml', 'Được sản xuất từ nguồn nước khoáng sâu trong lòng đất, được lọc qua nhiều tầng địa chất giàu khoáng chất, hấp thu muối, các yếu tố vi lượng như calcium, magie, potassium, sodium, bicarbonate... Sản phẩm không những cung cấp nước và khoáng chất và duy trì sinh lực cho cơ thể', 4600, 5, '350ml', 35, 'Việt Nam', 0),
(93, 'La Vie 500ml', 'Được sản xuất từ nguồn nước khoáng sâu trong lòng đất, lọc qua nhiều tầng địa chất giàu khoáng chất, hấp thu muối, các yếu tố vi lượng như calcium, magie, potassium, sodium, bicarbonate... Sản phẩm không những cung cấp nước và khoáng chất và duy trì sinh lực cho cơ thể. Chai nhỏ tiện dụng', 5100, 5, '500ml', 35, 'Việt Nam', 0),
(94, 'Nước khoáng La Vie 5 lít', 'Được sản xuất từ nguồn nước khoáng thiên nhiên chất lượng giàu khoáng chất, quy trình xử lý và đóng chai tiên tiến hiện đại. Nước khoáng La Vie 5 lít cung cấp đủ nước và khoáng cho cơ thể, thanh lọc da hiệu quả. Sản phẩm chính hãng nước khoáng LaVie, bình lớn tiết kiệm, dùng cho cả nhà', 26500, 5, '5 lít', 35, 'Việt Nam', 0),
(95, 'Nước tinh khiết Dasani 510ml', 'Từ nguồn nước ngầm thông qua hệ thống thẩm thấu ngược và thanh trùng bằng Ozone, đảm bảo sự thanh khiết trong từng giọt nước giúp thanh lọc cơ thể hoàn hảo của nước suối Dasani. Nước tinh khiết Dasani 510ml khi uống có vị tinh khiết, thanh mát giúp cơ thể bù nước', 5000, 5, '510ml', 36, 'Việt Nam', 0),
(96, 'Nước tinh khiết Dasani 1.5 lít', 'Từ nguồn nước ngầm thông qua hệ thống thẩm thấu ngược và thanh trùng bằng Ozone, đảm bảo sự thanh khiết trong từng giọt nước giúp thanh lọc cơ thể hoàn hảo của nước tinh khiết Dasani. Nước tinh khiết Dasani 1.5 lít khi uống có vị tinh khiết, thanh mát giúp cơ thể bù nước', 10000, 5, '1.5 lít', 36, 'Việt Nam', 0),
(97, 'Nước uống Good Mood vị sữa chua 455ml', 'Sản phẩm nước uống đóng chai độc đáo từ thương hiệu nước uống Good Mood. Nước uống Good Mood vị sữa chua 455ml là sự hòa quyện đầy mới mẻ từ sữa chua và nước uống có thể sử dụng hàng ngày như nước lọc, bổ sung nước cho cơ thể cùng hương vị dịu nhẹ tự nhiên, giúp tinh thần tươi mới', 9000, 5, '455ml', 37, 'Việt Nam', 0),
(98, 'Hộp 6 hũ nước yến sào Khánh Hòa 70ml', 'Nước yến Khánh Hòa cung cấp thêm các loại acid rất quan trọng cho sự tăng trưởng mô, cơ, tái tạo tế bào, kích thích sự tăng trưởng, chuyển hóa thần kinh và chức năng não bộ con người, tốt cho hoạt động gan,.. từ nước yến. Hộp 6 hũ nước yến sào Khánh Hòa 70ml nguyên chất, bổ dưỡng', 229000, 8, '70ml', 38, 'Việt Nam', 0),
(99, 'Lốc 6 lon nước yến sào Khánh Hòa 190ml', 'Nước yến Khánh Hòa cung cấp thêm các loại acid rất quan trọng cho sự tăng trưởng mô, cơ, tái tạo tế bào, kích thích sự tăng trưởng, chuyển hóa thần kinh và chức năng não bộ con người, tốt cho hoạt động gan,.. từ nước yến. Lốc 6 lon nước yến sào Khánh Hòa Gold 190ml nguyên chất, bổ dưỡng', 62000, 8, '190ml', 38, 'Việt Nam', 0),
(100, 'Nước yến sào cho bé Khánh Hòa 62ml', 'Nước yến Khánh Hòa cung cấp các loại acid rất quan trọng cho sự tăng trưởng mô, cơ, tái tạo tế bào, kích thích sự tăng trưởng, chuyển hóa thần kinh và chức năng não bộ, tốt cho hoạt động gan,.. từ nước yến. Nước yến sào cho bé Khánh Hòa 62ml chứa 15% yến đảo thiên nhiên, chất lượng, bổ dưỡng', 36500, 8, '62ml', 38, 'Việt Nam', 0),
(101, 'Hộp 6 hũ tổ yến chưng sẵn Sài Gòn Anpha thượng hạng 29% hương vani 70ml', 'Yến Sài Gòn Anpha thơm ngon, dinh dưỡng với hàm lượng yến cao đến 29% kết hợp cùng hương vani thơm dễ uống, phù hợp bồi bổ cơ thể. Hộp 6 hũ tổ yến chưng sẵn Sài Gòn Anpha thượng hạng 29% hương vani 70ml giúp tăng cường đề kháng khỏe mạnh, món quà tặng sức khỏe đến từ nhiên nhiên', 189000, 8, '70ml', 39, 'Việt Nam', 0),
(102, 'Nước yến Đông trung hạ thảo, kỷ tử và hạt chia Song Yến hương hạt sen 185ml', 'Nước yến Đông trung hạ thảo, kỷ tử và hạt chia Song Yến hương hạt sen 185ml là nước uống bổ dưỡng, được làm từ tổ yến chưng 10% và bổ sung thêm đông trung hạ thảo, kỷ tử và hạt chia. Sản phẩm phù hợp để sử dụng trong thực đơn hàng ngày, giúp cung cấp năng lượng và dưỡng chất cần thiết cho cơ thể.', 25000, 8, '185ml', 40, 'Việt Nam', 0),
(103, 'Hộp 6 hũ nước yến cho trẻ em Song Yến Kids Dream 70ml', 'Sản phẩm nước yến dinh dưỡng thơm ngon được làm tự tổ yến tự nhiên được bổ sung thêm vitamin K2 và vitamin B3 tốt cho sự phát triển của bé. Hộp 6 hũ nước yến cho trẻ em Song Yến Kids Dream 70ml chính hãng nước yến Song Yến - thương hiệu vì sức khỏe được nhiều người lựa chọn', 169000, 8, '70ml', 40, 'Việt Nam', 0),
(104, 'Hộp 6 hũ nước yến nguyên chất Song Yến 70ml', 'Nước yến Song Yến được đóng hũ sang trọng, dễ bảo quản. Hộp 6 hũ nước yến nguyên chất Song Yến 70ml có tác dụng tăng cường sức khỏe, làm giảm lão hóa, tăng cường trí tuệ nên nước yến là một thức yến không thể thiếu đối với các bậc trung niên và trẻ nhỏ.', 169000, 8, '70ml', 40, 'Việt Nam', 0),
(105, 'Chè dưỡng nhan tổ yến và trùng thảo Green Bird 175ml', 'Sản phẩm dinh dưỡng thơm ngon chính hãng đến từ thương hiệu yến Green Bird. Chè dưỡng nhan tổ yến và trùng thảo Green Bird 175ml được làm từ tổ yến, trùng thảo cùng nhiều loại hạt dinh dưỡng tốt cho cơ thể, chứa nguồn collagen thuần thiết từ thực vật mang đến hiệu quả làm đẹp da', 28000, 8, '175ml', 41, 'Việt Nam', 0),
(106, 'Nước yến sào collagen Green Bird 185ml', 'Nước yến dinh dưỡng của thương hiệu Green Bird tốt cho sức khỏe. Nước yến sào collagen Green Bird 185ml thơm ngon tự nhiên giúp tăng cường sức đề kháng và hệ miễn dịch hiệu quả. Nước yến Green Bird cho bạn một ngày làm việc, học tập tràn đầy năng lượng.', 28000, 8, '185ml', 41, 'Việt Nam', 0),
(107, 'Nước yến đông trùng hạ thảo Green Bird 185ml', 'Nước yến Green Bird là dòng sản phẩm nước yến có thành phần chủ yếu là tổ yến và đông trùng cùng nước tinh khiết,... Nước yến sào đông trùng hạ thảo Green Bird 185ml dành cho người làm việc, người chơi thể thao mỗi ngày cần được bổ sung dinh dưỡng, tăng cường hô hấp, duy trì sức khỏe', 28000, 8, '185ml', 41, 'Việt Nam', 0),
(108, 'Nước yến sào hạt chia Green Bird 185ml', 'Nước yến với nguồn nguyên liệu yến đảm bảo sạch, giàu dinh dưỡng giúp điều hòa và ổn định huyết áp cũng như giúp điều hòa nhịp tim. Nước yến sào hạt chia Green Bird 185ml được đóng hũ thủy tinh sang trọng, thích hợp làm quà từ nước yến Green Bird', 28000, 8, '185ml', 41, 'Việt Nam', 0),
(109, 'Tổ yến cho trẻ em chưng sẵn Win\'snest Kid 70ml', 'Nước yến dinh dưỡng thương hiệu nước yến Win\'snest với dòng sản phẩm Kid được thiết kế riêng cho trẻ nhỏ thơm ngon hảo hạng. Tổ yến cho trẻ em chưng sẵn Win\'snest Kid 70ml vô cùng hấp dẫn, chứa nhiều thành phần dưỡng chất cho bé, vị ngon dễ uống', 35000, 8, '70ml', 42, 'Việt Nam', 0),
(110, 'Tổ yến chưng đường phèn Win\'snest 70ml', 'Nước yến giúp ăn ngon, ngủ ngon, hồi phục sức khỏe được yến Win\'snest sản xuất theo quy trình hiện đại tạo ra sản phẩm tổ yến chưng đường phèn Win\'snest 70ml nguyên chất, dinh dưỡng và thơm ngon với những công dụng tuyệt vời phù hợp cho trẻ em và cả người lớn', 35000, 8, '70ml', 42, 'Việt Nam', 0),
(111, 'Nước yến sào Nunest đường phèn 190ml', 'Nước yến với nguồn nguyên liệu yến đảm bảo sạch, đường hữu cơ có lợi cho sức khỏe, giàu dinh dưỡng giúp điều hòa và ổn định huyết áp cũng như giúp điều hòa nhịp tim. Nước yến sào Nunest đường phèn 190ml được đóng lon tiện dụng, nước yến Nunest dễ dàng mang theo sử dụng bất kỳ lúc nào.', 11000, 8, '190ml', 43, 'Việt Nam', 0),
(112, 'Rượu soju Rice+ hương dứa 12.5% 360ml', 'Rượu hương dứa, ngọt thơm mới lạ trên nền rượu soju truyền thống Hàn Quốc cực thơm ngon rất được yêu thích. Rượu soju Rice+ hương dứa 12.5% chai 360ml chính hãng rượu soju Rice+ được làm từ những nguyên liệu tự nhiên an toàn, vị trái cây tươi mát dễ uống, phù hợp khẩu vị nhiều người', 44000, 9, '360ml', 44, 'Việt Nam', 0),
(113, 'Rượu soju Rice+ hương bưởi 12.5% 360ml', 'Rượu soju Hàn Quốc thơm ngon hấp dẫn kết hợp với hương vị trái cây tươi mát rất được yêu thích. Rượu soju Rice+ hương bưởi 12.5% chai 360ml chính hãng rượu soju Rice+ ngọt thơm dễ uống, được làm từ những nguyên liệu tự nhiên, an toàn, phù hợp dùng kèm các món lẩu, nướng, nhâm nhi cùng bạn bè', 44000, 9, '360ml', 44, 'Việt Nam', 0),
(114, 'Rượu soju Rice+ hương vải 12.5% 360ml', 'Rượu soju hương vị Hàn Quốc cực thơm ngon rất được yêu thích. Rượu soju Rice+ hương vải 12.5% chai 360ml chính hãng rượu soju Rice+ ngọt thơm dễ uống, phù hợp với khẩu vị nhiều người. Cam kết sử dụng nguyên liệu tự nhiên, đảm bảo an toàn, tươi mát mang đến trải nghiệm uống thích thú', 44000, 9, '360ml', 44, 'Việt Nam', 0),
(115, 'Rượu soju Rice+ hương đào 12.5% 360ml', 'Rượu soju hương vị Hàn Quốc cực thơm ngon rất được yêu thích. Rượu soju Rice+ vị đào 12.5% chai 360ml chính hãng rượu soju Rice+ sản xuất từ các nguyên liệu tự nhiên, đảm bảo an toàn, soju đào tươi mát, vị ngọt thơm dễ uống, phù hợp với khẩu vị nhiều người', 44000, 9, '360ml', 44, 'Việt Nam', 0),
(116, 'Rượu soju Rice+ truyền thống 16.5% 360ml', 'Rượu soju hương vị Hàn Quốc cực thơm ngon rất được yêu thích. Rượu soju Rice+ truyền thống 16.5% chai 360ml chính hãng rượu soju Rice+ sản xuất từ các nguyên liệu tự nhiên, đảm bảo an toàn, phù hợp với khẩu vị nhiều người', 44000, 9, '360ml', 44, 'Việt Nam', 0),
(117, 'Nước cam ép Twister Tropicana 1 lít', 'Chiết xuất từ những tép cam tươi nguyên chất tươi ngon và bổ dưỡng kết hợp công nghệ sản xuất hiện đại, mang lại thức uống có hương vị thơm ngon, tốt cho sức khỏe, giúp xua tan mọi cảm giác mệt mỏi, căng thẳng ngay tức thì, đem lại cảm giác thoải mái nhất sau mỗi lần sử dụng.', 23000, 3, '1 lít', 45, 'Việt Nam', 0),
(118, 'Nước ép cam Vinamilk 1 lít', 'Nước ép Vinamilk là thương hiệu hàng đầu Việt Nam với các dòng sản phẩm nước ép trái cây chất lượng cao. Nước ép cam Vinamilk 1 lít từ 2.6 kg cam mọng nước được ép nguyên chất cho vị ngon chua ngọt thanh dịu nhẹ, bổ sung năng lượng, vitamin C và các dưỡng chất thiết yếu, khuyên dùng mỗi ngày.', 59000, 3, '1 lít', 46, 'Việt Nam', 0),
(119, 'Nước ép nectar đào Vinamilk 1 lít', 'Nước ép Vinamilk là thương hiệu hàng đầu Việt Nam với các dòng sản phẩm nước ép trái cây chất lượng cao. Nước ép nectar đào Vinamilk 1 lít từ 1kg đào giòn ngọt được ép nguyên chất cho vị ngon ngọt thanh dịu nhẹ, bổ sung năng lượng, vitamin C và các dưỡng chất thiết yếu, khuyên dùng mỗi ngày.', 44000, 3, '1 lít', 46, 'Việt Nam', 0),
(120, 'Nước trái cây Ice+ vị đào 490ml', 'Nước trái cây thơm ngon chất lượng từ nước trái cây ICE+. Nước trái cây Ice+ vị đào 490ml thanh mát ngon ngọt giúp giải khát nhanh chóng, bù đắp năng lượng cho cơ thể sảng khoái.. Sản phẩm không màu nhân tạo, không sử dụng chất bảo quản cam kết an toàn cho người sử dụng. ', 8500, 3, '490ml', 47, 'Việt Nam', 0),
(121, 'Nước ép trái cây Jele L-carnitine vị vải 150g', 'Sản phẩm mang lại cảm giác ngon và lạ miệng vì có thạch rau câu trộn lẫn nước trái cây, hứa hàm lượng Vitamin C cao giúp tăng cường hệ miễn dịch, bảo vệ cơ thể chống lại nhiễm trùng, khuyến khích sản xuất tế bào bạch cầu, giúp hấp thụ Axit Amin từ Collagen cho làn da mịn màng và vóc dáng cân đối.', 15500, 3, '150g', 48, 'Thái lan', 0),
(122, 'Nước ép trái cây thạch Jele Beautie Vitamin A, C, E 150g', 'Sản phẩm nước trái cây dạng túi nhỏ gọn tiện dụng với hương vị kết hợp giữa dâu, chanh và nho tạo nên vị chua ngọt dịu nhẹ kích thích, kết hợp cùng thạch dai giòn mang đến cảm giác giải nhiệt ngay tức thì mang lại nhiều lợi ích cho sức khỏe và làn da. Có thể sử dụng trước bữa ăn để tạo sự ngon miệng', 15500, 3, '150g', 47, 'Thái lan', 0),
(123, 'Nước ép trái cây thạch Jele Beautie Collagen 150g', 'Sản phẩm nước ép dạng túi tiện dụng có thành phần chính từ trái cây tươi ngon, đã qua chọn lọc kĩ càng. Nước ép trái cây Jele Beautie Collagen có chứa thêm collagen và nhiều dưỡng chất khác cung cấp chất dinh dưỡng cho làn da bạn thêm căng tràn sức sống. Cam kết an toàn và chất lượng', 15500, 3, '150g', 48, 'Thái lan', 0),
(124, 'Nước cam có tép Teppy 1 lít', 'Chiết xuất từ những quả cam mọng nước cùng với những tép cam tươi hấp dẫn tự nhiên. Và được sản xuất theo công nghệ hiện đại, không chất độc hại không ảnh hưởng đến sức khỏe người tiêu dùng. Nước ép cam Teppy nguyên tép chứa nhiều vitamin C hỗ trợ cung cấp năng lượng cho cơ thể.', 23000, 3, '1 lít', 49, 'Việt Nam', 0),
(125, 'Nước sương sáo A1 Food 280ml', 'Được chiết xuất từ sương sáo tự nhiên, hương vị thanh mát, thơm ngon và đóng chai tiện lợi. Nước sương sáo A1 Food 280ml có thạch vô cùng hấp dẫn giúp bạn giải nhanh cơn nóng, giải khát và cung cấp năng lượng cho cơ thể. Sản phẩm chính hãng nước trái cây A1 Food', 12500, 3, '280ml', 50, 'Malaysia', 0),
(126, 'Nước cam ép Minute Maid Splash 320ml', 'Nước cam ép Minute MaId có tép khác biệt hẳn với các sản phẩm nước trái cây còn lại bởi vị cam tươi ngon, hương cam thơm mát và màu cam hoàn toàn tự nhiên. Mang hương vị tươi nguyên từ thiên nhiên, nước cam ép Minute Maid Splash 320ml là thức uống tuyệt hảo rất phù hợp với nhu cầu tiêu dùng thông minh hiện nay.', 9500, 3, '320ml', 51, 'Việt Nam', 0),
(127, 'Nước uống có thạch dừa Mogu Mogu vị vải 320ml', 'Thức uống trái câyrất được ưa chuộng tại Thái Lan. Nước uống có thạch dừa Mogu Mogu vị vải 320ml chính hãng nước trái cây Mogu Mogu với từng miếng thạch dừa tươi ngọt dai khi kết hợp cùng nước ép vải tươi ngon,...đã tạo nên một sức hút vô cùng lớn khiến người tiêu dùng.', 18500, 3, '320ml', 52, 'Thái lan', 0),
(128, 'Nước uống có thạch dừa Mogu Mogu vị dừa 320ml', 'Thức uống trái cây được ưa chuộng tại Thái Lan có thạch dừa với nhiều hương vị thơm ngon, cung cấp nước và các vitamin thiết yếu cho cơ thể. Là thức uống giải khát tuyệt vời cho những người vận động mạnh, cần bổ sung năng lượng cho cơ thể, lứa tuổi học sinh, sinh viên...', 18500, 3, '320ml', 52, 'Thái lan', 0),
(129, 'Nước uống có thạch dừa Mogu Mogu vị dâu 320ml', 'Thức uống được ưa chuộng tại Thái Lan có thạch dừa với nhiều hương vị thơm ngon, cung cấp nước và các vitamin thiết yếu cho cơ thể. Là thức uống giải khát tuyệt vời cho những người vận động mạnh, cần bổ sung năng lượng cho cơ thể, lứa tuổi học sinh, sinh viên...', 18500, 3, '320ml', 52, 'Thái lan', 0),
(130, 'Nước gạo rang Woongjin 1.5 lít', 'Sản phẩm đến từ Hàn Quốc là loại nước uống dinh dưỡng đầu tiên được làm từ gạo, với hương vị thơm ngon giúp bổ sung năng lượng và vitamin thiết yếu, cho cơ thể thanh nhiệt trong những ngày nắng nóng. Nước gạo rang có vị ngọt dịu, thơm dịu, hơi bùi và ngậy nhưng không ngấy', 45000, 3, '1.5 lít', 53, 'Hàn quốc', 0),
(131, 'Nước gạo lứt đen Woongjin 1.5 lít', 'Nước gạo lứt đen Woongjin 1.5 lít đến từ Hàn Quốc là loại nước uống dinh dưỡng đầu tiên được làm từ gạo lứt đen, với hương vị thơm ngon giúp bổ sung năng lượng và vitamin thiết yếu, cho cơ thể thanh nhiệt trong những ngày nắng nóng. Nước gạo rang có vị ngọt dịu, thơm dịu, hơi bùi và ngậy nhưng không ngấy', 67000, 3, '1.5 lít', 53, 'Hàn Quốc', 0),
(132, 'Nước cam Deedo Fruitku 450ml', 'Nước cam Deedo Fruitku 450ml là một thức uống giải khát giàu vitamin, bổ sung chất dinh dưỡng và cung cấp năng lượng cho cơ thể. Nước ép trái cây của Deedo Fruitku được sản xuất trên dây chuyền hiện đại tại Thái Lan, không chứa chất bảo quản, giúp đảm bảo chất lượng và sự tươi ngon của nước ép.', 13000, 3, '450ml', 54, 'Việt Nam', 0),
(133, 'Sữa trái cây Nutriboost hương bánh quy kem 297ml', 'Sữa trái cây hương vị mới lạ độc đáo chính hãng thương hiệu sữa trái cây Nutriboost nổi tiếng với là sự kết hợp hoàn hảo từ sữa và nước ép táo cùng hương bánh quy kem thơm thơm hấp dẫn. Sữa trái cây Nutriboost hương bánh quy kem 297ml bổ sung kẽm và các vitamin, cung cấp năng lượng cho cơ thể', 11000, 10, '297ml', 55, 'Việt Nam', 0),
(134, 'Sữa trái cây Nutriboost hương dâu 297ml', 'Sữa trái cây được làm sữa và nước trái cây vị dâu giúp bổ sung canxi cho hệ xương khớp chắc khỏe, các vitamin được bổ sung trong sữa trái cây Nutriboost dâu 297ml giúp bảo vệ cơ thể, tăng cường hệ miễn dịch, bảo vệ đôi mắt sáng khỏe. Sản phẩm chính hãng từ sữa trái cây Nutriboost', 11000, 10, '297ml', 55, 'Việt Nam', 0),
(135, 'Sữa trái cây Nutriboost hương cam 297ml', 'Sản phẩm là sự kết hợp hoàn hảo từ sữa và nước trái cây vị cam. Sữa trái cây Nutri Boost hương cam chai 297ml giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin B3, B6, E, C, xua tan cơn khát và cảm giác mệt mỏi. Sản phẩm sữa trái cây chất lượng từ thương hiệu sữa trái cây Nutriboost.', 11000, 10, '297ml', 55, 'Việt Nam', 0),
(136, 'Sữa trái cây Nutriboost hương dâu 1 lít', 'Dòng sản phẩm sữa trái cây của thương hiệu sữa trái cây Nutri Boost với vị dâu dễ uống thơm ngon, bổ dưỡng. Sữa trái cây Nutri Boost dâu chai nhựa PET 1 lít giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin B3, B6, E, C, Canxi và Kẽm rất có lợi cho cơ thể.', 29500, 10, '1 lít', 55, 'Việt Nam', 0),
(137, 'Sữa trái cây Nutriboost hương cam 1 lít', 'Dòng sản phẩm sữa hương trái cây của thương hiệu sữa trái cây Nutri Boost với vị cam dễ uống thơm ngon, bổ dưỡng. Sữa trái cây Nutri Boost hương cam giúp cơ thể bù đắp nước, bổ sung năng lượng, vitamin B3, B6, E, C, Canxi và Kẽm rất có lợi cho cơ thể, xua tan cơn khát và mệt mỏi.', 29500, 10, '1 lít', 55, 'Việt Nam', 0),
(138, 'Thạch trái cây YoMost hương dâu 180ml', 'Yomost là một thức uống làm từ sữa chua tự nhiên, dễ tiêu hóa và kết hợp với nước trái cây thiên nhiên ngon miệng. Lốc 4 hộp Thạch trái cây YoMost hương dâu 180ml cung cấp nhiều dưỡng chất quan trọng như protein, canxi và các loại vitamin cho cơ thể. Sữa chua được sản xuất bằng công nghệ cao, đảm bảo chất lượng và an toàn cho sức khỏe. Với vị ngon đặc trưng từ sữa chua và nước trái cây tự nhiên, Yomost giúp bạn khỏe mạnh và tràn đầy năng lượng để sống mỗi ngày trọn vẹn.', 9400, 10, '180ml', 56, 'Việt Nam', 0);
INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category_id`, `size`, `brand_id`, `origin`, `is_deleted`) VALUES
(139, 'Trà sữa KIRIN Tea Break 345ml', 'Trà sữa giúp giảm căng thẳng, mệt mỏi stress, trẻ lâu, lão hóa chậm, với trẻ em thì giúp tăng khả năng tiêu hóa. Trà sữa Kirin Tea Break 345ml kết hợp độc đáo giữa trà xanh tự nhiên và vị sữa dịu nhẹ mang đến cho bạn một thức uống bổ dưỡng lạ miệng của trà sữa Kirin', 11600, 10, '345ml', 57, 'Việt Nam', 0),
(140, 'Sữa trái cây Oggi vị cam 110ml', 'Sữa trái cây Oggi hương cam hộp 110ml vị cam thơm ngon và bổ sung nhiều vitamin và dưỡng chất cần thiết cho cơ thể. Sữa trái cây Oggi chứa protein, canxi, vitamin D3, K2 và HMO tăng cường sức đề kháng, hỗ trợ tiêu hóa sữa trái cây Oggi còn giúp phát triển chiều cao của trẻ.', 4500, 10, '110ml', 58, 'Việt Nam', 0),
(141, 'Sữa trái cây Kun hương nho 180ml', 'Sữa trái cây từ thương hiệu sữa trái cây LIF bổ sung 5 loại Vitamin và khoáng chất giúp tăng cường sức khỏe. Sữa trái cây LiF Kun hương nho 180ml thơm ngon tự nhiên mang lại cảm giác thưởng thức thật tuyệt vời', 7250, 10, '180ml', 59, 'Việt Nam', 0),
(142, 'Sữa trái cây Kun hương cam 180ml', 'Sự kết hợp tuyệt vời của nguồn sữa tươi chất lượng cao với vị cam tự nhiên. Sữa trái cây Lif kun hương cam 180ml dành riêng cho trẻ em giúp trẻ có đầy đủ năng lượng, khoáng chất cho các hoạt động học tập, vui chơi. Sữa trái cây chất lượng từ thương hiệu sữa trái cây LIF', 5800, 10, '180ml', 59, 'Việt Nam', 0),
(143, 'Sữa trái cây Kun hương trái cây nhiệt đới 180ml', 'Sữa trái cây thơm ngon từ thương hiệu sữa trái cây Kun bổ sung 5 loại Vitamin và khoáng chất giúp tăng cường sức khỏe mỗi ngày. Sữa trái cây kun trái cây nhiệt đới 180ml thơm ngon tự nhiên mang lại cảm giác thưởng thức thật tuyệt vời ', 7300, 10, '180ml', 59, 'Việt Nam', 0),
(144, 'nước cốt cà phê sữa NesCafé 75ml', 'Chất cà phê phin mà lại nhanh chóng như cà phê hòa tan chính là nước cốt cà phê được chiết xuất từ bột cà phê rang xay, mô phỏng kỹ thuật ủ cà phê phin, giữ lại nước cốt đầu tinh túy. Nước cốt cà phê sữa NesCafé 75ml chính hãng cà phê Nescafé cho bạn nguồn năng lượng sảng khoái', 10500, 2, '75ml', 60, 'Việt Nam', 0),
(145, 'nước cốt cà phê đen NesCafé 75ml', 'Sản phẩm được mô phỏng kỹ thuật pha phin bao gồm nén, ủ và chiết giữ lại hương vị cà phê đậm đà truyền thống. Hộp 5 gói nước cốt cà phê đen NesCafé 75ml chính hãng cà phê Nescafé tiện lợi, chỉ cần thêm đá dùng ngay, chất lượng như cà phê phin, nhanh chóng như cà phê hòa tan', 51000, 2, '75ml', 60, 'Việt Nam', 0),
(146, 'Cà phê sữa đá NesCafé nhân đôi sánh quyện 240g', 'Được làm từ 100% những hạt cà phê Việt Nam chất lượng, tự hào mang đến cho bạn ly cà phê sữa đá thuần tuý với hương vị cà phê mạnh mẽ kết hợp với vị béo dịu nhẹ của sữa thật giúp nạp nhanh năng lượng cho khởi đầu ngày mới tỉnh táo và sảng khoái ', 52000, 2, '240g', 60, 'Việt Nam', 0),
(147, 'Cà phê sữa NesCafé 3 in 1 đậm vị cà phê 320g', 'Từ 100% hạt cà phê Việt Nam chất lượng cao, gieo trồng và canh tác theo kỹ thuật của Nescafe Plan và sản xuất theo công nghệ nghiền nhuyễn hạt cà phê độc quyền của Nestle mang đến vị cà phê đậm đà hòa quyện cùng vị sữa thơm béo tạo ra sức hấp dẫn khó chối từ', 72000, 2, '320g', 60, 'Việt Nam', 0),
(148, 'Cà phê sữa VinaCafé Gold Original 480g', 'Cà phê hòa tan tiện lợi, chất lượng. Từ 100% từ hạt cà phê Robusta và Arabica chất lượng, trải qua dây chuyền sản xuất hiện đại và khép kín nhằm để cho ra loại cà phê có hương vị đậm đà hài hòa giữa vị cà phê và vị sữa cho bạn thưởng thức mỗi ngày.', 88000, 2, '480g', 62, 'Việt Nam', 0),
(149, 'Cà phê đen G7 30g', 'Cà phê hòa tan G7 cho vị cà phê đen chuẩn gu, cà phê đen thứ thiệt cho người sành cà phê yêu thích vị đắng đậm đà đặc trưng. Cà phê đen G7 30g mang đến cảm giác sảng khoái, tỉnh táo, tập trung tinh thần để bắt đầu một ngày làm việc hiệu quả', 39000, 2, '30g', 62, 'Việt Nam', 0),
(150, 'Cà phê sữa G7 3 in 1 800g', 'Cà phê hòa tan G7 3 trong 1 gói 800g chiết xuất trực tiếp từ những hạt cà phê sạch, thuần khiết từ vùng đất đỏ bazan huyền thoại Buôn Ma Thuột kết cà phê hòa tan G7 là loại cà phê hòa tan thứ thiệt thơm lừng, tuyệt ngon, giúp bạn nhanh chóng tỉnh táo để tập trung làm việc', 193000, 2, '800g', 62, 'Việt Nam', 0),
(151, 'Cà phê sữa Wake Up Café Sài Gòn 456g', 'Cà phê sữa hòa tan Wake up Café Sài Gòn 456g được sản xuất từ những nguyên liệu cà phê hòa tan chọn lọc, trên dây chuyền công nghệ hiện đại, đảm bảo đạt các tiêu chuẩn về an toàn sức khỏe cho người sử dụng. Cà phê hòa tan Wake up sẽ giúp cho bạn có một ngày làm việc tràn đầy hứng khởi và hiệu quả.', 56000, 2, '456g', 63, 'Việt Nam', 0),
(152, 'Cà phê hòa tan Trung Nguyên Legend Classic 204g', 'Hương cà phê xay đặc trưng, thể chất mạnh cùng vị đắng đậm pha lẫn chút ngọt bùi đầy bản sắc, mang đến những người đam mê cà phê một loại cà phê hòa tan chất lượng cao cung cấp năng lượng khơi dậy hứng khởi', 59000, 2, '204g', 64, 'Việt Nam', 0),
(153, 'Cà phê hòa tan Trung Nguyên Legend Special Edition 225g', 'Mang đến những người đam mê cà phê một loại cà phê hòa tan chất lượng cao, không chỉ đánh thức tiềm năng sáng tạo trong bạn, mà còn đem lại những tác dụng bất ngờ cho sức khỏe. Sản phẩm cà phê hòa tan chất lượng từ Trung Nguyên', 72000, 2, '255g', 64, 'Việt Nam', 0),
(154, 'Cà phê sữa Trung Nguyên Passiona 224g', 'Với hàm lượng caffein thấp, bổ sung collagen, chất chống lão hóa. Trung Nguyên đã tạo ra một sản phẩm cà phê đặc biệt đầu tiên và duy nhất dành cho những người phụ nữ đam mê hương vị cà phê, hấp dẫn không thể bỏ qua', 84000, 2, '224g', 64, 'Việt Nam', 0),
(155, 'Cà phê sữa MacCoffee Café Phố Gold 3in1 290g', 'Được chọn lọc từ nguồn cà phê hảo hạng với công thức phối trộn độc đáo, bổ sung thêm cà phê rang xay hương vị đậm đà. Cà phê sữa MacCoffee Café Phố Gold 3in1 290g chính hãng cà phê hòa tan Maccoffee, hương vị đúng chuẩn cà phê sữa pha phin thơm béo hấp dẫn', 66000, 2, '290g', 65, 'Việt Nam', 0),
(156, 'Cà phê sữa MacCoffee Café Phố nhà làm 3in1 840g', 'Sản phẩm cà phê hòa tan tiện lợi, thơm ngon hấp dẫn được bổ trong thêm cà phê rang xay mang đến hương vị đậm đà chân thật. Cà phê hoà tan MacCoffee Café Phố nhà làm 3in1 840g chính hãng cà phê MacCoffee mang đến cho bạn nguồn năng lượng tỉnh táo, giải tỏa căng thẳng và tràn đầy hứng khởi', 169000, 2, '840g', 65, 'Việt Nam', 0),
(157, 'Cà phê sữa MacCoffee Café Phố nhà làm 3in1 280g', 'Thơm ngon, đậm đà, sánh mịn chuẩn vị nhà làm cực hấp dẫn và an toàn, chất lượng. Sản phẩm là kết tinh từ những hạt cà phê rang xay tuyệt hảo cùng nguồn nguyên liệu chất lượng cao và tốt nhất mang hương vị hơn cả một ly cà phê sữa thơm ngon mà còn là cảm giác yêu thương, ấm áp của gia đình', 59000, 2, '280g', 65, 'Việt Nam', 0),
(159, 'Cà phê đen đá MacCoffee Café Phố 160g', 'Dòng sản phẩm được ưa chuộng của MacCoffee bởi hương vị cà phê đặc trưng đậm đà tinh tế, sánh quyện cùng hương thơm nồng nàn quyến rũ cho bạn tỉnh táo, bừng tỉnh năng lượng cho khởi đầu ngày mới tràn đầy hứng khởi. Sản phẩm cà phê hòa tan tiện lợi, chất lượng và đảm bảo an toàn', 45000, 2, '160g', 65, 'Việt Nam', 0),
(160, 'Cà phê sữa đá MacCoffee Café Phố 240g', 'Chắt lọc từ những hạt cà phê ngon nhất cùng bí quyết và công nghệ sản xuất hiện đại, quy trình sản xuất cà phê hòa tan khép kín, cà phê sữa đá MacCoffee Café Phố 240g chính hãng cà phê hòa tan MacCoffee đạt tiêu chuẩn an toàn thực phẩm, mang đến cho bạn khởi đầu ngày mới tỉnh táo và năng lượng', 60000, 2, '240g', 65, 'Việt Nam', 0),
(161, 'Cà phê muối Ông Bầu 220g', 'Cà phê muối Ông Bầu 220g là sản phẩm cà phê Việt đậm đà, kết hợp vị muối độc đáo. Vị mặn của muối làm tôn lên hương vị cà phê và sữa, tạo ra trải nghiệm thú vị. Sản phẩm tiện lợi với 10 gói mỗi gói 22g, chỉ cần 30 giây để thưởng thức một ly cà phê muối thơm ngon tại nhà.', 42000, 2, '220g', 66, 'Việt Nam', 0),
(162, 'Cà phê sữa đá Ông Bầu 240g', 'Cà phê sữa đá Ông Bầu 240g', 40000, 2, '240g', 66, 'Việt Nam', 0);

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
(130, 33, 'bia-bivina-export-lon-330ml-05.jpg'),
(131, 34, 'coca-cola-chai-nguyen-ban-390ml-01.jpg'),
(132, 34, 'coca-cola-chai-nguyen-ban-390ml-02.jpg'),
(133, 35, 'nuoc-ngot-co-ga-coca-cola-zero-chai-390ml-01.jpg'),
(134, 35, 'nuoc-ngot-co-ga-coca-cola-zero-chai-390ml-02.jpg'),
(135, 35, 'nuoc-ngot-co-ga-coca-cola-zero-chai-390ml-03.jpg'),
(136, 35, 'nuoc-ngot-co-ga-coca-cola-zero-chai-390ml-04.jpg'),
(137, 35, 'nuoc-ngot-co-ga-coca-cola-zero-chai-390ml-05.jpg'),
(138, 36, 'nuoc-ngot-coca-cola-zero-lon-320ml-01.jpg'),
(139, 36, 'nuoc-ngot-coca-cola-zero-lon-320ml-02.jpg'),
(140, 36, 'nuoc-ngot-coca-cola-zero-lon-320ml-03.jpg'),
(141, 36, 'nuoc-ngot-coca-cola-zero-lon-320ml-04.jpg'),
(142, 37, 'nuoc-ngot-coca-cola-light-lon-330ml-01.jpg'),
(143, 37, 'nuoc-ngot-coca-cola-light-lon-330ml-02.jpg'),
(144, 37, 'nuoc-ngot-coca-cola-light-lon-330ml-03.jpg'),
(145, 37, 'nuoc-ngot-coca-cola-light-lon-330ml-04.jpg'),
(146, 38, 'nuoc-ngot-coca-cola-lon-320ml-01.jpg'),
(147, 38, 'nuoc-ngot-coca-cola-lon-320ml-02.jpg'),
(148, 38, 'nuoc-ngot-coca-cola-lon-320ml-03.jpg'),
(149, 38, 'nuoc-ngot-coca-cola-lon-320ml-04.jpg'),
(150, 39, 'coca-nguyenban-1.5lit-01.jpg'),
(151, 39, 'coca-nguyenban-1.5lit-02.jpg'),
(152, 40, 'nuoc-ngot-pepsi-khong-calo-lon-320ml-01.jpg'),
(153, 40, 'nuoc-ngot-pepsi-khong-calo-lon-320ml-02.jpg'),
(154, 40, 'nuoc-ngot-pepsi-khong-calo-lon-320ml-03.jpg'),
(155, 40, 'nuoc-ngot-pepsi-khong-calo-lon-320ml-04.jpg'),
(156, 41, 'nuoc-ngot-pepsi-cola-lon-320ml-01.jpg'),
(157, 41, 'nuoc-ngot-pepsi-cola-lon-320ml-02.jpg'),
(158, 41, 'nuoc-ngot-pepsi-cola-lon-320ml-03.jpg'),
(159, 41, 'nuoc-ngot-pepsi-cola-lon-320ml-04.jpg'),
(160, 42, 'nuoc-ngot-pepsi-khong-calo-vi-chanh-lon-320ml-01.jpg'),
(161, 42, 'nuoc-ngot-pepsi-khong-calo-vi-chanh-lon-320ml-02.jpg'),
(162, 42, 'nuoc-ngot-pepsi-khong-calo-vi-chanh-lon-320ml-03.jpg'),
(163, 42, 'nuoc-ngot-pepsi-khong-calo-vi-chanh-lon-320ml-04.jpg'),
(164, 43, 'nuoc-ngot-pepsi-khong-calo-chai-390ml-01.jpg'),
(165, 43, 'nuoc-ngot-pepsi-khong-calo-chai-390ml-02.jpg'),
(166, 44, 'nuoc-ngot-pepsi-cola-chai-390ml-01.jpg'),
(167, 44, 'nuoc-ngot-pepsi-cola-chai-390ml-02.jpg'),
(168, 45, 'nuoc-ngot-pepsi-cola-chai-15-lit-01.jpg'),
(169, 45, 'nuoc-ngot-pepsi-cola-chai-15-lit-02.jpg'),
(170, 45, 'nuoc-ngot-pepsi-cola-chai-15-lit-03.jpg'),
(171, 46, 'z-2_202411041431130825.jpg'),
(172, 47, 'z-16_202411041452024177.jpg'),
(173, 48, 'fanta-huong-soda-kem-trai-cay-320ml-01.jpg'),
(174, 49, 'z-8_202411041615097169.jpg'),
(175, 50, 'nuoc-ngot-soda-chanh-7-up-lon-320ml-01.jpg'),
(176, 50, 'nuoc-ngot-soda-chanh-7-up-lon-320ml-02.jpg'),
(177, 50, 'nuoc-ngot-soda-chanh-7-up-lon-320ml-03.jpg'),
(178, 50, 'nuoc-ngot-soda-chanh-7-up-lon-320ml-04.jpg'),
(179, 51, 'nuoc-ngot-7-up-lon-330ml-01.jpg'),
(180, 51, 'nuoc-ngot-7-up-lon-330ml-02.jpg'),
(181, 51, 'nuoc-ngot-7-up-lon-330ml-03.jpg'),
(182, 51, 'nuoc-ngot-7-up-lon-330ml-04.jpg'),
(183, 52, 'nuoc-ngot-7-up-vi-chanh-chai-390ml-01.jpg'),
(184, 52, 'nuoc-ngot-7-up-vi-chanh-chai-390ml-02.jpg'),
(185, 52, 'nuoc-ngot-7-up-vi-chanh-chai-390ml-03.jpg'),
(186, 52, 'nuoc-ngot-7-up-vi-chanh-chai-390ml-04.jpg'),
(187, 53, 'nuoc-ngot-7-up-vi-chanh-chai-15-lit-01.jpg'),
(188, 53, 'nuoc-ngot-7-up-vi-chanh-chai-15-lit-02.jpg'),
(189, 53, 'nuoc-ngot-7-up-vi-chanh-chai-15-lit-03.jpg'),
(190, 53, 'nuoc-ngot-7-up-vi-chanh-chai-15-lit-04.jpg'),
(191, 54, 'nuoc-ngot-sprite-huong-chanh-lon-320ml-01.jpg'),
(192, 54, 'nuoc-ngot-sprite-huong-chanh-lon-320ml-02.jpg'),
(193, 54, 'nuoc-ngot-sprite-huong-chanh-lon-320ml-03.jpg'),
(194, 54, 'nuoc-ngot-sprite-huong-chanh-lon-320ml-04.jpg'),
(195, 55, 'nuoc-ngot-sprite-huong-chanh-185-lit-01.jpg'),
(196, 55, 'nuoc-ngot-sprite-huong-chanh-185-lit-02.jpg'),
(197, 55, 'nuoc-ngot-sprite-huong-chanh-185-lit-03.jpg'),
(198, 55, 'nuoc-ngot-sprite-huong-chanh-185-lit-04.jpg'),
(199, 56, 'soda-schweppes-lon-330ml-01.jpg'),
(200, 56, 'soda-schweppes-lon-330ml-02.jpg'),
(201, 56, 'soda-schweppes-lon-330ml-03.jpg'),
(202, 56, 'soda-schweppes-lon-330ml-04.jpg'),
(203, 56, 'soda-schweppes-lon-330ml-05.jpg'),
(204, 57, 'nuoc-ngot-mirinda-huong-cam-lon-320ml-01.jpg'),
(205, 57, 'nuoc-ngot-mirinda-huong-cam-lon-320ml-02.jpg'),
(206, 57, 'nuoc-ngot-mirinda-huong-cam-lon-320ml-03.jpg'),
(207, 57, 'nuoc-ngot-mirinda-huong-cam-lon-320ml-04.jpg'),
(208, 57, 'nuoc-ngot-mirinda-huong-cam-lon-320ml-05.jpg'),
(209, 58, 'nuoc-ngot-mirinda-vi-soda-kem-lon-320ml-01.jpg'),
(210, 58, 'nuoc-ngot-mirinda-vi-soda-kem-lon-320ml-02.jpg'),
(211, 58, 'nuoc-ngot-mirinda-vi-soda-kem-lon-320ml-03.jpg'),
(212, 58, 'nuoc-ngot-mirinda-vi-soda-kem-lon-320ml-04.jpg'),
(213, 59, 'nuoc-ngot-mirinda-huong-xa-xi-lon-320ml-01.jpg'),
(214, 59, 'nuoc-ngot-mirinda-huong-xa-xi-lon-320ml-02.jpg'),
(215, 59, 'nuoc-ngot-mirinda-huong-xa-xi-lon-320ml-03.jpg'),
(216, 59, 'nuoc-ngot-mirinda-huong-xa-xi-lon-320ml-04.jpg'),
(217, 60, 'tra-o-long-tea-plus-320ml-01.jpg'),
(218, 60, 'tra-o-long-tea-plus-320ml-02.jpg'),
(219, 60, 'tra-o-long-tea-plus-320ml-03.jpg'),
(220, 60, 'tra-o-long-tea-plus-320ml-04.jpg'),
(221, 60, 'tra-o-long-tea-plus-320ml-05.jpg'),
(222, 61, 'tra-o-long-xanh-huong-chanh-tea-plus-450ml-01.jpg'),
(223, 61, 'tra-o-long-xanh-huong-chanh-tea-plus-450ml-02.jpg'),
(224, 62, 'tra-o-long-tea-plus-vi-dao-450ml-01.jpg'),
(225, 62, 'tra-o-long-tea-plus-vi-dao-450ml-02.jpg'),
(226, 63, 'tra-thanh-nhiet-drthanh-330ml-01.jpg'),
(227, 63, 'tra-thanh-nhiet-drthanh-330ml-02.jpg'),
(228, 63, 'tra-thanh-nhiet-drthanh-330ml-03.jpg'),
(229, 63, 'tra-thanh-nhiet-drthanh-330ml-04.jpg'),
(230, 63, 'tra-thanh-nhiet-drthanh-330ml-05.jpg'),
(231, 64, 'tra-thanh-nhiet-drthanh-455ml-01.jpg'),
(232, 64, 'tra-thanh-nhiet-drthanh-455ml-02.jpg'),
(233, 64, 'tra-thanh-nhiet-drthanh-455ml-03.jpg'),
(234, 64, 'tra-thanh-nhiet-drthanh-455ml-04.jpg'),
(235, 65, 'tra-xanh-khong-do-vi-chanh-455ml-01.jpg'),
(236, 65, 'tra-xanh-khong-do-vi-chanh-455ml-02.jpg'),
(237, 65, 'tra-xanh-khong-do-vi-chanh-455ml-03.jpg'),
(238, 65, 'tra-xanh-khong-do-vi-chanh-455ml-04.jpg'),
(239, 65, 'tra-xanh-khong-do-vi-chanh-455ml-05.jpg'),
(240, 66, 'tra-xanh-c2-huong-chanh-230ml-01.jpg'),
(241, 66, 'tra-xanh-c2-huong-chanh-230ml-02.jpg'),
(242, 66, 'tra-xanh-c2-huong-chanh-230ml-03.jpg'),
(243, 66, 'tra-xanh-c2-huong-chanh-230ml-04.jpg'),
(244, 66, 'tra-xanh-c2-huong-chanh-230ml-05.jpg'),
(245, 67, 'tra-den-c2-huong-dao-chai-230ml-01.jpg'),
(246, 67, 'tra-den-c2-huong-dao-chai-230ml-02.jpg'),
(247, 67, 'tra-den-c2-huong-dao-chai-230ml-03.jpg'),
(248, 67, 'tra-den-c2-huong-dao-chai-230ml-04.jpg'),
(249, 67, 'tra-den-c2-huong-dao-chai-230ml-05.jpg'),
(250, 68, 'tra-xanh-c2-huong-chanh-360ml-01.jpg'),
(251, 68, 'tra-xanh-c2-huong-chanh-360ml-02.jpg'),
(252, 68, 'tra-xanh-c2-huong-chanh-360ml-03.jpg'),
(253, 68, 'tra-xanh-c2-huong-chanh-360ml-04.jpg'),
(254, 68, 'tra-xanh-c2-huong-chanh-360ml-05.jpg'),
(255, 69, 'hong-tra-vai-c2-455ml-01.jpg'),
(256, 69, 'hong-tra-vai-c2-455ml-02.jpg'),
(257, 70, 'tra-den-dua-luoi-bac-ha-c2-freeze-455ml-01.jpg'),
(258, 70, 'tra-den-dua-luoi-bac-ha-c2-freeze-455ml-02.jpg'),
(259, 70, 'tra-den-dua-luoi-bac-ha-c2-freeze-455ml-03.jpg'),
(260, 70, 'tra-den-dua-luoi-bac-ha-c2-freeze-455ml-04.jpg'),
(261, 70, 'tra-den-dua-luoi-bac-ha-c2-freeze-455ml-05.jpg'),
(262, 71, 'tra-bi-dao-wonderfarm-310ml-01.jpg'),
(263, 71, 'tra-bi-dao-wonderfarm-310ml-02.jpg'),
(264, 71, 'tra-bi-dao-wonderfarm-310ml-03.jpg'),
(265, 71, 'tra-bi-dao-wonderfarm-310ml-04.jpg'),
(266, 72, 'tra-bi-dao-wonderfarm-280ml-clone-01.jpg'),
(267, 72, 'tra-bi-dao-wonderfarm-280ml-clone-02.jpg'),
(268, 72, 'tra-bi-dao-wonderfarm-280ml-clone-03.jpg'),
(269, 73, 'tra-bi-dao-wonderfarm-280ml-01.jpg'),
(270, 73, 'tra-bi-dao-wonderfarm-280ml-02.jpg'),
(271, 73, 'tra-bi-dao-wonderfarm-280ml-03.jpg'),
(272, 73, 'tra-bi-dao-wonderfarm-280ml-04.jpg'),
(273, 74, 'tra-mat-ong-boncha-vi-o-long-dao-450ml-01.jpg'),
(274, 74, 'tra-mat-ong-boncha-vi-o-long-dao-450ml-02.jpg'),
(275, 75, 'tra-mat-ong-boncha-vi-tac-chai-450ml-clone-01.jpg'),
(276, 75, 'tra-mat-ong-boncha-vi-tac-chai-450ml-clone-02.jpg'),
(277, 75, 'tra-mat-ong-boncha-vi-tac-chai-450ml-clone-03.jpg'),
(278, 74, 'tra-mat-ong-boncha-vi-tac-chai-450ml-clone-04.jpg'),
(279, 76, 'nuoc-tang-luc-redbull-250ml-01.jpg'),
(280, 76, 'nuoc-tang-luc-redbull-250ml-02.jpg'),
(281, 76, 'nuoc-tang-luc-redbull-250ml-03.jpg'),
(282, 76, 'nuoc-tang-luc-redbull-250ml-04.jpg'),
(283, 77, 'nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-01.jpg'),
(284, 77, 'nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-02.jpg'),
(285, 77, 'nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-03.jpg'),
(286, 77, 'nuoc-tang-luc-redbull-thai-kem-va-vitamin-250ml-04.jpg'),
(287, 78, 'nuoc-tang-luc-sting-gold-chai-330ml-01.jpg'),
(288, 78, 'nuoc-tang-luc-sting-gold-chai-330ml-02.jpg'),
(289, 78, 'nuoc-tang-luc-sting-gold-chai-330ml-03.jpg'),
(290, 78, 'nuoc-tang-luc-sting-gold-chai-330ml-04.jpg'),
(291, 78, 'nuoc-tang-luc-sting-gold-chai-330ml-05.jpg'),
(292, 79, 'nuoc-tang-luc-sting-huong-dau-330ml-01.jpg'),
(293, 79, 'nuoc-tang-luc-sting-huong-dau-330ml-02.jpg'),
(294, 79, 'nuoc-tang-luc-sting-huong-dau-330ml-03.jpg'),
(295, 79, 'nuoc-tang-luc-sting-huong-dau-330ml-04.jpg'),
(296, 79, 'nuoc-tang-luc-sting-huong-dau-330ml-05.jpg'),
(297, 80, 'nuoc-tang-luc-sting-gold-320ml-01.jpg'),
(298, 80, 'nuoc-tang-luc-sting-gold-320ml-02.jpg'),
(299, 80, 'nuoc-tang-luc-sting-gold-320ml-03.jpg'),
(300, 81, 'nuoc-tang-luc-sting-sleek-huong-dau-320ml-01.jpg'),
(301, 81, 'nuoc-tang-luc-sting-sleek-huong-dau-320ml-02.jpg'),
(302, 81, 'nuoc-tang-luc-sting-sleek-huong-dau-320ml-03.jpg'),
(303, 81, 'nuoc-tang-luc-sting-sleek-huong-dau-320ml-04.jpg'),
(304, 82, 'nuoc-bu-khoang-revive-muoi-khoang-chai-500ml-01.jpg'),
(305, 82, 'nuoc-bu-khoang-revive-muoi-khoang-chai-500ml-02.jpg'),
(306, 82, 'nuoc-bu-khoang-revive-muoi-khoang-chai-500ml-03.jpg'),
(307, 83, 'nuoc-bu-khoang-revive-chanh-muoi-chai-390ml-01.jpg'),
(308, 83, 'nuoc-bu-khoang-revive-chanh-muoi-chai-390ml-02.jpg'),
(309, 83, 'nuoc-bu-khoang-revive-chanh-muoi-chai-390ml-03.jpg'),
(310, 84, 'nuoc-tang-luc-monster-energy-lon-355ml-01.jpg'),
(311, 84, 'nuoc-tang-luc-monster-energy-lon-355ml-02.jpg'),
(312, 84, 'nuoc-tang-luc-monster-energy-lon-355ml-04.jpg'),
(313, 85, 'nuoc-tang-luc-monster-energy-ultra-355ml-01.jpg'),
(314, 85, 'nuoc-tang-luc-monster-energy-ultra-355ml-02.jpg'),
(315, 85, 'nuoc-tang-luc-monster-energy-ultra-355ml-03.jpg'),
(316, 86, 'nuoc-tang-luc-lipovitan-mat-ong-lon-250ml-01.jpg'),
(317, 86, 'nuoc-tang-luc-lipovitan-mat-ong-lon-250ml-02.jpg'),
(318, 86, 'nuoc-tang-luc-lipovitan-mat-ong-lon-250ml-03.jpg'),
(319, 86, 'nuoc-tang-luc-lipovitan-mat-ong-lon-250ml-04.jpg'),
(320, 86, 'nuoc-tang-luc-lipovitan-mat-ong-lon-250ml-05.jpg'),
(321, 87, 'nuoc-tang-luc-number1-330ml-01.jpg'),
(322, 87, 'nuoc-tang-luc-number1-330ml-02.jpg'),
(323, 87, 'nuoc-tang-luc-number1-330ml-03.jpg'),
(324, 87, 'nuoc-tang-luc-number1-330ml-04.jpg'),
(325, 88, 'nuoc-tinh-khiet-aquafina-355ml-01.jpg'),
(326, 88, 'nuoc-tinh-khiet-aquafina-355ml-02.jpg'),
(327, 88, 'nuoc-tinh-khiet-aquafina-355ml-03.jpg'),
(328, 89, 'nuoc-tinh-khiet-aquafina-500ml-01.jpg'),
(329, 89, 'nuoc-tinh-khiet-aquafina-500ml-02.jpg'),
(330, 89, 'nuoc-tinh-khiet-aquafina-500ml-03.jpg'),
(331, 89, 'nuoc-tinh-khiet-aquafina-500ml-04.jpg'),
(332, 90, 'nuoc-giai-khat-co-ga-aquafina-soda-320ml-01.jpg'),
(333, 90, 'nuoc-giai-khat-co-ga-aquafina-soda-320ml-02.jpg'),
(334, 90, 'nuoc-giai-khat-co-ga-aquafina-soda-320ml-03.jpg'),
(335, 90, 'nuoc-giai-khat-co-ga-aquafina-soda-320ml-04.jpg'),
(336, 90, 'nuoc-giai-khat-co-ga-aquafina-soda-320ml-05.jpg'),
(337, 91, 'nuoc-tinh-khiet-aquafina-15-lit-01.jpg'),
(338, 91, 'nuoc-tinh-khiet-aquafina-15-lit-02.jpg'),
(339, 92, 'nuoc-khoang-la-vie-350ml-01.jpg'),
(340, 92, 'nuoc-khoang-la-vie-350ml-02.jpg'),
(341, 92, 'nuoc-khoang-la-vie-350ml-03.jpg'),
(342, 93, 'lavie-500ml-01.jpg'),
(343, 93, 'lavie-500ml-02.jpg'),
(344, 94, 'nuoc-khoang-la-vie-5-lit-01.jpg'),
(345, 94, 'nuoc-khoang-la-vie-5-lit-02.jpg'),
(346, 95, 'nuoc-tinh-khiet-dasani-500ml-01.jpg'),
(347, 95, 'nuoc-tinh-khiet-dasani-500ml-02.jpg'),
(348, 95, 'nuoc-tinh-khiet-dasani-500ml-03.jpg'),
(349, 95, 'nuoc-tinh-khiet-dasani-500ml-04.jpg'),
(350, 96, 'nuoc-tinh-khiet-dasani-15-lit-01.jpg'),
(351, 96, 'nuoc-tinh-khiet-dasani-15-lit-02.jpg'),
(352, 96, 'nuoc-tinh-khiet-dasani-15-lit-03.jpg'),
(353, 97, 'nuoc-uong-good-mood-vi-sua-chua-455ml-01.jpg'),
(354, 97, 'nuoc-uong-good-mood-vi-sua-chua-455ml-02.jpg'),
(355, 97, 'nuoc-uong-good-mood-vi-sua-chua-455ml-03.jpg'),
(356, 97, 'nuoc-uong-good-mood-vi-sua-chua-455ml-04.jpg'),
(357, 98, 'nuoc-yen-sao-khanh-hoa-70ml-01.jpg'),
(358, 98, 'nuoc-yen-sao-khanh-hoa-70ml-02.jpg'),
(359, 98, 'nuoc-yen-sao-khanh-hoa-70ml-03.jpg'),
(360, 98, 'nuoc-yen-sao-khanh-hoa-70ml-04.jpg'),
(365, 99, 'loc-6-lon-nuoc-yen-sao-khanh-hoa-gold-190ml-01.jpg'),
(366, 99, 'loc-6-lon-nuoc-yen-sao-khanh-hoa-gold-190ml-02.jpg'),
(367, 99, 'loc-6-lon-nuoc-yen-sao-khanh-hoa-gold-190ml-03.jpg'),
(368, 99, 'loc-6-lon-nuoc-yen-sao-khanh-hoa-gold-190ml-04.jpg'),
(369, 100, 'nuoc-yen-sao-cho-be-khanh-hoa-62ml-01.jpg'),
(370, 100, 'nuoc-yen-sao-cho-be-khanh-hoa-62ml-02.jpg'),
(371, 100, 'nuoc-yen-sao-cho-be-khanh-hoa-62ml-03.jpg'),
(372, 101, 'hop-6-to-yen-chung-san-sai-gon-anpha-01.jpg'),
(373, 101, 'hop-6-to-yen-chung-san-sai-gon-anpha-02.jpg'),
(374, 101, 'hop-6-to-yen-chung-san-sai-gon-anpha-03.jpg'),
(375, 101, 'hop-6-to-yen-chung-san-sai-gon-anpha-04.jpg'),
(376, 102, 'loc-4-hu-nuoc-yen-sao-va-sam-lat-green-bird-72g-clone-01.jpg'),
(377, 102, 'loc-4-hu-nuoc-yen-sao-va-sam-lat-green-bird-72g-clone-02.jpg'),
(381, 103, 'hop-6-hu-nuoc-yen-cho-tre-em-song-yen-kid-dream-70ml-01.jpg'),
(382, 103, 'hop-6-hu-nuoc-yen-cho-tre-em-song-yen-kid-dream-70ml-02.jpg'),
(383, 103, 'hop-6-hu-nuoc-yen-cho-tre-em-song-yen-kid-dream-70ml-03.jpg'),
(384, 103, 'hop-6-hu-nuoc-yen-cho-tre-em-song-yen-kid-dream-70ml-04.jpg'),
(385, 104, 'hop-6-hu-nuoc-yen-nguyen-chat-song-yen-70ml-01.jpg'),
(386, 104, 'hop-6-hu-nuoc-yen-nguyen-chat-song-yen-70ml-02.jpg'),
(387, 104, 'hop-6-hu-nuoc-yen-nguyen-chat-song-yen-70ml-03.jpg'),
(388, 104, 'hop-6-hu-nuoc-yen-nguyen-chat-song-yen-70ml-04.jpg'),
(389, 105, 'che-duong-nhan-to-yen-va-trung-thao-green-bird-175ml-01.jpg'),
(390, 105, 'che-duong-nhan-to-yen-va-trung-thao-green-bird-175ml-02.jpg'),
(391, 106, 'nuoc-yen-sao-collagen-green-bird-185ml-01.jpg'),
(392, 106, 'nuoc-yen-sao-collagen-green-bird-185ml-02.jpg'),
(393, 106, 'nuoc-yen-sao-collagen-green-bird-185ml-03.jpg'),
(398, 107, 'httpscdnv2tgddvnbhx-staticbhxproductsimages4585211704bhx1469306996-4202412231000519422_202412251438188370.jpg'),
(399, 108, 'nuoc-yen-sao-hat-chia-green-bird-185ml-01.jpg'),
(400, 108, 'nuoc-yen-sao-hat-chia-green-bird-185ml-02.jpg'),
(401, 109, 'to-yen-cho-tre-em-chung-san-winsnest-kid-70ml-01.jpg'),
(402, 109, 'to-yen-cho-tre-em-chung-san-winsnest-kid-70ml-02.jpg'),
(403, 109, 'to-yen-cho-tre-em-chung-san-winsnest-kid-70ml-03.jpg'),
(404, 109, 'to-yen-cho-tre-em-chung-san-winsnest-kid-70ml-04.jpg'),
(405, 109, 'to-yen-cho-tre-em-chung-san-winsnest-kid-70ml-05.jpg'),
(406, 110, 'to-yen-chung-duong-phen-winsnest-70ml-01.jpg'),
(407, 110, 'to-yen-chung-duong-phen-winsnest-70ml-02.jpg'),
(408, 110, 'to-yen-chung-duong-phen-winsnest-70ml-03.jpg'),
(409, 110, 'to-yen-chung-duong-phen-winsnest-70ml-04.jpg'),
(410, 110, 'to-yen-chung-duong-phen-winsnest-70ml-05.jpg'),
(411, 111, '412208-5_202412161533032524.jpg'),
(412, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-01.jpg'),
(413, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-02.jpg'),
(414, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-03.jpg'),
(415, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-04.jpg'),
(416, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-05.jpg'),
(417, 112, 'ruou-soju-rice-huong-dua-125-chai-360ml-06.jpg'),
(418, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-01.jpg'),
(419, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-02.jpg'),
(420, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-03.jpg'),
(421, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-04.jpg'),
(422, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-05.jpg'),
(423, 113, 'ruou-soju-rice-huong-buoi-125-chai-360ml-06.jpg'),
(424, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-01.jpg'),
(425, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-02.jpg'),
(426, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-03.jpg'),
(427, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-04.jpg'),
(428, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-05.jpg'),
(429, 114, 'ruou-soju-rice-huong-vai-125-chai-360ml-06.jpg'),
(430, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-01.jpg'),
(431, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-02.jpg'),
(432, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-03.jpg'),
(433, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-04.jpg'),
(434, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-05.jpg'),
(435, 115, 'ruou-soju-rice-huong-dao-125-chai-360ml-06.jpg'),
(436, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-01.jpg'),
(437, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-02.jpg'),
(438, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-03.jpg'),
(439, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-04.jpg'),
(440, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-05.jpg'),
(441, 116, 'ruou-soju-rice-truyen-thong-165-chai-360ml-06.jpg'),
(442, 117, 'nuoc-cam-ep-twister-tropicana-1-lit-01.jpg'),
(443, 117, 'nuoc-cam-ep-twister-tropicana-1-lit-02.jpg'),
(444, 117, 'nuoc-cam-ep-twister-tropicana-1-lit-03.jpg'),
(445, 117, 'nuoc-cam-ep-twister-tropicana-1-lit-04.jpg'),
(446, 117, 'nuoc-cam-ep-twister-tropicana-1-lit-05.jpg'),
(447, 118, 'nuoc-ep-cam-vinamilk-1-lit-01.jpg'),
(448, 118, 'nuoc-ep-cam-vinamilk-1-lit-02.jpg'),
(449, 118, 'nuoc-ep-cam-vinamilk-1-lit-03.jpg'),
(450, 119, 'nuoc-ep-nectar-dao-vinamilk-1-lit-01.jpg'),
(451, 119, 'nuoc-ep-nectar-dao-vinamilk-1-lit-02.jpg'),
(452, 119, 'nuoc-ep-nectar-dao-vinamilk-1-lit-03.jpg'),
(453, 120, 'nuoc-trai-cay-ice-vi-dao-490ml-01.jpg'),
(454, 120, 'nuoc-trai-cay-ice-vi-dao-490ml-02.jpg'),
(455, 120, 'nuoc-trai-cay-ice-vi-dao-490ml-03.jpg'),
(456, 121, 'nuoc-ep-trai-cay-jele-l-carnitine-vi-vai-150g-01.jpg'),
(457, 121, 'nuoc-ep-trai-cay-jele-l-carnitine-vi-vai-150g-02.jpg'),
(458, 121, 'nuoc-ep-trai-cay-jele-l-carnitine-vi-vai-150g-03.jpg'),
(459, 121, 'nuoc-ep-trai-cay-jele-l-carnitine-vi-vai-150g-04.jpg'),
(460, 122, 'nuoc-ep-trai-cay-thach-jele-beautie-vitamin-a-c-e-150g-01.jpg'),
(461, 122, 'nuoc-ep-trai-cay-thach-jele-beautie-vitamin-a-c-e-150g-02.jpg'),
(462, 122, 'nuoc-ep-trai-cay-thach-jele-beautie-vitamin-a-c-e-150g-03.jpg'),
(463, 122, 'nuoc-ep-trai-cay-thach-jele-beautie-vitamin-a-c-e-150g-04.jpg'),
(464, 123, 'nuoc-ep-trai-cay-thach-jele-beautie-collagen-150g-01.jpg'),
(465, 123, 'nuoc-ep-trai-cay-thach-jele-beautie-collagen-150g-02.jpg'),
(466, 123, 'nuoc-ep-trai-cay-thach-jele-beautie-collagen-150g-03.jpg'),
(467, 123, 'nuoc-ep-trai-cay-thach-jele-beautie-collagen-150g-04.jpg'),
(468, 124, 'nuoc-cam-co-tep-teppy-1lit-01.jpg'),
(469, 124, 'nuoc-cam-co-tep-teppy-1lit-02.jpg'),
(470, 125, 'nuoc-suong-sao-a1-food-280ml-01.jpg'),
(471, 125, 'nuoc-suong-sao-a1-food-280ml-02.jpg'),
(472, 125, 'nuoc-suong-sao-a1-food-280ml-03.jpg'),
(473, 125, 'nuoc-suong-sao-a1-food-280ml-04.jpg'),
(474, 125, 'nuoc-suong-sao-a1-food-280ml-05.jpg'),
(475, 125, 'nuoc-suong-sao-a1-food-280ml-06.jpg'),
(476, 125, 'nuoc-suong-sao-a1-food-280ml-07.jpg'),
(477, 126, 'nuoc-cam-ep-minute-maid-320ml-01.jpg'),
(478, 126, 'nuoc-cam-ep-minute-maid-320ml-02.jpg'),
(479, 127, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-vai-320ml-01.jpg'),
(480, 127, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-vai-320ml-02.jpg'),
(481, 127, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-vai-320ml-03.jpg'),
(482, 127, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-vai-320ml-04.jpg'),
(483, 127, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-vai-320ml-05.jpg'),
(484, 128, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dua-320ml-01.jpg'),
(485, 128, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dua-320ml-02.jpg'),
(486, 128, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dua-320ml-03.jpg'),
(487, 128, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dua-320ml-04.jpg'),
(488, 128, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dua-320ml-05.jpg'),
(489, 129, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dau-320ml-01.jpg'),
(490, 129, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dau-320ml-02.jpg'),
(491, 129, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dau-320ml-03.jpg'),
(492, 129, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dau-320ml-04.jpg'),
(493, 129, 'nuoc-uong-co-thach-dua-mogu-mogu-vi-dau-320ml-05.jpg'),
(494, 130, 'nuoc-gao-rang-woongjin-morning-han-quoc-chai-1500m-01.jpg'),
(495, 130, 'nuoc-gao-rang-woongjin-morning-han-quoc-chai-1500m-02.jpg'),
(496, 130, 'nuoc-gao-rang-woongjin-morning-han-quoc-chai-1500m-03.jpg'),
(497, 130, 'nuoc-gao-rang-woongjin-morning-han-quoc-chai-1500m-04.jpg'),
(498, 131, 'nuoc-gao-rang-woongjin-15-lit-01.jpg'),
(499, 131, 'nuoc-gao-rang-woongjin-15-lit-02.jpg'),
(500, 131, 'nuoc-gao-rang-woongjin-15-lit-03.jpg'),
(501, 132, 'frame-1-1_202412040904596181.jpg'),
(502, 133, 'sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-01.jpg'),
(503, 133, 'sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-02.jpg'),
(504, 133, 'sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-03.jpg'),
(505, 133, 'sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-04.jpg'),
(506, 133, 'sua-trai-cay-nutriboost-huong-banh-quy-kem-297ml-05.jpg'),
(507, 134, 'sua-trai-cay-nutriboost-huong-dau-297ml-01.jpg'),
(508, 134, 'sua-trai-cay-nutriboost-huong-dau-297ml-02.jpg'),
(509, 134, 'sua-trai-cay-nutriboost-huong-dau-297ml-03.jpg'),
(510, 134, 'sua-trai-cay-nutriboost-huong-dau-297ml-04.jpg'),
(511, 134, 'sua-trai-cay-nutriboost-huong-dau-297ml-05.jpg'),
(512, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-01.jpg'),
(513, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-02.jpg'),
(514, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-03.jpg'),
(515, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-04.jpg'),
(516, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-05.jpg'),
(517, 135, 'sua-trai-cay-nutriboost-huong-cam-297ml-06.jpg'),
(518, 136, 'sua-trai-cay-nutriboost-huong-dau-1-lit-01.jpg'),
(519, 136, 'sua-trai-cay-nutriboost-huong-dau-1-lit-02.jpg'),
(520, 136, 'sua-trai-cay-nutriboost-huong-dau-1-lit-03.jpg'),
(521, 136, 'sua-trai-cay-nutriboost-huong-dau-1-lit-04.jpg'),
(522, 137, 'sua-trai-cay-nutriboost-huong-cam-1-lit-01.jpg'),
(523, 137, 'sua-trai-cay-nutriboost-huong-cam-1-lit-02.jpg'),
(524, 137, 'sua-trai-cay-nutriboost-huong-cam-1-lit-03.jpg'),
(525, 138, 'loc-4-hop-thach-trai-cay-yomost-huong-dau-180ml-202406181624459189.jpg'),
(526, 139, 'tra-sua-kirin-tea-break-345ml-01.jpg'),
(527, 139, 'tra-sua-kirin-tea-break-345ml-02.jpg'),
(528, 139, 'tra-sua-kirin-tea-break-345ml-03.jpg'),
(529, 139, 'tra-sua-kirin-tea-break-345ml-04.jpg'),
(530, 140, 'sua-trai-cay-oggi-vitadairy-huong-cam-hop-110ml-01.jpg'),
(531, 140, 'sua-trai-cay-oggi-vitadairy-huong-cam-hop-110ml-02.jpg'),
(532, 141, 'sua-trai-cay-lif-kun-huong-nho-180ml-01.jpg'),
(533, 141, 'sua-trai-cay-lif-kun-huong-nho-180ml-02.jpg'),
(534, 141, 'sua-trai-cay-lif-kun-huong-nho-180ml-03.jpg'),
(535, 141, 'sua-trai-cay-lif-kun-huong-nho-180ml-04.jpg'),
(536, 141, 'sua-trai-cay-lif-kun-huong-nho-180ml-05.jpg'),
(537, 142, 'sua-trai-cay-lif-kun-huong-cam-180ml-01.jpg'),
(538, 142, 'sua-trai-cay-lif-kun-huong-cam-180ml-02.jpg'),
(539, 142, 'sua-trai-cay-lif-kun-huong-cam-180ml-03.jpg'),
(540, 142, 'sua-trai-cay-lif-kun-huong-cam-180ml-04.jpg'),
(541, 142, 'sua-trai-cay-lif-kun-huong-cam-180ml-05.jpg'),
(542, 143, 'sua-trai-cay-kun-huong-trai-cay-nhiet-doi-180ml-01.jpg'),
(543, 143, 'sua-trai-cay-kun-huong-trai-cay-nhiet-doi-180ml-02.jpg'),
(544, 143, 'sua-trai-cay-kun-huong-trai-cay-nhiet-doi-180ml-03.jpg'),
(545, 143, 'sua-trai-cay-kun-huong-trai-cay-nhiet-doi-180ml-04.jpg'),
(546, 144, 'nuoc-cot-ca-phe-sua-nescafe-75ml-01.jpg'),
(547, 144, 'nuoc-cot-ca-phe-sua-nescafe-75ml-02.jpg'),
(548, 144, 'nuoc-cot-ca-phe-sua-nescafe-75ml-03.jpg'),
(549, 145, 'nuoc-cot-ca-phe-den-nescafe-75ml-01.jpg'),
(550, 145, 'nuoc-cot-ca-phe-den-nescafe-75ml-02.jpg'),
(551, 145, 'nuoc-cot-ca-phe-den-nescafe-75ml-03.jpg'),
(552, 146, 'ca-phe-sua-da-nescafe-nhan-doi-sanh-quyen-240g-01.jpg'),
(553, 146, 'ca-phe-sua-da-nescafe-nhan-doi-sanh-quyen-240g-02.jpg'),
(554, 146, 'ca-phe-sua-da-nescafe-nhan-doi-sanh-quyen-240g-03.jpg'),
(555, 146, 'ca-phe-sua-da-nescafe-nhan-doi-sanh-quyen-240g-04.jpg'),
(556, 146, 'ca-phe-sua-da-nescafe-nhan-doi-sanh-quyen-240g-05.jpg'),
(557, 147, 'ca-phe-sua-nescafe-3-in-1-dam-vi-ca-phe-340g-01.jpg'),
(558, 147, 'ca-phe-sua-nescafe-3-in-1-dam-vi-ca-phe-340g-02.jpg'),
(559, 148, 'ca-phe-sua-vinacafe-gold-original-480g-01.jpg'),
(560, 148, 'ca-phe-sua-vinacafe-gold-original-480g-02.jpg'),
(561, 148, 'ca-phe-sua-vinacafe-gold-original-480g-03.jpg'),
(562, 148, 'ca-phe-sua-vinacafe-gold-original-480g-04.jpg'),
(563, 149, 'ca-phe-den-g7-30g-01.jpg'),
(564, 149, 'ca-phe-den-g7-30g-02.jpg'),
(565, 149, 'ca-phe-den-g7-30g-03.jpg'),
(566, 149, 'ca-phe-den-g7-30g-04.jpg'),
(567, 150, 'ca-phe-sua-g7-3-in-1-800g-01.jpg'),
(568, 150, 'ca-phe-sua-g7-3-in-1-800g-02.jpg'),
(569, 150, 'ca-phe-sua-g7-3-in-1-800g-03.jpg'),
(570, 150, 'ca-phe-sua-g7-3-in-1-800g-04.jpg'),
(571, 150, 'ca-phe-sua-g7-3-in-1-800g-05.jpg'),
(572, 151, 'ca-phe-sua-wake-up-cafe-sai-gon-456g-24-goi-x-19g-01.jpg'),
(573, 151, 'ca-phe-sua-wake-up-cafe-sai-gon-456g-24-goi-x-19g-02.jpg'),
(574, 151, 'ca-phe-sua-wake-up-cafe-sai-gon-456g-24-goi-x-19g-03.jpg'),
(575, 152, 'ca-phe-hoa-tan-trung-nguyen-legend-classic-204g-01.jpg'),
(576, 152, 'ca-phe-hoa-tan-trung-nguyen-legend-classic-204g-02.jpg'),
(577, 152, 'ca-phe-hoa-tan-trung-nguyen-legend-classic-204g-03.jpg'),
(578, 152, 'ca-phe-hoa-tan-trung-nguyen-legend-classic-204g-04.jpg'),
(579, 152, 'ca-phe-hoa-tan-trung-nguyen-legend-classic-204g-05.jpg'),
(580, 153, 'ca-phe-hoa-tan-trung-nguyen-legend-special-edition-225g-01.jpg'),
(581, 153, 'ca-phe-hoa-tan-trung-nguyen-legend-special-edition-225g-02.jpg'),
(582, 153, 'ca-phe-hoa-tan-trung-nguyen-legend-special-edition-225g-03.jpg'),
(583, 153, 'ca-phe-hoa-tan-trung-nguyen-legend-special-edition-225g-04.jpg'),
(584, 153, 'ca-phe-hoa-tan-trung-nguyen-legend-special-edition-225g-05.jpg'),
(585, 154, 'ca-phe-sua-trung-nguyen-passiona-224g-01.jpg'),
(586, 154, 'ca-phe-sua-trung-nguyen-passiona-224g-02.jpg'),
(587, 154, 'ca-phe-sua-trung-nguyen-passiona-224g-03.jpg'),
(588, 154, 'ca-phe-sua-trung-nguyen-passiona-224g-04.jpg'),
(589, 154, 'ca-phe-sua-trung-nguyen-passiona-224g-05.jpg'),
(590, 155, 'ca-phe-sua-maccoffee-cafe-pho-gold-3in1-290g-01.jpg'),
(591, 155, 'ca-phe-sua-maccoffee-cafe-pho-gold-3in1-290g-02.jpg'),
(592, 155, 'ca-phe-sua-maccoffee-cafe-pho-gold-3in1-290g-03.jpg'),
(593, 155, 'ca-phe-sua-maccoffee-cafe-pho-gold-3in1-290g-04.jpg'),
(594, 155, 'ca-phe-sua-maccoffee-cafe-pho-gold-3in1-290g-05.jpg'),
(595, 156, 'ca-phe-hoa-tan-maccoffee-cafe-pho-nha-lam-3in1-840g-01.jpg'),
(596, 156, 'ca-phe-hoa-tan-maccoffee-cafe-pho-nha-lam-3in1-840g-02.jpg'),
(597, 156, 'ca-phe-hoa-tan-maccoffee-cafe-pho-nha-lam-3in1-840g-03.jpg'),
(598, 156, 'ca-phe-hoa-tan-maccoffee-cafe-pho-nha-lam-3in1-840g-04.jpg'),
(599, 156, 'ca-phe-hoa-tan-maccoffee-cafe-pho-nha-lam-3in1-840g-05.jpg'),
(600, 157, 'ca-phe-sua-maccoffee-cafe-pho-nha-lam-280g-01.jpg'),
(601, 157, 'ca-phe-sua-maccoffee-cafe-pho-nha-lam-280g-02.jpg'),
(602, 157, 'ca-phe-sua-maccoffee-cafe-pho-nha-lam-280g-03.jpg'),
(608, 159, 'ca-phe-den-da-maccoffee-cafe-pho-160g-01.jpg'),
(609, 159, 'ca-phe-den-da-maccoffee-cafe-pho-160g-02.jpg'),
(610, 159, 'ca-phe-den-da-maccoffee-cafe-pho-160g-03.jpg'),
(611, 159, 'ca-phe-den-da-maccoffee-cafe-pho-160g-04.jpg'),
(612, 159, 'ca-phe-den-da-maccoffee-cafe-pho-160g-05.jpg'),
(613, 160, 'ca-phe-sua-da-maccoffee-cafe-pho-240g-01.jpg'),
(614, 160, 'ca-phe-sua-da-maccoffee-cafe-pho-240g-02.jpg'),
(615, 160, 'ca-phe-sua-da-maccoffee-cafe-pho-240g-03.jpg'),
(616, 160, 'ca-phe-sua-da-maccoffee-cafe-pho-240g-04.jpg'),
(617, 161, 'ca-phe-sua-da-ong-bau-240g-clone-01.jpg'),
(618, 161, 'ca-phe-sua-da-ong-bau-240g-clone-02.jpg'),
(619, 161, 'ca-phe-sua-da-ong-bau-240g-clone-03.jpg'),
(620, 161, 'ca-phe-sua-da-ong-bau-240g-clone-04.jpg'),
(621, 162, 'ca-phe-sua-da-ong-bau-240g-01.jpg'),
(622, 162, 'ca-phe-sua-da-ong-bau-240g-02.jpg'),
(623, 162, 'ca-phe-sua-da-ong-bau-240g-03.jpg'),
(624, 162, 'ca-phe-sua-da-ong-bau-240g-04.jpg'),
(625, 162, 'ca-phe-sua-da-ong-bau-240g-05.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profitmargin`
--

CREATE TABLE `profitmargin` (
  `margin_percent` float DEFAULT NULL COMMENT 'Tỉ lệ lợi nhuận cố định (ví dụ: 10.0 cho 10%)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profitmargin`
--

INSERT INTO `profitmargin` (`margin_percent`) VALUES
(10);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `role_id`) VALUES
(1, 'staff1', '$2y$10$6gX8v7BT9bHuV3Rxg6PXGe6roRNTDxy9FZJXIkMjdxVjiyLHZGPKG', 'staff1@example.com', '0900000001', 'Hà Nội', 4),
(2, 'staff2', '$2y$10$tVx2T2MvzP0iZrE7nYOLzOVZVXnvBb45uMzI56XwIdWRoLzoMAvZG', 'staff2@example.com', '0900000002', 'TP.HCM', 4),
(3, 'staff3', '$2y$10$AKcM35.6o7Dk0Jd5ujbQdOyaT9aS2o1X6Fh5TOLZDPjUkB4ScwArW', 'staff3@example.com', '0900000003', 'Đà Nẵng', 4),
(4, 'staff4', '$2y$10$Ru6hDniEHFZsQT7EVWeRReGRQZcK4UeS81n3qsNYBb6FxmdTXxU6a', 'staff4@example.com', '0900000004', 'Cần Thơ', 4),
(5, 'staff5', '$2y$10$9z8hF1DJE1E2zJ0k7OZLqOYKUfxksTI7Hg3mN8KyULfxy27RlVOt2', 'staff5@example.com', '0900000005', 'Hải Phòng', 4),
(6, 'staff6', '$2y$10$SxN81AxBejC/dEgeU5Tfqu0fzqlFEGRkxFgmCkKPvWIv97XYLRi8K', 'staff6@example.com', '0900000006', 'Huế', 4),
(7, 'user1', '$2y$10$qGUuz0BD.7XtKHXLc03nDOa1tMHPgVDEkU4BiwCTKQ1YZJQHURtJK', 'user1@example.com', '0123456781', 'Address 1', 1),
(8, 'user2', '$2y$10$D0jTe9e2zEvM7BIrCPvBpeXqkSfx1GhI/1twRaLZ7DfnHqX6SGgZW', 'user2@example.com', '0123456782', 'Address 2', 1),
(9, 'user3', '$2y$10$TScOEql3UvUfzUEXrclmB.tvD3nVtLBN2aL0TmWQYr1PvVYJJYl5y', 'user3@example.com', '0123456783', 'Address 3', 1),
(10, 'user4', '$2y$10$P2UE7KiONH3Ggz.9Hn9GP..Crk7SxqJ/kQ27fBkJRMj.PY9fTpmjq', 'user4@example.com', '0123456784', 'Address 4', 1),
(11, 'user5', '$2y$10$LVY2huOvFZlpeAFESn7R4eA0r2nWHClWnCKrPCgkN99ZB0Bvdyff2', 'user5@example.com', '0123456785', 'Address 5', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`cart_detail_id`),
  ADD KEY `idx_cart_id` (`cart_id`),
  ADD KEY `idx_packaging_option_id` (`packaging_option_id`);

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
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `cart_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `packaging_option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=626;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `fk_cart_detail_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_detail_packaging` FOREIGN KEY (`packaging_option_id`) REFERENCES `packaging_options` (`packaging_option_id`) ON DELETE SET NULL;

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
