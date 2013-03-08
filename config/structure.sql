-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2013 at 10:12 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`userid`, `creationid`, `comment`, `timestamp`, `id`, `status`) VALUES
(1, 1, 'test comment ', '2013-01-31 20:32:54', 1, 'shown'),
(2, 1, '[quote name="veggieman" date="01/31/2013" url="creation.php?id=1#1"]test comment[/quote]\r\ntest comment 2 ', '2013-01-31 20:34:32', 2, 'shown'),
(2, 1, '[quote name="veggieman" date="02/06/2013" url="creation.php?id=1#3"]This is a wonderful comment.[/quote]\r\nVerily! ', '2013-02-06 21:27:49', 4, 'shown'),
(1, 1, 'notification setting test ', '2013-02-11 20:40:01', 5, 'shown'),
(2, 1, 'da da da ', '2013-02-11 20:40:51', 6, 'shown'),
(1, 1, 'It was vitally necessary to conceal this fact from the outside world. \r\nEmboldened by the collapse of the windmill, the human beings were \r\ninventing fresh lies about Animal Farm. Once again it was being put about \r\nthat all the animals were dying of famine and disease, and that they were \r\ncontinually fighting among themselves and had resorted to cannibalism and \r\ninfanticide. Napoleon was well aware of the bad results that might follow \r\nif the real facts of the food situation were known, and he decided to make \r\nuse of Mr. Whymper to spread a contrary impression. Hitherto the animals \r\nhad had little or no contact with Whymper on his weekly visits: now, \r\nhowever, a few selected animals, mostly sheep, were instructed to remark \r\ncasually in his hearing that rations had been increased. In addition, \r\nNapoleon ordered the almost empty bins in the store-shed to be filled \r\nnearly to the brim with sand, which was then covered up with what remained \r\nof the grain and meal. On some suitable pretext Whymper was led through \r\nthe store-shed and allowed to catch a glimpse of the bins. He was \r\ndeceived, and continued to report to the outside world that there was no \r\nfood shortage on Animal Farm. \r\n\r\nNevertheless, towards the end of January it became obvious that it would \r\nbe necessary to procure some more grain from somewhere. In these days \r\nNapoleon rarely appeared in public, but spent all his time in the \r\nfarmhouse, which was guarded at each door by fierce-looking dogs. When he \r\ndid emerge, it was in a ceremonial manner, with an escort of six dogs who \r\nclosely surrounded him and growled if anyone came too near. Frequently he \r\ndid not even appear on Sunday mornings, but issued his orders through one \r\nof the other pigs, usually Squealer. \r\n\r\nOne Sunday morning Squealer announced that the hens, who had just come in \r\nto lay again, must surrender their eggs. Napoleon had accepted, through \r\nWhymper, a contract for four hundred eggs a week. The price of these would \r\npay for enough grain and meal to keep the farm going till summer came on \r\nand conditions were easier. ', '2013-02-19 20:57:33', 7, 'shown');

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
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `creations`
--

INSERT INTO `creations` (`id`, `name`, `type`, `ownerid`, `created`, `modified`, `hidden`, `filetype`, `filename`, `descr`, `advisory`, `views`, `favourites`, `rating`, `license`) VALUES
(1, 'Supercollider', 'artwork', 1, '2012-06-23 07:00:00', '0000-00-00 00:00:00', 'no', 'jpg', '1.jpg', 'Bork [url=http://scratch.mit.edu/]bork[/url] bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork.', 'minor gore, scary trees, and other things', 1, 1, 5.0, 'cc-0'),
(2, 'g', 'artwork', 1, '2013-02-04 20:45:48', '2013-02-21 20:48:11', 'no', 'jpg', '2.jpg', '`Have some wine,'' the March Hare said in an encouraging tone.\r\n\r\nAlice looked all round the table, but there was nothing on it but tea. `I don''t see any wine,'' she remarked.\r\n\r\n`There isn''t any,'' said the March Hare.\r\n\r\n`Then it wasn''t very civil of you to offer it,'' said Alice angrily.\r\n\r\n`It wasn''t very civil of you to sit down without being invited,'' said the March Hare.\r\n\r\n`I didn''t know it was your table,'' said Alice; `it''s laid for a great many more than three.''\r\n\r\n`Your hair wants cutting,'' said the Hatter. He had been looking at Alice for some time with great curiosity, and this was his first speech.\r\n\r\n`You should learn not to make personal remarks,'' Alice said with some severity; `it''s very rude.''\r\n\r\nThe Hatter opened his eyes very wide on hearing this; but all he said was, `Why is a raven like a writing-desk?''\r\n\r\n`Come, we shall have some fun now!'' thought Alice. `I''m glad they''ve begun asking riddles.--I believe I can guess that,'' she added aloud.\r\n\r\n`Do you mean that you think you can find out the answer to it?'' said the March Hare.\r\n\r\n`Exactly so,'' said Alice.\r\n\r\n`Then you should say what you mean,'' the March Hare went on.\r\n\r\n`I do,'' Alice hastily replied; `at least--at least I mean what I say--that''s the same thing, you know.''\r\n\r\n`Not the same thing a bit!'' said the Hatter. `You might just as well say that &quot;I see what I eat&quot; is the same thing as &quot;I eat what I see&quot;!''\r\n\r\n Hatter engaging in rhetoric\r\n\r\n`You might just as well say,'' added the March Hare, `that &quot;I like what I get&quot; is the same thing as &quot;I get what I like&quot;!''\r\n\r\n`You might just as well say,'' added the Dormouse, who seemed to be talking in his sleep, `that &quot;I breathe when I sleep&quot; is the same thing as &quot;I sleep when I breathe&quot;!''\r\n\r\n`It is the same thing with you,'' said the Hatter, and here the conversation dropped, and the party sat silent for a minute, while Alice thought over all she could remember about ravens and writing-desks, which wasn''t much.\r\n\r\nThe Hatter was the first to break the silence. `What day of the month is it?'' he said, turning to Alice: he had taken his watch out of his pocket, and was looking at it uneasily, shaking it every now and then, and holding it to his ear.\r\n\r\nAlice considered a little, and then said `The fourth.''\r\n\r\n`Two days wrong!'' sighed the Hatter. `I told you butter wouldn''t suit the works!'' he added looking angrily at the March Hare.\r\n\r\n`It was the best butter,'' the March Hare meekly replied.\r\n\r\n`Yes, but some crumbs must have got in as well,'' the Hatter grumbled: `you shouldn''t have put it in with the bread-knife.''\r\n\r\nThe March Hare took the watch and looked at it gloomily: then he dipped it into his cup of tea, and looked at it again: but he could think of nothing better to say than his first remark, `It was the best butter, you know.''\r\n\r\nAlice had been looking over his shoulder with some curiosity. `What a funny watch!'' she remarked. `It tells the day of the month, and doesn''t tell what o''clock it is!''\r\n\r\n`Why should it?'' muttered the Hatter. `Does your watch tell you what year it is?''\r\n\r\n`Of course not,'' Alice replied very readily: `but that''s because it stays the same year for such a long time together.''\r\n\r\n`Which is just the case with mine,'' said the Hatter.\r\n\r\nAlice felt dreadfully puzzled. The Hatter''s remark seemed to have no sort of meaning in it, and yet it was certainly English. `I don''t quite understand you,'' she said, as politely as she could.\r\n\r\n`The Dormouse is asleep again,'' said the Hatter, and he poured a little hot tea upon its nose.\r\n\r\nThe Dormouse shook its head impatiently, and said, without opening its eyes, `Of course, of course; just what I was going to remark myself.''\r\n\r\n`Have you guessed the riddle yet?'' the Hatter said, turning to Alice again.\r\n\r\n`No, I give it up,'' Alice replied: `what''s the answer?''\r\n\r\n`I haven''t the slightest idea,'' said the Hatter.\r\n\r\n`Nor I,'' said the March Hare.\r\n\r\nAlice sighed wearily. `I think you might do something better with the time,'' she said, `than waste it in asking riddles that have no answers.''\r\n\r\n`If you knew Time as well as I do,'' said the Hatter, `you wouldn''t talk about wasting it. It''s him.''\r\n\r\n`I don''t know what you mean,'' said Alice.\r\n\r\n`Of course you don''t!'' the Hatter said, tossing his head contemptuously. `I dare say you never even spoke to Time!''\r\n\r\n`Perhaps not,'' Alice cautiously replied: `but I know I have to beat time when I learn music.''\r\n\r\n`Ah! that accounts for it,'' said the Hatter. `He won''t stand beating. Now, if you only kept on good terms with him, he''d do almost anything you liked with the clock. For instance, suppose it were nine o''clock in the morning, just time to begin lessons: you''d only have to whisper a hint to Time, and round goes the clock in a twinkling! Half-past one, time for dinner!''\r\n\r\n(`I only wish it was,'' the March Hare said to itself in a whisper.)\r\n\r\n`That would be grand, certainly,'' said Alice thoughtfully: `but then--I shouldn''t be hungry for it, you know.''\r\n\r\n`Not at first, perhaps,'' said the Hatter: `but you could keep it to half-past one as long as you liked.''\r\n\r\n`Is that the way you manage?'' Alice asked.\r\n\r\nThe Hatter shook his head mournfully. `Not I!'' he replied. `We quarrelled last March--just before he went mad, you know--'' (pointing with his tea spoon at the March Hare,) `--it was at the great concert given by the Queen of Hearts, and I had to sing\r\n\r\n            &quot;Twinkle, twinkle, little bat!\r\n            How I wonder what you''re at!&quot;\r\nYou know the song, perhaps?''\r\n`I''ve heard something like it,'' said Alice.\r\n\r\n`It goes on, you know,'' the Hatter continued, `in this way:--\r\n\r\n            &quot;Up above the world you fly,\r\n            Like a tea-tray in the sky.', 'this is a bunch of text and i don''t know why it wouldn''t be acceptable aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1, 1, 0.0, 'copyright'),
(3, 'txt', 'writing', 1, '2013-02-04 20:46:35', '2013-02-21 20:18:26', 'no', 'txt', '3.txt', 'this is a super long description full of all sorts of crazy stuff don''t you worry I''m just filling it up with words so there''s some sort of example for what I''m about to do or something I mean I guess I could just use lorem ipsum dolor sit amet or whatever but that is LESS FUN', '', 1, 0, 0.0, 'copyright'),
(4, 'brr', 'writing', 1, '2013-02-04 20:47:08', '2013-02-04 20:47:09', 'no', 'txt', '4.txt', NULL, NULL, 1, 0, 0.0, 'copyright'),
(5, 'dsfdf', 'flash', 1, '2013-02-04 21:03:27', '2013-02-04 21:03:28', 'no', 'swf', '5.swf', 'dsfdf', NULL, 1, 0, 0.0, 'copyright'),
(6, 'fdsf', 'artwork', 1, '2013-02-05 20:29:51', '2013-02-05 20:44:15', 'no', 'jpg', '6.jpg', 'dfsdf', NULL, 1, 0, 0.0, 'copyright'),
(7, 'fdsf', 'artwork', 1, '2013-02-05 20:29:53', '2013-02-05 20:29:54', 'no', 'jpg', '7.jpg', 'dfsdf', NULL, 1, 0, 0.0, 'copyright'),
(8, 'gfjghkl', 'artwork', 1, '2013-02-05 20:30:05', '2013-02-05 20:30:07', 'no', 'jpg', '8.jpg', 'fgfdgf', NULL, 1, 0, 0.0, 'copyright'),
(9, 'ddddd', 'artwork', 1, '2013-02-05 20:30:20', '2013-02-05 20:30:21', 'no', 'jpg', '9.jpg', 'cvfvfdgf', NULL, 1, 0, 0.0, 'copyright'),
(10, 'ddfsdret4', 'artwork', 1, '2013-02-05 20:30:46', '2013-02-05 20:30:48', 'no', 'jpg', '10.jpg', 'reg', NULL, 1, 0, 0.0, 'copyright'),
(11, 'jellyfish', 'artwork', 1, '2013-02-05 20:33:39', '2013-02-05 20:33:40', 'no', 'jpg', '11.jpg', NULL, NULL, 1, 0, 0.0, 'copyright'),
(12, 'brr', 'audio', 1, '2013-02-05 21:08:57', '2013-02-05 21:08:59', 'no', 'mp3', '12.mp3', 'brr', NULL, 1, 0, 0.0, 'copyright'),
(13, '35', 'scratch', 1, '2013-02-14 20:42:54', '2013-02-14 20:42:56', 'no', 'sb2', '13.sb2', 'ddd', NULL, 1, 0, 0.0, 'copyright'),
(14, 'tulips', 'artwork', 2, '2013-02-28 20:45:51', '2013-02-28 20:53:43', 'no', 'jpg', '14.jpg', 'yes', NULL, 1, 1, 0.0, 'copyright'),
(15, 'desert', 'artwork', 2, '2013-02-28 20:47:33', '2013-02-28 20:53:21', 'no', 'jpg', '15.jpg', 'dfs', NULL, 1, 1, 0.0, 'copyright');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE IF NOT EXISTS `favourites` (
  `creationid` int(25) NOT NULL,
  `userid` int(25) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`creationid`, `userid`, `timestamp`) VALUES
(1, 1, '2013-02-21 20:47:39'),
(2, 1, '2013-02-21 20:48:09'),
(15, 1, '2013-02-28 20:53:20'),
(14, 1, '2013-02-28 20:53:42');

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
  `type` enum('creation','comment','message') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `flags`
--

INSERT INTO `flags` (`id`, `timestamp`, `userid`, `parentid`, `content`, `type`) VALUES
(1, '2013-02-19 20:56:21', 1, 4, 'test test', 'message'),
(2, '2013-02-19 20:56:41', 1, 1, 'brr', 'creation'),
(3, '2013-02-19 20:56:50', 1, 4, 'dada', 'comment'),
(4, '2013-02-19 20:57:43', 1, 7, 'spam!', 'comment');

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

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`creationid`, `userid`, `rating`, `timestamp`) VALUES
(1, 1, 5, '2013-02-13 21:22:46');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `registered`, `rank`, `password`, `location`, `banstatus`, `banneduntil`, `bandate`, `icon`, `about`, `age`, `gender`, `email`, `registerip`, `banreason`, `sb2player`, `notifications`) VALUES
(1, 'veggieman', '2012-06-21 07:00:00', 'admin', '6f5ff21c4c62c3a345a00460beca8797c8ab1c8e5e1c0bc9dce91ec2a4f64583', 'Here', 'unbanned', '2038-01-19', '2013-01-14', '1.png', 'This is where many rows about oneself may go, if one were to choose to do that.\r\n\r\n[b]BBCode now works here[/b]. [i]Its overuse is discouraged, [url=http://en.wiktionary.org/wiki/for_real]however[/url].', 15, 'm', 'aquariusbyz@gmail.com', '', '', 'js', 'none'),
(2, 'testaccount', '2013-01-31 20:33:53', 'user', '6f5ff21c4c62c3a345a00460beca8797c8ab1c8e5e1c0bc9dce91ec2a4f64583', NULL, 'unbanned', NULL, NULL, NULL, NULL, NULL, NULL, 'a@a.com', '::1', NULL, 'flash', 'all'),
(3, 'testing this', '2013-02-20 21:30:45', 'user', 'e15a31e76638c4d50a5e729dfcb7351848f419f44797db76ec104856e6afb1a3', 'dsf', 'unbanned', NULL, NULL, NULL, NULL, 0, 'm', 'ok@ok.com', '::1', NULL, 'flash', 'all'),
(4, 'd aa', '2013-02-20 21:34:59', 'user', '3da9d34185ec04bebd4dfa54c1730cb7d6a6f3f2e73d5ad900794b94f6286e87', NULL, 'unbanned', NULL, NULL, NULL, NULL, NULL, NULL, 'ok@ok.com', '::1', NULL, 'flash', 'all'),
(5, 'Testspace', '2013-02-20 21:38:22', 'user', '0b71eda92beb014793a753072a2794e8c936f26b05a1c7f3da1851c9ec44a92e', NULL, 'unbanned', NULL, NULL, NULL, NULL, NULL, NULL, 'dsfsdf@fsads.com', '::1', NULL, 'flash', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE IF NOT EXISTS `versions` (
  `creationid` int(100) NOT NULL,
  `name` varchar(25) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `number` int(100) NOT NULL,
  `saved` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`creationid`, `name`, `timestamp`, `number`, `saved`) VALUES
(1, '1.0', '2012-06-23 07:00:00', 1, 1);

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
(12, '::1', '2013-02-05 21:08:59'),
(13, '::1', '2013-02-14 20:42:56'),
(14, '::1', '2013-02-28 20:45:52'),
(15, '::1', '2013-02-28 20:47:34');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
