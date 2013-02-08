-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2013 at 10:16 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mediasite`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `userid` int(25) NOT NULL,
  `creationid` int(25) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `status` enum('shown','censored','approved') NOT NULL DEFAULT 'shown',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`userid`, `creationid`, `comment`, `timestamp`, `id`, `status`) VALUES
(1, 1, 'test comment ', '2013-01-31 20:32:54', 1, 'shown'),
(2, 1, '[quote name="veggieman" date="01/31/2013" url="creation.php?id=1#1"]test comment[/quote]\r\ntest comment 2 ', '2013-01-31 20:34:32', 2, 'shown'),
(2, 1, '[quote name="veggieman" date="02/06/2013" url="creation.php?id=1#3"]This is a wonderful comment.[/quote]\r\nVerily! ', '2013-02-06 21:27:49', 4, 'shown');

-- --------------------------------------------------------

--
-- Table structure for table `creations`
--

CREATE TABLE IF NOT EXISTS `creations` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('writing','flash','scratch','artwork','audio') NOT NULL,
  `ownerid` int(25) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hidden` enum('no','censored','flagged','approved','byowner','deleted') NOT NULL DEFAULT 'no',
  `filetype` varchar(7) NOT NULL,
  `filename` varchar(25) NOT NULL,
  `descr` text,
  `advisory` text,
  `views` int(11) NOT NULL DEFAULT '0',
  `favourites` int(11) NOT NULL DEFAULT '0',
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  `license` enum('copyright','cc-0','cc-by','cc-by-nd','cc-by-sa','cc-by-nc','cc-by-nc-nd','cc-by-nc-sa','mit','gpl','bsd') NOT NULL DEFAULT 'copyright',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `creations`
--

INSERT INTO `creations` (`id`, `name`, `type`, `ownerid`, `created`, `modified`, `hidden`, `filetype`, `filename`, `descr`, `advisory`, `views`, `favourites`, `rating`, `license`) VALUES
(1, 'Supercollider', 'artwork', 1, '2012-06-23 07:00:00', '2013-01-31 20:32:47', 'approved', 'png', '1.png', 'Bork [url=http://scratch.mit.edu/]bork[/url] bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork.', 'minor gore, scary trees, and other things', 1, 0, 0.0, 'cc-0'),
(2, 'g', 'artwork', 1, '2013-02-04 20:45:48', '2013-02-04 20:45:49', 'no', 'jpg', '2.jpg', 'g', NULL, 1, 0, 0.0, 'copyright'),
(3, 'txt', 'writing', 1, '2013-02-04 20:46:35', '2013-02-04 20:46:36', 'no', 'txt', '3.txt', 'ffddsrf', NULL, 1, 0, 0.0, 'copyright'),
(4, 'brr', 'writing', 1, '2013-02-04 20:47:08', '2013-02-04 20:47:09', 'no', 'txt', '4.txt', NULL, NULL, 1, 0, 0.0, 'copyright'),
(5, 'dsfdf', 'flash', 1, '2013-02-04 21:03:27', '2013-02-04 21:03:28', 'no', 'swf', '5.swf', 'dsfdf', NULL, 1, 0, 0.0, 'copyright'),
(6, 'fdsf', 'artwork', 1, '2013-02-05 20:29:51', '2013-02-05 20:44:15', 'no', 'jpg', '6.jpg', 'dfsdf', NULL, 1, 0, 0.0, 'copyright'),
(7, 'fdsf', 'artwork', 1, '2013-02-05 20:29:53', '2013-02-05 20:29:54', 'no', 'jpg', '7.jpg', 'dfsdf', NULL, 1, 0, 0.0, 'copyright'),
(8, 'gfjghkl', 'artwork', 1, '2013-02-05 20:30:05', '2013-02-05 20:30:07', 'no', 'jpg', '8.jpg', 'fgfdgf', NULL, 1, 0, 0.0, 'copyright'),
(9, 'ddddd', 'artwork', 1, '2013-02-05 20:30:20', '2013-02-05 20:30:21', 'no', 'jpg', '9.jpg', 'cvfvfdgf', NULL, 1, 0, 0.0, 'copyright'),
(10, 'ddfsdret4', 'artwork', 1, '2013-02-05 20:30:46', '2013-02-05 20:30:48', 'no', 'jpg', '10.jpg', 'reg', NULL, 1, 0, 0.0, 'copyright'),
(11, 'jellyfish', 'artwork', 1, '2013-02-05 20:33:39', '2013-02-05 20:33:40', 'no', 'jpg', '11.jpg', NULL, NULL, 1, 0, 0.0, 'copyright'),
(12, 'brr', 'audio', 1, '2013-02-05 21:08:57', '2013-02-05 21:08:59', 'no', 'mp3', '12.mp3', 'brr', NULL, 1, 0, 0.0, 'copyright');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE IF NOT EXISTS `favourites` (
  `creationid` int(25) NOT NULL,
  `userid` int(25) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE IF NOT EXISTS `flags` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` int(50) NOT NULL,
  `parentid` int(50) NOT NULL,
  `content` text NOT NULL,
  `type` enum('creation','comment') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `recipientid` int(50) NOT NULL,
  `senderid` int(50) NOT NULL,
  `viewed` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `type` enum('notification','pm','admin') NOT NULL,
  `admintype` enum('specific','generic') DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `recipientid`, `senderid`, `viewed`, `timestamp`, `message`, `type`, `admintype`) VALUES
(1, 1, 2, 1, '2013-01-31 20:34:32', 'You have received a new comment by [url=user.php?id=2]testaccount[/url] on your creation [url=creation.php?id=1#2]Supercollider[/url]!', 'notification', NULL),
(2, 2, 1, 2, '2013-01-31 20:44:25', 'test ', 'pm', NULL),
(3, 1, 1, 1, '2013-01-31 21:10:56', '[quote name="veggieman" date="Jan 31, 2013" url="user.php?id=1"][/quote]\r\nk', 'pm', NULL),
(4, 1, 2, 1, '2013-01-31 21:12:01', 'dsf', 'pm', NULL),
(5, 1, 2, 1, '2013-02-01 20:47:24', 'so \r\n\r\nmany\r\n\r\nlines', 'pm', NULL),
(6, 2, 1, 1, '2013-02-01 20:47:41', '[quote name="testaccount" date="Feb 01, 2013" url="user.php?id=2"]so\r\n\r\nmany\r\n\r\nlines[/quote]\r\n:O', 'pm', NULL),
(7, 1, 2, 1, '2013-02-06 21:27:49', 'You have received a new comment by [url=user.php?id=2]testaccount[/url] on your creation [url=creation.php?id=1#4]Supercollider[/url]!', 'notification', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `creationid` int(25) NOT NULL,
  `userid` int(25) NOT NULL,
  `rating` int(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rank` enum('user','mod','admin') NOT NULL DEFAULT 'user',
  `password` varchar(64) NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  `banstatus` enum('unbanned','banned','deleted') NOT NULL DEFAULT 'unbanned',
  `banneduntil` date DEFAULT NULL,
  `bandate` date DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `about` text,
  `age` int(3) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `registerip` varchar(20) NOT NULL,
  `banreason` text,
  `sb2player` enum('flash','js') NOT NULL DEFAULT 'flash',
  `notifications` enum('all','noreplies','nocomments','none') NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `registered`, `rank`, `password`, `location`, `banstatus`, `banneduntil`, `bandate`, `icon`, `about`, `age`, `gender`, `email`, `registerip`, `banreason`, `sb2player`, `notifications`) VALUES
(1, 'veggieman', '2012-06-21 07:00:00', 'admin', '6f5ff21c4c62c3a345a00460beca8797c8ab1c8e5e1c0bc9dce91ec2a4f64583', 'Here', 'unbanned', '2038-01-19', '2013-01-14', '1.png', 'This is where many rows about oneself may go, if one were to choose to do that.\r\n\r\n[b]BBCode now works here[/b]. [i]Its overuse is discouraged, [url=http://en.wiktionary.org/wiki/for_real]however[/url][/i].', 15, 'm', 'aquariusbyz@gmail.com', '', '', 'js', 'noreplies'),
(2, 'testaccount', '2013-01-31 20:33:53', 'user', '6f5ff21c4c62c3a345a00460beca8797c8ab1c8e5e1c0bc9dce91ec2a4f64583', NULL, 'unbanned', NULL, NULL, NULL, NULL, NULL, NULL, 'a@a.com', '::1', NULL, 'flash', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE IF NOT EXISTS `views` (
  `creationid` int(25) NOT NULL,
  `viewip` varchar(14) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `views`
--

INSERT INTO `views` (`creationid`, `viewip`, `timestamp`) VALUES
(1, '::1', '2013-01-31 20:32:47'),
(2, '::1', '2013-02-04 20:45:49'),
(3, '::1', '2013-02-04 20:46:36'),
(4, '::1', '2013-02-04 20:47:09'),
(5, '::1', '2013-02-04 21:03:28'),
(7, '::1', '2013-02-05 20:29:54'),
(8, '::1', '2013-02-05 20:30:07'),
(9, '::1', '2013-02-05 20:30:21'),
(10, '::1', '2013-02-05 20:30:48'),
(11, '::1', '2013-02-05 20:33:40'),
(6, '::1', '2013-02-05 20:44:15'),
(12, '::1', '2013-02-05 21:08:59');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
