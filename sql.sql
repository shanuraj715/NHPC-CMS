-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2020 at 06:11 PM
-- Server version: 5.6.45
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techfact_db_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(6) NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `_default` int(1) NOT NULL DEFAULT '0' COMMENT 'default can not modify or delete'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_title`, `_default`) VALUES
(1, 'Uncategorized', 1),
(3, 'new Category', 0),
(49, 'New Category 3', 0),
(51, 'hello', 0),
(52, 'New Category', 0);

-- --------------------------------------------------------

--
-- Table structure for table `navigation`
--

CREATE TABLE `navigation` (
  `id` int(6) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'title of btn',
  `url` varchar(255) NOT NULL COMMENT 'url of the link',
  `parent` int(3) NOT NULL DEFAULT '1' COMMENT 'is this parent 1=yes 0=No',
  `child` int(3) NOT NULL DEFAULT '0' COMMENT 'if its child then 1=has child 0=no child',
  `parent_id` int(3) NOT NULL DEFAULT '0' COMMENT 'if child define its parent id',
  `list_order` int(3) NOT NULL DEFAULT '999'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `navigation`
--

INSERT INTO `navigation` (`id`, `title`, `url`, `parent`, `child`, `parent_id`, `list_order`) VALUES
(4, 'Service & Initiative', 'xyz.php', 1, 0, 0, 1),
(978, 'Projects', 'post/1247', 1, 1, 0, 999),
(991, 'PUBG Tournaments', 'http://mypubg.techfacts007.in/tournament/', 0, 1, 978, 999),
(990, 'PUBG Site', 'http://mypubg.techfacts007.in', 0, 1, 978, 999),
(992, 'Contentus', 'http://minor.techfacts007.in', 0, 1, 978, 999);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(4) NOT NULL COMMENT 'post unique id',
  `title` varchar(255) NOT NULL COMMENT 'post title',
  `author` varchar(255) NOT NULL COMMENT 'post published by',
  `category` varchar(255) NOT NULL COMMENT 'post category',
  `status` varchar(255) NOT NULL DEFAULT 'draft' COMMENT 'draft, trash, published',
  `post_image` varchar(255) NOT NULL COMMENT 'post main image',
  `tags` text NOT NULL COMMENT 'post tags',
  `post_date` date DEFAULT NULL,
  `content` longtext NOT NULL COMMENT 'post data'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `author`, `category`, `status`, `post_image`, `tags`, `post_date`, `content`) VALUES
(1236, 'World. This is a dummy post, edited by shanuraj715', 'admin1234', '1', 'published', 'logo.png', 'private job, business, aeroplane', '2019-07-24', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(6) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `mobile` bigint(20) NOT NULL DEFAULT '0',
  `gender` varchar(32) NOT NULL DEFAULT '',
  `age` int(3) NOT NULL DEFAULT '0',
  `role` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'otp_pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `fname`, `lname`, `username`, `password`, `image`, `email`, `mobile`, `gender`, `age`, `role`, `status`) VALUES
(123456, 'Admin', '', 'admin1234', '14xeQwDMdKo/A', 'comment-female-user.png', 'some_email@domain.com', 9876543210, 'female', 19, 'admin', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users_otp`
--

CREATE TABLE `users_otp` (
  `id` int(6) NOT NULL,
  `username` varchar(32) NOT NULL,
  `otp` int(6) NOT NULL,
  `otp_date` varchar(32) NOT NULL,
  `authentication_key` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `users_otp`
--
ALTER TABLE `users_otp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=993;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'post unique id', AUTO_INCREMENT=1248;

--
-- AUTO_INCREMENT for table `users_otp`
--
ALTER TABLE `users_otp`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
