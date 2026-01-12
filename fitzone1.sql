-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 07, 2025 at 05:38 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitzone1`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `Appointment_ID` int NOT NULL,
  `Admission_Number` int NOT NULL,
  `Class_Type` varchar(50) NOT NULL,
  `Preferred_Date` date NOT NULL,
  `Preferred_Time` time NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Appointment_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`Appointment_ID`, `Admission_Number`, `Class_Type`, `Preferred_Date`, `Preferred_Time`, `Status`, `Created_At`) VALUES
(2, 4, 'Cardio', '2025-04-19', '08:00:00', 'Completed', '2025-04-16 15:15:00'),
(0, 4, 'Yoga', '2025-04-26', '06:00:00', '', '2025-04-25 15:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `Post_ID` int NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Image` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Post_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`Post_ID`, `Title`, `Content`, `Author`, `Category`, `Image`, `Created_At`) VALUES
(4, '💪 Workout Routine: 20-Minute Full Body HIIT', 'Author: Coach Afrar\r\nContent: Short on time? Try this intense 20-minute HIIT routine that burns fat and builds strength!\r\n\r\nRoutine:\r\n\r\nJumping jacks – 1 min\r\n\r\nSquats – 1 min\r\n\r\nPush-ups – 1 min\r\n\r\nRest – 30 sec\r\n\r\nRepeat 3 rounds\r\n\r\nCooldown: Stretch and deep breathing for 5 min.\r\nDo this 3x/week for best results!', 'Admin User', 'Workout Routine', 'group_classes.jpg', '2025-04-26 02:48:34'),
(2, '🍱 Meal Recipe: High-Protein Breakfast Bowl', 'Author: FitZone Team\r\nContent: Start your morning with a delicious and filling protein-packed breakfast!\r\n\r\nIngredients:\r\n\r\n2 eggs (boiled or scrambled)\r\n\r\n1/2 cup cooked quinoa\r\n\r\n1/4 avocado, sliced\r\n\r\n1/2 cup sautéed spinach\r\n\r\n1 tbsp feta cheese\r\n\r\nSalt and pepper to taste\r\n\r\nInstructions:\r\n\r\nLayer quinoa in a bowl.\r\n\r\nAdd cooked spinach, eggs, and avocado.\r\n\r\nSprinkle with feta cheese, salt, and pepper.\r\n\r\nEnjoy a wholesome breakfast ready in under 10 minutes!\r\n\r\nBenefits:\r\n✅ Rich in protein\r\n✅ Supports muscle recovery\r\n✅ Keeps you full longer', 'Admin User', 'Healthy Recipe', 'meals_1.jpg', '2025-04-26 02:36:42'),
(3, 'Meal Plan: Clean Eating Week Plan', 'Content: Looking to reset your diet? Here\'s a 7-day clean eating plan to help you feel energized and focused:\r\n\r\nDay 1 Example:\r\n\r\nBreakfast: Oatmeal + berries + almonds\r\n\r\nLunch: Grilled chicken salad with olive oil dressing\r\n\r\nDinner: Baked salmon + quinoa + steamed broccoli\r\n\r\nSnacks: Greek yogurt, carrot sticks\r\n\r\nTips:\r\nStay hydrated\r\nAvoid processed foods\r\nPrep meals ahead\r\nPrintable plan available at the gym counter!', 'Admin User', 'Meal Plan', 'meals_1.jpg', '2025-04-26 02:46:14');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
CREATE TABLE IF NOT EXISTS `memberships` (
  `Membership_ID` int NOT NULL,
  `Admission_Number` int NOT NULL,
  `Plan_ID` int NOT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date NOT NULL,
  PRIMARY KEY (`Membership_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`Membership_ID`, `Admission_Number`, `Plan_ID`, `Start_Date`, `End_Date`) VALUES
(1, 4, 1, '2025-04-19', '2025-05-19'),
(2, 4, 1, '2025-04-19', '2025-05-19'),
(3, 4, 3, '2025-04-20', '2025-05-20'),
(4, 4, 3, '2025-04-20', '2025-05-20'),
(5, 4, 2, '2025-04-20', '2025-05-20'),
(6, 4, 2, '2025-04-20', '2025-05-20'),
(7, 4, 2, '2025-04-20', '2025-05-20'),
(8, 4, 2, '2025-04-20', '2025-05-20'),
(9, 4, 2, '2025-04-20', '2025-05-20'),
(10, 4, 1, '2025-04-20', '2025-05-20'),
(0, 4, 1, '2025-04-25', '2025-05-25');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

DROP TABLE IF EXISTS `membership_plans`;
CREATE TABLE IF NOT EXISTS `membership_plans` (
  `Plan_ID` int NOT NULL,
  `Plan_Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Duration_Days` int NOT NULL,
  `Benefits` text NOT NULL,
  PRIMARY KEY (`Plan_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`Plan_ID`, `Plan_Name`, `Price`, `Duration_Days`, `Benefits`) VALUES
(3, 'VIP Plan', 6500.00, 30, '?? All Premium benefits ?? Weekly personal training ?? Customized meal & workout plans ?? Priority booking for all classes'),
(1, 'Basic Plan', 3500.00, 30, '?? Access to gym during daytime hours ?? Group classes (limited) ?? Use of locker rooms'),
(2, 'Premium Plan', 5000.00, 30, '?? 24/7 Gym Access ?? Unlimited group classes ?? 1 Free personal training session/month ?? Access to sauna and spa');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

DROP TABLE IF EXISTS `queries`;
CREATE TABLE IF NOT EXISTS `queries` (
  `Query_ID` int NOT NULL AUTO_INCREMENT,
  `Admission_Number` int NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Message` text NOT NULL,
  `Submitted_At` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Response` text NOT NULL,
  `Responded_At` datetime NOT NULL,
  PRIMARY KEY (`Query_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queries`
--

INSERT INTO `queries` (`Query_ID`, `Admission_Number`, `Subject`, `Message`, `Submitted_At`, `Response`, `Responded_At`) VALUES
(1, 4, 'above classes', 'change the time', '2025-04-20 06:45:23', 'no change', '2025-04-20 12:42:09'),
(2, 4, 'love case', 'I love you', '2025-04-20 15:56:30', 'love you too', '2025-04-20 21:27:42'),
(3, 4, 'fefe', 'fegeg', '2025-04-25 15:23:50', 'sffef', '2025-04-25 21:26:38'),
(4, 4, 'fefe', 'fegeg', '2025-04-25 15:28:32', '', '0000-00-00 00:00:00'),
(5, 4, 'dw', 'eve', '2025-04-25 15:28:47', '', '0000-00-00 00:00:00'),
(6, 4, 'dw', 'eve', '2025-04-25 15:29:01', '', '0000-00-00 00:00:00'),
(7, 4, 'dw', 'eve', '2025-04-25 15:37:48', '', '0000-00-00 00:00:00'),
(8, 4, 'dw', 'eve', '2025-04-25 15:45:53', '', '0000-00-00 00:00:00'),
(9, 4, 'myy', 'yyy', '2025-04-25 15:46:07', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

DROP TABLE IF EXISTS `users1`;
CREATE TABLE IF NOT EXISTS `users1` (
  `Admission_Number` int NOT NULL AUTO_INCREMENT,
  `Full_Name` varchar(100) NOT NULL,
  `NIC` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Weight` varchar(10) NOT NULL,
  `Height` varchar(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` varchar(30) NOT NULL DEFAULT 'Customer',
  PRIMARY KEY (`Admission_Number`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`Admission_Number`, `Full_Name`, `NIC`, `Address`, `Email`, `Phone_Number`, `Weight`, `Height`, `Username`, `Password`, `Role`) VALUES
(3, 'Admin User', 'ADMIN001', 'Head Office', 'admin@fitzone.com', '091-2333445', '-', '-', 'admin', '$2y$10$NeBTP/HHjQl72YvUcUNqnO.fFsMcsBnQSteTJzY/3MGseZFQLEsRS', 'admin'),
(4, 'nawaf jawfer', '23456789023', 'galle', 'nawafjawfer@gmail.com', '0770987654', '67', '160', 'nawaf', '$2y$10$phYJkyG/7VmJvCZtJMyy5.pZz01hYc9bz07HF.hIMLz.SyRzEDG/W', 'customer'),
(7, 'gota gobbaya', '23456789020', 'galle', 'gota@gmail.com', '0986726520', '0', '0', 'gota', '$2y$10$pZKoeHr4NFmDBQnvQRhrJeblLUiKc9mEvrJm3Lx1dVcxXzbsUVQQu', 'gym_management_staff'),
(9, 'muhammed', '209389301010', 'weligama', 'muhammed@gmail.com', '0710293919', '0', '0', 'muhammed', '$2y$10$UmnYuJ2qIVZqr58z9ntyt.5GWiJsIgPoEaWHba5BFWlM1DdlVX56.', 'gym_management_staff');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
