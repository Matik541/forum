in main dir add file `server.php` with this information:
```php
<?php
$forumName = "name";
$forumDescription = "description";
$mainDir = "/yourDir";

$server = "host";
$user = "";
$password = "";
$basePath = "yourBase";

$hash = "HashAlg"; // eg. sha256

$mainHref = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."$mainDir/index.php";

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
} 
catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}

```
and in MySQL use:
```sql
-- 
-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
-- 

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `friends` (
  `user_id_1` int(11) NOT NULL,
  `user_id_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `author` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `rot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `password` text COLLATE utf8_polish_ci NOT NULL,
  `nick` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `picture` text COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `friends`
  ADD PRIMARY KEY (`user_id_1`,`user_id_2`),
  ADD KEY `user_id_2` (`user_id_2`);
  
ALTER TABLE `likes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `post-like` (`post_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post-post` (`rot`),
  ADD KEY `user-post` (`author`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id_1`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`user_id_2`) REFERENCES `users` (`id`);

ALTER TABLE `posts`
  ADD CONSTRAINT `post-post` FOREIGN KEY (`rot`) REFERENCES `posts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user-post` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```
