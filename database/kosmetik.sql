SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `kosmetik`
--
CREATE DATABASE IF NOT EXISTS `kosmetik` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kosmetik`;

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(15) NOT NULL auto_increment,
  `product_code` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_desc` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `units` int(5) NOT NULL,
  `total` int(15) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=12 ;


--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL auto_increment,
  `product_code` varchar(60) NOT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_desc` tinytext NOT NULL,
  `product_img_name` varchar(60) NOT NULL,
  `qty` int(5) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_desc`, `product_img_name`, `qty`, `price`) VALUES
(1, 'KOS1', 'Baume', 'Notre baume nourrissant est idéal pour apaiser et hydrater les peaux sèches et sensibles. Enrichi en ingrédients naturels.', 'baume.jpg', 26, 5000.00),
(2, 'KOS2', 'Lotion', 'Cette lotion légère et hydratante est parfaite pour un usage quotidien.', 'lotion.jpg', 7, 200.00),
(3, 'KOS3', 'Parfum', 'Découvrez notre parfum envoûtant, un mélange harmonieux de notes florales et boisées.', 'parfum.jpg', 34, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pin` int(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL default 'user',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

-- Comptes de démonstration (mots de passe hachés avec bcrypt).
-- Mot de passe des deux comptes : ChangeMe123!  -> à modifier dès la première connexion.
INSERT INTO `users` (`id`, `fname`, `lname`, `address`, `city`, `pin`, `email`, `password`, `type`) VALUES
(1, 'Demo', 'Client', 'Infinite Loop', 'Cotonou', 95014, 'client@example.com', '$2y$10$DYn1rCpGngu7kMCThiQ/zugYevoaOJTCIuUXttoObRAfx4fOnSgpi', 'user'),
(2, 'Admin', 'Webmaster', 'Internet', 'Cotonou', 101010, 'admin@example.com', '$2y$10$DYn1rCpGngu7kMCThiQ/zugYevoaOJTCIuUXttoObRAfx4fOnSgpi', 'admin');

