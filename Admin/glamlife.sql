-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 07:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glamlife`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(11) NOT NULL,
  `Category_Name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Category_Name`) VALUES
(1, 'HairCare'),
(2, 'BodyCare'),
(3, 'SkinCare'),
(4, 'Makeup');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `Discount_ID` int(11) NOT NULL,
  `Discount_Percentage` float NOT NULL,
  `Product_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Order_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_ID` int(11) NOT NULL,
  `Product_Name` varchar(300) NOT NULL,
  `Product_Description` varchar(300) NOT NULL,
  `Product_Price` float NOT NULL,
  `Product_Image` varchar(300) NOT NULL,
  `C_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_ID`, `Product_Name`, `Product_Description`, `Product_Price`, `Product_Image`, `C_ID`) VALUES
(1, 'Loreal everpure\r\n', 'EverPure Simply Clean with luxurious lather and intense conditioning that envelops hair in nourishment. Formulated without synthetic fragrance, sulfates, parabens, DMDM hydantoin, added phthalates, dye, and gluten ingredients.\r\n', 900, 'https://dulcealcance.com/cdn/shop/files/lorealeverpure.jpg?v=1698263350', 1),
(2, 'Hyaluron Plump Serum\r\n\r\n', 'Plump Moisture Plump Serum daily leave-in delivers a boost of moisture, replumping and reshaping dry, dehydrated hair for up to 72 hours without weigh down', 1000, 'https://klipshop.co.uk/80677-large_default/l-oreal-paris-elvital-hyaluron-plump-moisture-plump-serum.jpg', 1),
(3, 'Hyaluron Hydrating Conditioner\r\n\r\n', '72H Moisture - Hyaluron + Plump Hydrating Conditioner instantly detangles, moisturizes, and replumps dry hair for up to 72 hours without weigh down', 600, 'https://i5.walmartimages.com/seo/L-Oreal-Paris-Elvive-Hyaluron-Plump-72H-Moisturizing-Conditioner-Hyaluronic-Acid-All-Hair-Types-13-5-fl-oz_24b5b1c3-487d-486b-89b4-0957e2b30752.32ce470cf7ef13c63f66f1a01eab4af3.jpeg', 1),
(4, 'Loreal Sulfate Conditioner', 'Nourishing conditioner, suitable for color-treated hair. For renewed softness and shine. With rosemary essential oil.\r\n', 700, 'https://target.scene7.com/is/image/Target/GUEST_4440e3a6-b8f5-41a5-b598-e4c488a4b279?wid=488&hei=488&fmt=pjpeg', 1),
(5, 'Loreal glossing Shampoo', 'Glossing Shampoo, Moisturizing Conditioner, and Nourishing Hair Mask system instantly boosts hair shine by intensely smoothing hair fibers to beautifully reflect light', 450, 'https://m.media-amazon.com/images/I/71kP8cecG6L.jpg', 1),
(6, 'OSEA Body Scrub', 'Mineral-rich salts from around the world, including Pink Himalayan salt, effectively buffs away roughness. A luxurious exfoliating body scrub for those who seek the best in personal care products.', 650, 'https://m.media-amazon.com/images/I/51XyDIkNd0L.jpg', 2),
(7, 'NIVEA Shea Nourish Body Lotion\r\n', 'NIVEA lotion is enriched with Shea Butter and Deep Nourishing Serum and locks in moisture giving noticeably smoother skin for 48 hours', 750, 'https://johnstoneidapharmacy.ca/cdn/shop/products/6000198521806_c7b13e73-354b-43b6-a310-3251c26f9ece_2048x.jpg?v=1621217966', 2),
(8, 'OSEA Undaria Algae Body Lotion', 'Ultra-Hydrating & Lightweight Body Lotion - Firms Skin in 4 Hours - Packed with Undaria Seaweed & Hyaluronic Acid - for Dry, Normal, & Combination Skin\r\n', 700, 'https://m.media-amazon.com/images/I/51QgGB64aEL.jpg', 2),
(9, 'Neutrogena Body Oil', 'Fragrance-Free Light Sesame Oil Formula, Dry Skin Moisturizer and Hydrating Body Massage Oil for Radiant and Healthy Glow, Nourishing After Shower and Bath Oil', 750, 'https://m.media-amazon.com/images/I/61w8hSNv+4L.jpg', 2),
(10, 'Dove Body Wash\r\n', 'Body wash Pump Deep Moisture For Dry Skin Moisturizing Skin Cleanser with 24hr Renewing MicroMoisture Nourishes The Driest Skin', 600, 'https://i5.walmartimages.com/asr/0505a836-5626-4559-a469-b67f8850f2c1.99a6bcdeb11417bd0d867f059fd7aca5.jpeg', 2),
(11, ' HYSÉAC Cleansing Gel\r\n\r\n', 'HYSÉAC Cleansing Gel eliminates impurities and excess sebum, perfectly respecting the epidermis. High rinsability, light foaming action in contact with water and a delicate fragrance characteristic of the entire Hyséac range', 650, 'https://drogopharma.com/wp-content/uploads/2023/09/3661434006098-600x600.jpg', 3),
(12, 'DR Rashel Peach lip balm', 'This lip balm contains natural peach ingredients, delights your lips with its delicious peach aroma, a fabulous shimmer and soft glossy color.', 150, 'https://m.media-amazon.com/images/S/aplus-media/sota/b8b83ccf-25b2-407e-80b8-2155b44d61eb.__CR0,0,300,300_PT0_SX300_V1___.jpg', 3),
(13, 'Ruby nutrition hydrogel eye patch', 'Hydrating eye mask with ruby extract\r\nMoisturizes, nourishes and freshens the skin \r\nTightens the skin and fights wrinkles.', 450, 'https://media.takealot.com/covers_images/6800debc7b1a439b928bb8ba58198de5/s-pdpxl.file', 3),
(14, 'Estelin facial lift serum', 'Firm anti-aging ,repair skin damage, reduce dry lines, promote cell revitalization and increase skin elasticity. The skin is full and firm', 550, 'https://images-eu.ssl-images-amazon.com/images/I/71KZSfJobwL._AC_UL600_SR600,600_.jpg', 3),
(15, 'CeraVe Moisturizing Cream', 'CeraVe Moisturizing Cream is a rich, non-greasy, fast-absorbing moisturizer with three essential ceramides that lock in skin\'s moisture and help maintain the skin\'s protective barrier.', 650, 'https://m.media-amazon.com/images/I/61UeSIpV2XL.jpg', 3),
(16, 'SHEGLAM Volume & Length Mascara\r\n\r\n', 'SHEGLAM’s All-in-One Mascara gives you freedom and versatility. Our buildable, long lasting, waterproof, clump-free formula can be used with either built-in brush to create a custom effect that will transform your eyelashes.\r\n', 400, 'https://m.media-amazon.com/images/I/61JmK3aai-L.jpg', 4),
(17, 'Might Cinema Lip Gloss', 'Might Cinema Lip Gloss Matte Velvety Long Wear Colored-2 Color-Shade.', 85, 'https://m.media-amazon.com/images/I/61CbHfqK3WL.jpg', 4),
(18, 'Might Cinema Blusher', 'Might cinema liquid waterproof pink shade blusher.', 200, 'https://eg.jumia.is/unsafe/fit-in/500x500/filters:fill(white)/product/99/794276/1.jpg?9133', 4),
(19, 'L\'Oreal Paris Matte Foundation', 'L\'Oreal Paris Infallible 24 hour Freshwear Foundation for a full coverage, no compromise base for the ultimate resistance.', 800, 'https://m.media-amazon.com/images/I/71iIK9EvGHL.jpg', 4),
(20, 'Maybelline eye concealer', 'Maybelline eraser instant age rewind eye concealer warm light.\r\n\r\n', 650, 'https://eve-sa.com/wp-content/uploads/2020/02/041554259247.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `Review_ID` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

CREATE TABLE `shoppingcart` (
  `Cart_ID` int(11) NOT NULL,
  `Cart_Quantity` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `User_Fname` varchar(300) NOT NULL,
  `User_Lname` varchar(300) NOT NULL,
  `User_Email` varchar(300) NOT NULL,
  `User_Password` varchar(300) NOT NULL,
  `User_Type` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`Discount_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `C_ID` (`C_ID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`Review_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `Discount_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `Review_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  MODIFY `Cart_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`C_ID`) REFERENCES `category` (`Category_ID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  ADD CONSTRAINT `shoppingcart_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
