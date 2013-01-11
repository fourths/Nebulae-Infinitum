-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 11, 2013 at 01:13 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `creations`
--

INSERT INTO `creations` (`id`, `name`, `type`, `ownerid`, `created`, `modified`, `hidden`, `filetype`, `filename`, `descr`, `advisory`, `views`, `favourites`, `rating`, `license`) VALUES
(1, 'Supercollider', 'artwork', 1, '2012-06-23 07:00:00', '2013-01-11 21:08:40', 'approved', 'png', '1.png', 'Bork [url=http://scratch.mit.edu/]bork[/url] bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork.', 'minor gore, scary trees, and other things', 1, 0, 0.0, 'cc-0');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `registered`, `rank`, `password`, `location`, `banstatus`, `banneduntil`, `bandate`, `icon`, `about`, `age`, `gender`, `email`, `registerip`, `banreason`, `sb2player`) VALUES
(1, 'veggieman', '2012-06-21 07:00:00', 'admin', '6f5ff21c4c62c3a345a00460beca8797c8ab1c8e5e1c0bc9dce91ec2a4f64583', 'Here', 'unbanned', '2012-09-21', '2012-09-21', '1.png', 'This is where I put words about myself, which is what these are. What''s the problem &quot;? &gt;', 15, 'm', 'aquariusbyz@gmail.com', '', '', 'js');

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
(1, '::1', '2013-01-11 21:13:14');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
