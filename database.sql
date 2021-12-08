-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 08 Ara 2021, 20:50:33
-- Sunucu sürümü: 10.3.25-MariaDB
-- PHP Sürümü: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bots`
--

CREATE TABLE `bots` (
  `orderID` int(20) UNSIGNED NOT NULL,
  `id` varchar(50) NOT NULL,
  `library` varchar(200) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `shortdesc` varchar(200) NOT NULL,
  `detaileddesc` varchar(2000) NOT NULL,
  `tags` varchar(50) NOT NULL,
  `website` varchar(200) NOT NULL,
  `supserver` varchar(200) NOT NULL,
  `invite` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `vote` varchar(50) NOT NULL,
  `owner` varchar(500) NOT NULL,
  `certificate` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `bots`
--

INSERT INTO `bots` (`orderID`, `id`, `library`, `prefix`, `shortdesc`, `detaileddesc`, `tags`, `website`, `supserver`, `invite`, `name`, `vote`, `owner`, `certificate`) VALUES
(1, '702423641136300032', 'discord.js', '+', 'A basic Discord bot for moderation to servers.', 'A basic Discord bot for moderation to servers. Includes a lot of benefits such as music system, moderation commands etc.', '', '', '', 'https://discord.com/oauth2/authorize?client_id=702423641136300032&amp;amp;amp;amp;scope=bot&amp;amp;amp;amp;permissions=8', 'Modly', '42', '672486414985723907', ''),
(2, '718880529310679104', 'discord.php', '!', 'Uptime System for every single bot to stay active.', 'Uptime System for every single bot to stay active. Add your bot to our system then just sit back and chill out.', 'other', '', '', 'https://discord.com/oauth2/authorize?client_id=718880529310679104&amp;scope=bot&amp;permissions=8', 'Uptime System ', '0', '672486414985723907', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`orderID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bots`
--
ALTER TABLE `bots`
  MODIFY `orderID` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
