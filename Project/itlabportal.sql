-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Час створення: Квт 01 2025 р., 17:52
-- Версія сервера: 8.2.0
-- Версія PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `itlabportal`
--

-- --------------------------------------------------------

--
-- Структура таблиці `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `text` text COLLATE utf8mb4_general_ci NOT NULL,
  `publicationDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `text`, `publicationDate`) VALUES
(1, 'Важливі зміни!', 'Ознаймтесь зі змінами у компанії!! Детальніше -> ///....///', '2025-01-30 15:01:24'),
(3, 'Оголошення №3', 'Деякий опис..........................', '2025-01-30 15:21:29'),
(5, 'Дуже цікаве оновлення!', 'Радимо ознайомитись з оновленням за посиланням: \r\nhttps://itlab-studio.com/ua/', '2025-01-30 15:21:44'),
(6, 'Новий проект!', 'Раді представити Вам наш новий продукт! Детальніше -> //...//', '2025-01-30 15:45:31'),
(7, 'Новий член команди!', 'Раді представити вам нашого нового працівника! -> <3', '2025-01-30 15:45:31'),
(8, 'Новина №8 ', 'Дуже цікава стаття - ознайомтесь!', '2025-01-30 18:14:29'),
(35, 'Історична новина', 'Ознайомтесь з інформацією!', '2025-02-05 20:45:33'),
(13, '', '', '2025-01-30 18:24:14'),
(14, '123', '\'123', '2025-01-30 18:24:45'),
(15, '', '\'123', '2025-01-30 18:27:39'),
(16, '', '\'123', '2025-01-30 18:34:56'),
(17, '', '\'123', '2025-01-30 18:35:46'),
(18, '', '', '2025-01-30 18:35:50'),
(19, '1234', '2423545', '2025-01-30 18:42:33'),
(20, '1234', '2423545', '2025-01-30 18:43:43'),
(21, '1234', '5676', '2025-01-30 18:46:04'),
(22, '1234', '5676', '2025-01-30 18:47:29'),
(23, '1234', '5676', '2025-01-30 18:49:56'),
(24, '1234', '5676', '2025-01-30 18:50:15'),
(25, '1234', '5676', '2025-01-30 18:50:23'),
(26, '1234', '5676', '2025-01-30 18:52:31'),
(27, '1234', '5676', '2025-01-30 18:53:42'),
(28, '1234', '5676', '2025-01-30 18:57:41'),
(29, 'qw5e235', '352345', '2025-01-30 19:00:28'),
(30, 'Novina', '122434\r\n235\r\n3\r\n46\r\n5\r\n65465467\r\n678568\r\n', '2025-01-30 19:17:16'),
(31, '124', '1245efhdh', '2025-01-30 19:18:53'),
(32, 'BMW 340i ', '1234\r\n123\r\n12342345346536\r\n456\r\n45747467', '2025-02-01 11:18:38'),
(33, 'BMW 3 series', '12414234\r\n25\r\n\r\n346\r\n53\r\n56457647ghj\r\nghjkgh\r\nj\r\nghj\r\ngh\r\nj\r\nghj\r\ng\r\nhj', '2025-02-01 11:22:34'),
(34, '1234', '1234wdfhgfgjhfgjfgj', '2024-02-01 11:25:15');

-- --------------------------------------------------------

--
-- Структура таблиці `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `announcement_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_announcement` (`announcement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `files`
--

INSERT INTO `files` (`id`, `announcement_id`, `image_path`) VALUES
(1, 8, 'src/database/announcements/announcement8/'),
(2, 9, 'src/database/announcements/announcement9/'),
(3, 10, 'src/database/announcements/announcement10/'),
(4, 11, 'src/database/announcements/announcement11/'),
(5, 12, 'src/database/announcements/announcement12/'),
(6, 13, 'src/database/announcements/announcement13/'),
(7, 14, 'src/database/announcements/announcement14/'),
(8, 15, 'src/database/announcements/announcement15/'),
(9, 16, 'src/database/announcements/announcement16/'),
(10, 17, 'src/database/announcements/announcement17/'),
(11, 18, 'src/database/announcements/announcement18/'),
(12, 19, 'src/database/announcements/announcement19/'),
(13, 20, 'src/database/announcements/announcement20/'),
(14, 21, 'src/database/announcements/announcement21/'),
(15, 22, 'src/database/announcements/announcement22/'),
(16, 23, 'src/database/announcements/announcement23/'),
(17, 24, 'src/database/announcements/announcement24/'),
(18, 25, 'src/database/announcements/announcement25/'),
(19, 26, 'src/database/announcements/announcement26/'),
(20, 27, 'src/database/announcements/announcement27/'),
(21, 28, 'src/database/announcements/announcement28/'),
(22, 29, 'src/database/announcements/announcement29/'),
(23, 30, 'src/database/announcements/announcement30/'),
(24, 31, 'src/database/announcements/announcement31/'),
(25, 32, 'src/database/announcements/announcement32/'),
(26, 33, 'src/database/announcements/announcement33/'),
(27, 34, 'src/database/announcements/announcement34/'),
(28, 1, 'src/database/announcements/announcement1/'),
(29, 4, 'src/database/announcements/announcement4/'),
(30, 3, 'src/database/announcements/announcement3/'),
(31, 6, 'src/database/announcements/announcement6/'),
(32, 7, 'src/database/announcements/announcement7/'),
(33, 35, 'src/database/announcements/announcement35/');

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `first_name`, `last_name`, `email`, `role`, `image_path`) VALUES
(3, 'admin', '$2y$10$15f.SyRkDp3ud8VH.sbQaOJ9LXrvm43QQFTllAipK1wB5N/eLvvHG', 'Mykola', 'Trofimchuk', 'kartrofim@gmail.com', 'admin', 'src/database/users/user3/1-BMW-3-Series.jpg'),
(4, 'user', '$2y$10$029ZV9wkdG4KatXs8yOFPuM3f/w2Hr6c6y66blX2hsrpjiHOtWeQO', 'User', 'Userskiy', 'user123@mail.com', 'user', 'src/database/users/user4/Без названия.jpg');

-- --------------------------------------------------------

--
-- Структура таблиці `user_like_announcements`
--

DROP TABLE IF EXISTS `user_like_announcements`;
CREATE TABLE IF NOT EXISTS `user_like_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `announcement_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_announcement_id` (`announcement_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `user_like_announcements`
--

INSERT INTO `user_like_announcements` (`id`, `announcement_id`, `user_id`) VALUES
(8, 31, 3),
(7, 1, 3),
(15, 1, 4),
(17, 2, 4),
(19, 34, 3),
(20, 32, 3),
(21, 7, 4),
(24, 3, 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
