-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 06:19 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Angular_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `companyName` varchar(150) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `name`, `email`, `companyName`, `designation`, `avatar`, `dob`) VALUES
(4, 'Ravi Mule', 'ravi.mule@wwindia.com', 'NeoSOFT', 'Developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1991-05-17'),
(6, 'Sagar', 'sagar@gmail.com', 'NeoSOFT', 'Developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1993-06-17'),
(7, 'Swapnil', 'swapnil@gmail.com', 'NeoSOFT', 'Developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1992-06-26'),
(8, 'Kanpurne', 'dknapurne@gmail.com', 'kpit', 'Andriod developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1991-05-02'),
(9, 'Sandip', 'sandip@gmail.com', 'stw', 'Developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1991-06-21'),
(11, 'Mayuri', 'mayuri@gmail.com', 'NeoSOFT', 'Magento Developer', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1991-04-16'),
(13, 'Sachin', 'sachin@gmail.com', 'stw', 'tester', 'global-mobile-phone-sales-forecasted-by-88-countries.jpg', '1991-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `reg_user`
--

CREATE TABLE IF NOT EXISTS `reg_user` (
  `reg_user_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`reg_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `reg_user`
--

INSERT INTO `reg_user` (`reg_user_id`, `name`, `email`, `password`, `date`) VALUES
(1, 'test', 'test@test.com', 'test', '0000-00-00'),
(2, 'test1', 'test1@test.com', 'test', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE IF NOT EXISTS `user_activity` (
  `user_id` int(100) NOT NULL AUTO_INCREMENT,
  `emp_id` int(200) NOT NULL,
  `reg_user_id` int(200) NOT NULL,
  `flag` int(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`user_id`, `emp_id`, `reg_user_id`, `flag`) VALUES
(4, 11, 1, 1),
(5, 13, 1, 1),
(7, 9, 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
