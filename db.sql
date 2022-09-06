-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2022 at 03:57 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digitalf_dvmProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_panel_menu`
--

CREATE TABLE `admin_panel_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `is_parent` int(11) DEFAULT NULL,
  `is_child` int(11) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_panel_menu`
--

INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES
(1, 'Dashboard', 'dashboard', 'fa-solid fa-gauge', 0, 1, 0, '1', '2022-08-29 03:55:52'),
(2, 'Dashboard View', 'adminPanel', NULL, 1, 0, 1, '1', '2022-08-29 03:55:52'),
(3, 'School Profile', 'school-profile', NULL, 1, 0, 1, '1', '2022-08-29 03:55:52'),
(4, 'Students', 'students', 'fa-solid fa-graduation-cap', 0, 1, 0, '1', '2022-08-29 03:55:52'),
(5, 'List Students', 'student/list', NULL, 4, 0, 1, '1', '2022-08-29 03:55:52'),
(6, 'Add Student', 'student/addStudent', NULL, 4, 0, 1, '1', '2022-08-29 03:55:52'),
(12, 'Teachers', 'teachers', 'fa-solid fa-chalkboard-user', 0, 1, 0, '1', '2022-08-29 03:55:52'),
(13, 'List Teachers', 'teacher/list', NULL, 12, 0, 1, '1', '2022-08-29 03:55:52'),
(14, 'Add Teacher', 'teacher/addTeacher', NULL, 12, 0, 1, '1', '2022-08-29 03:55:52'),
(15, 'Masters', 'master', 'fa-solid fa-receipt', 0, 1, 0, '1', '2022-08-29 03:55:52'),
(16, 'City Master', 'master/cityMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(17, 'State Master', 'master/stateMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(18, 'Class Master', 'master/classMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(19, 'Section Master', 'master/sectionMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(20, 'Subject Master', 'master/subjectMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(21, 'Week Master', 'master/weekMaster', NULL, 15, 0, 1, '2', '2022-08-29 03:55:52'),
(22, 'Hour Master', 'master/hourMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(23, 'Teachers Subjects Master', 'master/teacherSubjectsMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(24, 'Time Table Master', 'master/timeTableSheduleMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(25, 'Panel User Master', 'master/panelUserMaster', NULL, 15, 0, 1, '1', '2022-08-29 03:55:52'),
(27, 'Staff', 'master', 'fa-solid fa-people-group', 0, 1, 0, '1', '2022-08-29 03:55:52'),
(28, 'Notification', 'master/notificationMaster', NULL, 27, 0, 1, '1', '2022-08-29 03:55:52'),
(29, 'Fees Master', 'master/feesMaster', NULL, 27, 0, 1, '2', '2022-08-29 03:55:52'),
(30, 'Month Master', 'master/monthMaster', NULL, 15, 0, 1, '2', '2022-08-29 03:55:52'),
(31, 'Fees Submit Master', 'master/submitFeesMaster', NULL, 15, 0, 1, '2', '2022-08-29 03:55:52'),
(32, 'Set DigiCoin Master', 'digicoin/setDigiCoinMaster', NULL, 33, 0, 1, '1', '2022-09-02 03:12:09'),
(33, 'DigiCoins', '#', 'fa-solid fa-coins', 0, 1, 0, '1', '2022-09-02 12:53:50'),
(34, 'Students DigiCoins', 'digicoin/studentDigiCoin', NULL, 33, 0, 1, '1', '2022-09-02 12:54:53'),
(35, 'Teachers DigiCoins', 'digicoin/teacherDigiCoin', NULL, 33, 0, 1, '1', '2022-09-02 12:54:53'),
(36, 'Gift Master', 'digicoin/giftMaster', NULL, 33, 0, 1, '1', '2022-09-04 02:40:27'),
(37, 'Gift Redeem Master', 'digicoin/giftRedeemMaster', NULL, 33, 0, 1, '1', '2022-09-06 02:33:05'),
(38, 'LeaderBoard', 'digicoin/leaderBoard', NULL, 33, 0, 1, '1', '2022-09-06 02:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `attendence`
--

CREATE TABLE `attendence` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) DEFAULT NULL,
  `stu_id` int(11) NOT NULL,
  `stu_class` varchar(100) DEFAULT NULL,
  `stu_section` varchar(100) DEFAULT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `attendenceStatus` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `att_date` date DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendence`
--

INSERT INTO `attendence` (`id`, `schoolUniqueCode`, `stu_id`, `stu_class`, `stu_section`, `login_user_id`, `login_user_type`, `attendenceStatus`, `dateTime`, `att_date`, `status`, `created_at`) VALUES
(1, '', 5, '11th', 'Arts', 3, 'Teacher', '1', '2022-08-21 02:31:31', '2022-08-21', '1', '2022-08-21 02:31:31'),
(2, '', 8, '11th', 'Arts', 3, 'Teacher', '1', '2022-08-21 02:31:31', '2022-08-21', '1', '2022-08-21 02:31:31'),
(3, '', 16, '11th', 'Arts', 3, 'Teacher', '0', '2022-08-21 02:31:31', '2022-08-21', '1', '2022-08-21 02:31:31'),
(4, NULL, 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 00:37:07', '2022-08-29', '1', '2022-08-29 12:37:07'),
(5, NULL, 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 00:37:07', '2022-08-29', '1', '2022-08-29 12:37:07'),
(6, NULL, 16, '11th', 'Arts', 2, 'Teacher', '0', '2022-08-29 00:37:07', '2022-08-29', '1', '2022-08-29 12:37:07'),
(7, NULL, 5, '11th', 'Arts', 2, 'Teacher', '0', '2022-08-29 19:36:16', '2022-08-30', '1', '2022-08-30 07:36:16'),
(8, NULL, 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 19:36:16', '2022-08-30', '1', '2022-08-30 07:36:16'),
(9, NULL, 16, '11th', 'Arts', 2, 'Teacher', '0', '2022-08-29 19:36:16', '2022-08-30', '1', '2022-08-30 07:36:16'),
(10, '683611', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-31 01:28:01', '2022-08-31', '1', '2022-08-31 13:28:01'),
(11, '683611', 8, '11th', 'Arts', 2, 'Teacher', '0', '2022-08-31 01:28:01', '2022-08-31', '1', '2022-08-31 13:28:01'),
(12, '683611', 16, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-31 01:28:01', '2022-08-31', '1', '2022-08-31 13:28:01'),
(13, '683611', 4, 'Nursery', 'A', 2, 'Teacher', '0', '2022-09-01 06:37:35', '2022-09-01', '1', '2022-09-01 06:37:35'),
(21, '683611', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-03 03:19:33', '2022-09-03', '1', '2022-09-03 15:19:33'),
(22, '683611', 8, '11th', 'Arts', 2, 'Teacher', '0', '2022-09-03 03:19:34', '2022-09-03', '1', '2022-09-03 15:19:34'),
(23, '683611', 16, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-03 03:19:34', '2022-09-03', '1', '2022-09-03 15:19:34'),
(24, '683611', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-04 04:37:02', '2022-09-04', '1', '2022-09-04 04:37:02'),
(25, '683611', 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-04 04:37:02', '2022-09-04', '1', '2022-09-04 04:37:02'),
(26, '683611', 16, '11th', 'Arts', 2, 'Teacher', '0', '2022-09-04 04:37:02', '2022-09-04', '1', '2022-09-04 04:37:02'),
(27, '683611', 4, 'Nursery', 'A', 2, 'Teacher', '1', '2022-09-04 04:46:09', '2022-09-04', '1', '2022-09-04 16:46:09'),
(32, '683611', 5, '11th', 'Arts', 6, 'Teacher', '1', '2022-09-05 03:44:31', '2022-09-05', '1', '2022-09-05 03:44:31'),
(33, '683611', 8, '11th', 'Arts', 6, 'Teacher', '1', '2022-09-05 03:44:31', '2022-09-05', '1', '2022-09-05 03:44:31'),
(34, '683611', 18, '11th', 'Arts', 6, 'Teacher', '0', '2022-09-05 03:44:31', '2022-09-05', '1', '2022-09-05 03:44:31'),
(35, '683611', 19, '11th', 'Arts', 6, 'Teacher', '1', '2022-09-05 03:44:31', '2022-09-05', '1', '2022-09-05 03:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `cityName` varchar(255) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `schoolUniqueCode`, `cityName`, `status`, `created_at`) VALUES
(1, '683611', 'Baraut', '1', '2022-08-11 05:58:23'),
(2, '683611', 'Baghpat', '1', '2022-08-11 05:58:23'),
(3, '683611', 'Khekra', '1', '2022-08-11 05:58:23'),
(4, '683611', 'Delhi', '1', '2022-08-11 05:58:23'),
(5, '683611', 'Noida', '1', '2022-08-11 05:58:23'),
(6, '683611', 'Sonipat', '1', '2022-08-11 05:58:23'),
(7, '683611', 'Panipat', '1', '2022-08-11 05:58:23'),
(8, '683611', 'Mumbai', '1', '2022-08-21 04:42:55'),
(9, '683611', 'Agra', '1', '2022-08-21 04:45:22'),
(13, '683611', 'Greater Noida', '1', '2022-08-21 05:01:38'),
(14, '683611', 'Noida', '1', '2022-08-21 05:04:43'),
(16, '683611', 'bhopal', '1', '2022-08-28 14:33:55'),
(17, '683611', 'Ahmedabad', '1', '2022-08-30 03:25:19'),
(19, '724212', 'Baraut', '1', '2022-09-03 13:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `className` varchar(255) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `schoolUniqueCode`, `className`, `status`, `created_at`) VALUES
(1, '683611', 'Nursery', '1', '2022-08-11 05:48:54'),
(2, '683611', 'LKG', '1', '2022-08-11 05:48:54'),
(3, '683611', 'UKG', '1', '2022-08-11 05:48:54'),
(4, '683611', '1st', '1', '2022-08-11 05:48:54'),
(5, '683611', '2nd', '1', '2022-08-11 05:48:54'),
(6, '683611', '3rd', '1', '2022-08-11 06:00:02'),
(7, '683611', '4th', '1', '2022-08-11 06:00:02'),
(8, '683611', '5th', '1', '2022-08-11 06:00:02'),
(9, '683611', '6th', '1', '2022-08-11 06:00:02'),
(10, '683611', '7th', '1', '2022-08-11 06:00:02'),
(11, '683611', '8th', '1', '2022-08-11 06:00:02'),
(12, '683611', '9th', '1', '2022-08-11 06:00:02'),
(13, '683611', '10th', '1', '2022-08-11 06:00:02'),
(14, '683611', '11th', '1', '2022-08-11 06:00:02'),
(15, '683611', '12th', '1', '2022-08-11 06:00:02'),
(17, '724212', '12th', '1', '2022-09-03 13:53:12'),
(18, '724212', '10th', '1', '2022-09-03 13:53:34');

-- --------------------------------------------------------

--
-- Table structure for table `classshedule`
--

CREATE TABLE `classshedule` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `class_id` varchar(100) NOT NULL,
  `section_id` varchar(100) NOT NULL,
  `shedule_json` json NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classshedule`
--

INSERT INTO `classshedule` (`id`, `schoolUniqueCode`, `class_id`, `section_id`, `shedule_json`, `status`, `created_at`) VALUES
(1, '', '14', '13', '[{\"time\": \"1\", \"subject\": \"1\", \"teacher\": \"2\"}, {\"time\": \"3\", \"subject\": \"2\", \"teacher\": \"1\"}, {\"time\": \"4\", \"subject\": \"6\", \"teacher\": \"3\"}, {\"time\": \"5\", \"subject\": \"5\", \"teacher\": \"1\"}, {\"time\": \"6\", \"subject\": \"4\", \"teacher\": \"2\"}, {\"time\": \"7\", \"subject\": \"3\", \"teacher\": \"2\"}, {\"time\": \"8\", \"subject\": \"7\", \"teacher\": \"2\"}, {\"time\": \"9\", \"subject\": \"9\", \"teacher\": \"3\"}]', '1', '2022-08-23 09:10:58'),
(2, '', '1', '1', '[{\"time\": \"1\", \"subject\": \"5\", \"teacher\": \"2\"}, {\"time\": \"3\", \"subject\": \"10\", \"teacher\": \"1\"}, {\"time\": \"4\", \"subject\": \"4\", \"teacher\": \"3\"}, {\"time\": \"5\", \"subject\": \"7\", \"teacher\": \"2\"}, {\"time\": \"6\", \"subject\": \"4\", \"teacher\": \"1\"}, {\"time\": \"7\", \"subject\": \"8\", \"teacher\": \"3\"}, {\"time\": \"8\", \"subject\": \"5\", \"teacher\": \"3\"}, {\"time\": \"9\", \"subject\": \"7\", \"teacher\": \"1\"}]', '1', '2022-08-24 02:19:52'),
(4, '683611', '12', '1', '[{\"time\": \"10\", \"subject\": \"10\", \"teacher\": \"5\"}, {\"time\": \"11\", \"subject\": \"9\", \"teacher\": \"5\"}]', '4', '2022-08-28 05:53:02'),
(5, '951166', '', '', 'null', '1', '2022-08-28 14:03:05'),
(6, '951166', '', '', 'null', '1', '2022-08-28 14:03:25'),
(7, '965316', '1', '1', '[{\"time\": \"12\", \"subject\": \"8\", \"teacher\": \"7\"}, {\"time\": \"13\", \"subject\": \"9\", \"teacher\": \"7\"}, {\"time\": \"14\", \"subject\": \"7\", \"teacher\": \"7\"}]', '1', '2022-08-30 14:49:18'),
(8, '683611', '16', '17', '[{\"time\": \"10\", \"subject\": \"11\", \"teacher\": \"4\"}, {\"time\": \"11\", \"subject\": \"11\", \"teacher\": \"3\"}, {\"time\": \"15\", \"subject\": \"11\", \"teacher\": \"6\"}]', '1', '2022-09-03 12:26:37'),
(9, '724212', '17', '18', '[{\"time\": \"17\", \"subject\": \"14\", \"teacher\": \"8\"}, {\"time\": \"18\", \"subject\": \"13\", \"teacher\": \"8\"}]', '1', '2022-09-03 14:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `departure`
--

CREATE TABLE `departure` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) DEFAULT NULL,
  `attendence_id` varchar(100) DEFAULT NULL,
  `stu_id` int(11) NOT NULL,
  `stu_class` varchar(100) DEFAULT NULL,
  `stu_section` varchar(100) DEFAULT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `departureStatus` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `dateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dept_date` date DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departure`
--

INSERT INTO `departure` (`id`, `schoolUniqueCode`, `attendence_id`, `stu_id`, `stu_class`, `stu_section`, `login_user_id`, `login_user_type`, `departureStatus`, `dateTime`, `dept_date`, `status`, `created_at`) VALUES
(1, '', '1', 5, '11th', 'Arts', 3, 'Teacher', '1', '2022-08-21 03:12:22', '2022-08-21', '1', '2022-08-21 03:12:22'),
(2, '', '2', 8, '11th', 'Arts', 3, 'Teacher', '1', '2022-08-21 03:12:22', '2022-08-21', '1', '2022-08-21 03:12:22'),
(3, NULL, '4', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 00:37:30', '2022-08-29', '1', '2022-08-29 12:37:30'),
(4, NULL, '5', 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 00:37:30', '2022-08-29', '1', '2022-08-29 12:37:30'),
(5, NULL, '8', 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-29 19:36:36', '2022-08-30', '1', '2022-08-30 07:36:36'),
(6, '683611', '10', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-31 01:30:54', '2022-08-31', '1', '2022-08-31 13:30:54'),
(7, '683611', '12', 16, '11th', 'Arts', 2, 'Teacher', '1', '2022-08-31 01:30:54', '2022-08-31', '1', '2022-08-31 13:30:54'),
(8, '683611', '14', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-02 21:54:02', '2022-09-03', '1', '2022-09-03 09:54:02'),
(9, '683611', '15', 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-02 21:54:02', '2022-09-03', '1', '2022-09-03 09:54:02'),
(10, '683611', '16', 16, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-02 21:54:02', '2022-09-03', '1', '2022-09-03 09:54:02'),
(11, '683611', '24', 5, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-04 04:39:28', '2022-09-04', '1', '2022-09-04 04:39:28'),
(12, '683611', '25', 8, '11th', 'Arts', 2, 'Teacher', '1', '2022-09-04 04:39:28', '2022-09-04', '1', '2022-09-04 04:39:28'),
(13, '683611', '27', 4, 'Nursery', 'A', 2, 'Teacher', '1', '2022-09-04 04:48:05', '2022-09-04', '1', '2022-09-04 16:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `date_of_exam` date NOT NULL,
  `max_marks` float NOT NULL,
  `min_marks` float NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `schoolUniqueCode`, `class_id`, `section_id`, `subject_id`, `exam_name`, `date_of_exam`, `max_marks`, `min_marks`, `login_user_id`, `login_user_type`, `status`, `created_at`) VALUES
(1, NULL, 8, 2, 9, 'Weekly Test for Accounts Date: 2022-08-30', '2022-08-30', 100, 33, 3, 'Teacher', '1', '2022-08-26 02:44:15'),
(2, NULL, 8, 2, 9, 'Montly Test for Biology Date: 2022-08-31', '2022-08-31', 100, 40, 3, 'Teacher', '1', '2022-08-26 06:48:34'),
(3, NULL, 9, 2, 9, 'Montly Test for Biology Date: 2022-08-31 Exam Id: 6254', '2022-08-31', 100, 40, 3, 'Teacher', '1', '2022-08-26 08:44:35'),
(4, NULL, 13, 2, 2, 'Today English Exam Date: 13 Exam Id: 8709', '0000-00-00', 100, 50, 2, 'Teacher', '1', '2022-08-29 12:38:42'),
(5, NULL, 14, 13, 1, 'Hindi Exam Weekly Date: 14 Exam Id: 9343', '0000-00-00', 100, 50, 2, 'Teacher', '1', '2022-08-30 15:14:56'),
(6, '683611', 14, 13, 1, 'Hindi Chapter 5 Date: 14 Exam Id: 209', '0000-00-00', 100, 33, 2, 'Teacher', '1', '2022-08-31 13:29:11'),
(7, '683611', 1, 2, 3, 'a Date: 1 Exam Id: 6968', '0000-00-00', 12, 12, 2, 'Teacher', '1', '2022-09-01 06:39:19'),
(8, '683611', 13, 1, 1, 'Monday Test For Hindi Date: 13 Exam Id: 1148', '0000-00-00', 100, 33, 2, 'Teacher', '1', '2022-09-03 09:55:24'),
(9, '683611', 14, 13, 2, 'English test Date: 14 Exam Id: 2698', '0000-00-00', 100, 33, 2, 'Teacher', '1', '2022-09-03 09:56:22'),
(10, '683611', 1, 1, 1, 'new exam Date: 1 Exam Id: 5459', '0000-00-00', 15, 5, 2, 'Teacher', '1', '2022-09-03 13:24:55'),
(11, '683611', 14, 2, 3, 'Weekly Exam English Subject Date: 14 Exam Id: 4871 Date: 14 Exam Id: 2917', '0000-00-00', 100, 35, 2, 'Teacher', '1', '2022-09-04 04:39:12'),
(12, '683611', 14, 13, 3, 'Per Day Math Exam Date: 14 Exam Id: 7438', '0000-00-00', 100, 50, 2, 'Teacher', '1', '2022-09-04 04:43:52'),
(13, '683611', 4, 1, 1, 'new exam Date: 4 Exam Id: 3007', '0000-00-00', 40, 15, 2, 'Teacher', '1', '2022-09-04 11:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `fees_amt` float NOT NULL,
  `status` enum('1','2') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `class_id`, `fees_amt`, `status`, `created_at`) VALUES
(1, 15, 1200, '1', '2022-08-27 07:54:11'),
(2, 14, 1100, '1', '2022-08-27 08:23:00'),
(4, 1, 100, '1', '2022-08-27 08:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `feesforstudent`
--

CREATE TABLE `feesforstudent` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `months` int(11) NOT NULL,
  `offer_amt` float DEFAULT NULL,
  `deposit_amt` float NOT NULL,
  `payment_mode` enum('1','2') NOT NULL COMMENT '1 => online, 2 => offline',
  `depositer_name` varchar(100) NOT NULL,
  `depositer_mobile` varchar(100) DEFAULT NULL,
  `depositer_address` varchar(100) NOT NULL,
  `custom_amt` float DEFAULT NULL,
  `total_due_balance` float DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `get_digi_coin`
--

CREATE TABLE `get_digi_coin` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `for_what` int(11) NOT NULL,
  `digiCoin` int(11) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `get_digi_coin`
--

INSERT INTO `get_digi_coin` (`id`, `schoolUniqueCode`, `user_type`, `user_id`, `for_what`, `digiCoin`, `status`, `created_at`) VALUES
(1, '683611', 'Student', 5, 1, 10, '1', '2022-09-01 02:59:41'),
(2, '683611', 'Student', 8, 1, 10, '1', '2022-09-01 02:59:41'),
(3, '683611', 'Student', 16, 1, 10, '1', '2022-09-01 02:59:41'),
(4, '683611', 'Teacher', 3, 1, 30, '1', '2022-09-01 02:59:41'),
(5, '683611', 'Student', 5, 2, 10, '1', '2022-09-01 03:04:53'),
(6, '683611', 'Student', 8, 2, 10, '1', '2022-09-01 03:04:53'),
(7, '683611', 'Teacher', 3, 2, 15, '1', '2022-09-01 03:04:53'),
(8, '683611', 'Student', 5, 1, 10, '1', '2022-09-01 03:08:29'),
(9, '683611', 'Student', 8, 1, 10, '1', '2022-09-01 03:08:29'),
(10, '683611', 'Teacher', 3, 1, 30, '1', '2022-09-01 03:08:29'),
(11, '683611', 'Student', 13, 3, 10, '1', '2022-09-02 02:52:06'),
(12, '683611', 'Student', 14, 3, 6, '1', '2022-09-02 02:52:06'),
(13, '683611', 'Student', 16, 3, 5, '1', '2022-09-02 02:54:13'),
(14, '683611', 'Student', 13, 3, 10, '1', '2022-09-02 03:22:13'),
(15, '683611', 'Student', 14, 3, 6, '1', '2022-09-02 03:22:13'),
(16, '683611', 'Teacher', 14, 3, 0, '1', '2022-09-02 09:46:53'),
(17, '683611', 'Student', 5, 1, 10, '1', '2022-09-03 06:33:15'),
(18, '683611', 'Student', 8, 1, 10, '1', '2022-09-03 06:33:15'),
(19, '683611', 'Student', 16, 1, 10, '1', '2022-09-03 06:33:15'),
(20, '683611', 'Teacher', 2, 1, 30, '1', '2022-09-03 06:33:15'),
(21, '683611', 'Student', 5, 2, 10, '1', '2022-09-03 09:54:02'),
(22, '683611', 'Student', 8, 2, 10, '1', '2022-09-03 09:54:02'),
(23, '683611', 'Student', 16, 2, 10, '1', '2022-09-03 09:54:02'),
(24, '683611', 'Teacher', 2, 2, 15, '1', '2022-09-03 09:54:02'),
(25, '683611', 'Student', 5, 3, 4, '1', '2022-09-03 09:57:37'),
(26, '683611', 'Student', 8, 3, 9, '1', '2022-09-03 09:57:37'),
(27, '683611', 'Teacher', 16, 3, 15, '1', '2022-09-03 09:57:37'),
(28, '683611', 'Student', 7, 1, 10, '1', '2022-09-03 13:21:31'),
(29, '683611', 'Student', 17, 1, 10, '1', '2022-09-03 13:21:31'),
(30, '683611', 'Student', 19, 1, 10, '1', '2022-09-03 13:21:31'),
(31, '683611', 'Teacher', 3, 1, 30, '1', '2022-09-03 13:21:31'),
(32, '683611', 'Student', 4, 3, 9, '1', '2022-09-03 13:25:38'),
(33, '683611', 'Student', 5, 1, 10, '1', '2022-09-03 15:19:34'),
(34, '683611', 'Student', 16, 1, 10, '1', '2022-09-03 15:19:34'),
(35, '683611', 'Teacher', 2, 1, 30, '1', '2022-09-03 15:19:34'),
(36, '683611', 'Student', 5, 1, 10, '1', '2022-09-04 04:37:02'),
(37, '683611', 'Student', 8, 1, 10, '1', '2022-09-04 04:37:02'),
(38, '683611', 'Teacher', 2, 1, 30, '1', '2022-09-04 04:37:02'),
(39, '683611', 'Student', 5, 2, 10, '1', '2022-09-04 04:39:28'),
(40, '683611', 'Student', 8, 2, 10, '1', '2022-09-04 04:39:28'),
(41, '683611', 'Teacher', 2, 2, 15, '1', '2022-09-04 04:39:28'),
(42, '683611', 'Student', 8, 3, 6, '1', '2022-09-04 04:48:39'),
(43, '683611', 'Teacher', 16, 3, 0, '1', '2022-09-04 04:48:39'),
(44, '683611', 'Student', 4, 1, 10, '1', '2022-09-04 16:46:09'),
(45, '683611', 'Teacher', 2, 1, 30, '1', '2022-09-04 16:46:09'),
(46, '683611', 'Student', 4, 2, 10, '1', '2022-09-04 16:48:05'),
(47, '683611', 'Teacher', 2, 2, 15, '1', '2022-09-04 16:48:05'),
(48, '683611', 'Student', 5, 1, 10, '1', '2022-09-05 03:31:33'),
(49, '683611', 'Student', 8, 1, 10, '1', '2022-09-05 03:31:33'),
(50, '683611', 'Student', 19, 1, 10, '1', '2022-09-05 03:31:33'),
(51, '683611', 'Teacher', 3, 1, 30, '1', '2022-09-05 03:31:33'),
(52, '683611', 'Student', 5, 1, 10, '1', '2022-09-05 03:44:31'),
(53, '683611', 'Student', 8, 1, 10, '1', '2022-09-05 03:44:31'),
(54, '683611', 'Student', 19, 1, 10, '1', '2022-09-05 03:44:31'),
(55, '683611', 'Teacher', 6, 1, 30, '1', '2022-09-05 03:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `gift`
--

CREATE TABLE `gift` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `gift_name` varchar(100) NOT NULL,
  `gift_image` varchar(255) NOT NULL,
  `redeem_digiCoins` int(11) NOT NULL,
  `user_type` enum('1','2','3','4') NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gift`
--

INSERT INTO `gift` (`id`, `schoolUniqueCode`, `gift_name`, `gift_image`, `redeem_digiCoins`, `user_type`, `status`, `created_at`) VALUES
(1, '683611', 'NoteBook', 'img-GIFT-41e3YGKg-3L.jpg', 200, '1', '1', '2022-09-04 02:45:24'),
(2, '683611', 'NoteBook', 'img-GIFT-41e3YGKg-3L.jpg', 300, '2', '1', '2022-09-04 02:45:41'),
(3, '683611', 'Pencil Box', 'img-GIFT-61QVt3iITJL._SL1332_.jpg', 25, '2', '1', '2022-09-05 03:46:49');

-- --------------------------------------------------------

--
-- Table structure for table `home_work`
--

CREATE TABLE `home_work` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `home_work_note` text NOT NULL,
  `home_work_date` date NOT NULL,
  `home_work_finish_date` date NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `home_work`
--

INSERT INTO `home_work` (`id`, `schoolUniqueCode`, `login_user_id`, `login_user_type`, `class_id`, `section_id`, `subject_id`, `home_work_note`, `home_work_date`, `home_work_finish_date`, `status`, `created_at`) VALUES
(1, '683611', 3, 'Teacher', 9, 2, 9, 'Learn the chapter 6 wala in english nhi hindi wala', '2022-09-03', '2022-09-05', '1', '2022-09-04 09:29:24'),
(2, '683611', 2, 'Teacher', 15, 2, 1, 'Have to learn chapter 4 & do all questions answers as well.', '2022-09-04', '2022-09-06', '1', '2022-09-04 16:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `month`
--

CREATE TABLE `month` (
  `id` int(11) NOT NULL,
  `monthName` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `month`
--

INSERT INTO `month` (`id`, `monthName`, `status`, `created_at`) VALUES
(1, 'January', '1', '2022-08-27 09:23:04'),
(2, 'February', '1', '2022-08-27 09:23:26'),
(3, 'March', '1', '2022-08-27 09:23:31'),
(4, 'April', '1', '2022-08-27 09:23:36'),
(5, 'May', '1', '2022-08-27 09:23:44'),
(6, 'June', '1', '2022-08-27 09:23:52'),
(7, 'July', '1', '2022-08-27 09:23:57'),
(8, 'August', '1', '2022-08-27 09:24:01'),
(9, 'September', '1', '2022-08-27 09:24:06'),
(10, 'October', '1', '2022-08-27 09:24:11'),
(11, 'November', '1', '2022-08-27 09:24:16'),
(12, 'December', '1', '2022-08-27 09:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `panel_menu_permission`
--

CREATE TABLE `panel_menu_permission` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `permissions` json NOT NULL,
  `is_head` enum('1','2','3','4') DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panel_menu_permission`
--

INSERT INTO `panel_menu_permission` (`id`, `schoolUniqueCode`, `user_id`, `user_type`, `permissions`, `is_head`, `status`, `created_at`) VALUES
(1, '683611', 3, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"30\", \"31\", \"28\", \"29\", \"32\", \"34\", \"35\"]', '1', '1', '2022-08-24 12:34:14'),
(2, '683611', 4, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-24 12:37:06'),
(3, '683611', 5, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-24 12:38:56'),
(4, '', 7, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-24 12:49:40'),
(5, '', 8, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-24 13:09:33'),
(6, '', 9, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-25 02:33:46'),
(7, '', 10, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-25 02:33:58'),
(8, '', 14, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"22\", \"23\", \"24\", \"25\", \"28\", \"32\", \"34\", \"35\", \"36\", \"37\", \"38\"]', NULL, '1', '2022-08-25 02:51:10'),
(9, '973713', 0, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 08:40:33'),
(10, '973713', 0, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 08:40:33'),
(11, '973713', 0, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 08:40:33'),
(12, '973713', 16, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 08:50:35'),
(13, '973713', 17, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 09:47:47'),
(27, '467886', 32, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:26:22'),
(28, '467886', 33, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:26:22'),
(29, '467886', 34, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:26:22'),
(30, '467886', 35, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 13:26:22'),
(31, '467886', 36, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 13:31:15'),
(32, '219981', 37, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:45:57'),
(33, '219981', 38, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:45:57'),
(34, '219981', 39, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:45:57'),
(35, '219981', 40, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 13:45:57'),
(36, '951166', 41, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:51:40'),
(37, '951166', 42, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:51:40'),
(38, '951166', 43, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 13:51:40'),
(39, '951166', 44, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 13:51:40'),
(40, '965316', 45, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 14:21:26'),
(41, '965316', 46, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 14:21:26'),
(42, '965316', 47, 'Principal', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', '1', '1', '2022-08-28 14:21:26'),
(43, '965316', 48, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 14:21:26'),
(44, '965316', 49, 'Staff', '[\"2\", \"3\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"30\", \"31\", \"28\", \"29\"]', NULL, '1', '2022-08-28 14:45:58'),
(45, '491608', 50, 'Admin', '[\"2\", \"4\", \"5\", \"7\", \"8\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\", \"21\"]', '1', '1', '2022-08-30 03:33:14'),
(46, '491608', 51, 'Staff', '[\"2\", \"4\", \"7\", \"10\", \"11\", \"12\", \"13\", \"14\", \"16\"]', '1', '1', '2022-08-30 03:33:14'),
(47, '491608', 52, 'Principal', '[\"2\", \"4\", \"5\", \"7\", \"8\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\"]', '1', '1', '2022-08-30 03:33:14'),
(48, '491608', 53, 'Admin', '[\"2\", \"4\", \"5\", \"7\", \"8\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\", \"21\"]', NULL, '1', '2022-08-30 03:33:14'),
(49, '724212', 54, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"30\", \"31\", \"28\", \"29\", \"32\", \"34\", \"35\"]', '1', '1', '2022-09-03 13:48:43'),
(50, '724212', 55, 'Staff', '[\"2\", \"4\", \"7\", \"10\", \"11\", \"12\", \"13\", \"14\", \"16\"]', '1', '1', '2022-09-03 13:48:43'),
(51, '724212', 56, 'Principal', '[\"2\", \"4\", \"5\", \"7\", \"8\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\"]', '1', '1', '2022-09-03 13:48:43'),
(52, '724212', 57, 'Admin', '[\"2\", \"3\", \"5\", \"6\", \"13\", \"14\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"30\", \"31\", \"28\", \"29\", \"32\", \"34\", \"35\"]', NULL, '1', '2022-09-03 13:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `push_notification`
--

CREATE TABLE `push_notification` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `device_type` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `push_notification`
--

INSERT INTO `push_notification` (`id`, `schoolUniqueCode`, `title`, `body`, `device_type`, `status`, `created_at`) VALUES
(1, '', 'We are waiting for you?', 'We have new exiting gifts for you in this seasons.', 'Web', '1', '2022-08-27 04:32:50'),
(2, '', 'test', 'you are invited', 'Web', '1', '2022-08-28 14:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `qrcode_schools`
--

CREATE TABLE `qrcode_schools` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `qrcodeUrl` varchar(255) NOT NULL,
  `uniqueValue` varchar(255) NOT NULL,
  `qrcodeJson` json DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qrcode_schools`
--

INSERT INTO `qrcode_schools` (`id`, `schoolUniqueCode`, `qrcodeUrl`, `uniqueValue`, `qrcodeJson`, `type`, `user_id`, `status`, `created_at`) VALUES
(15, '', 'https://qverify.in?schid=dvm-683611', '683611', NULL, '5', '1', '1', '2022-08-28 03:06:55'),
(16, '', 'https://qverify.in?schid=dvm-427695', '427695', NULL, '5', '2', '1', '2022-08-28 03:09:29'),
(17, '973713', 'https://qverify.in?schid=dvm-973713', '973713', NULL, '5', '3', '1', '2022-08-28 08:40:33'),
(18, '467886', 'https://qverify.in?schid=dvm-467886', '467886', NULL, '5', '4', '1', '2022-08-28 10:34:12'),
(19, '219981', 'https://qverify.in?schid=dvm-219981', '219981', NULL, '5', '5', '1', '2022-08-28 13:44:18'),
(20, '951166', 'https://qverify.in?schid=dvm-951166', '951166', NULL, '5', '6', '1', '2022-08-28 13:50:47'),
(21, '965316', 'https://qverify.in?schid=dvm-965316', '965316', NULL, '5', '7', '1', '2022-08-28 14:18:59'),
(22, '491608', 'https://qverify.in?schid=dvm-491608', '491608', NULL, '5', '8', '1', '2022-08-30 03:32:05'),
(23, '724212', 'https://qverify.in?schid=dvm-724212', '724212', NULL, '5', '9', '1', '2022-09-03 13:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `qrcode_students`
--

CREATE TABLE `qrcode_students` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `qrcodeUrl` varchar(255) NOT NULL,
  `uniqueValue` varchar(255) NOT NULL,
  `qrcodeJson` json DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qrcode_students`
--

INSERT INTO `qrcode_students` (`id`, `schoolUniqueCode`, `qrcodeUrl`, `uniqueValue`, `qrcodeJson`, `type`, `user_id`, `status`, `created_at`) VALUES
(6, '683611', 'https://qverify.in?stuid=dvm-stu00001', 'stu00001', NULL, '1', '13', '1', '2022-08-12 12:10:13'),
(7, '683611', 'https://qverify.in?stuid=dvm-stu00002', 'stu00002', NULL, '1', '14', '1', '2022-08-13 02:18:41'),
(8, '683611', 'https://qverify.in?stuid=dvm-stu00003', 'stu00003', NULL, '1', '15', '1', '2022-08-13 04:07:55'),
(12, '683611', 'https://qverify.in?stuid=dvm-stu00004', 'stu00004', NULL, '1', '17', '1', '2022-08-15 04:14:45'),
(13, '683611', 'https://qverify.in?stuid=dvm-stu00005', 'stu00005', NULL, '1', '18', '1', '2022-08-26 10:39:49'),
(14, '683611', 'https://qverify.in?stuid=dvm-stu00006', 'stu00006', NULL, '1', '19', '1', '2022-08-26 10:40:25'),
(15, '683611', 'https://qverify.in?stuid=dvm-stu00006', 'stu00006', NULL, '1', '20', '1', '2022-08-28 04:04:42'),
(16, '951166', 'https://qverify.in?stuid=dvm-stu00007', 'stu00007', NULL, '1', '21', '1', '2022-08-28 13:58:18'),
(17, '965316', 'https://qverify.in?stuid=dvm-stu00008', 'stu00008', NULL, '1', '22', '1', '2022-08-28 14:25:22'),
(18, '965316', 'https://qverify.in?stuid=dvm-stu00009', 'stu00009', NULL, '1', '23', '1', '2022-08-28 14:27:05'),
(19, '963852', 'https://qverify.in?stuid=dvm-stu000010', 'stu000010', NULL, '1', '24', '1', '2022-08-29 12:41:18'),
(20, '724212', 'https://qverify.in?stuid=dvm-stu000011', 'stu000011', NULL, '1', '25', '1', '2022-09-03 14:13:45'),
(21, '724212', 'https://qverify.in?stuid=dvm-stu000012', 'stu000012', NULL, '1', '26', '1', '2022-09-03 14:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `qrcode_teachers`
--

CREATE TABLE `qrcode_teachers` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `qrcodeUrl` varchar(255) NOT NULL,
  `uniqueValue` varchar(255) NOT NULL,
  `qrcodeJson` json DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qrcode_teachers`
--

INSERT INTO `qrcode_teachers` (`id`, `schoolUniqueCode`, `qrcodeUrl`, `uniqueValue`, `qrcodeJson`, `type`, `user_id`, `status`, `created_at`) VALUES
(15, '', 'https://qverify.in?tecid=dvm-tec00004', 'tec00004', NULL, '2', '4', '1', '2022-08-28 03:17:57'),
(16, '683611', 'https://qverify.in?tecid=dvm-tec00005', 'tec00005', NULL, '2', '5', '1', '2022-08-28 04:08:01'),
(17, '', 'https://qverify.in?tecid=dvm-tec00006', 'tec00006', NULL, '2', '6', '1', '2022-08-30 14:42:38'),
(18, '965316', 'https://qverify.in?tecid=dvm-tec00007', 'tec00007', NULL, '2', '7', '1', '2022-08-30 14:47:57'),
(19, '724212', 'https://qverify.in?tecid=dvm-tec00008', 'tec00008', NULL, '2', '8', '1', '2022-09-03 14:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `redeem_gifts`
--

CREATE TABLE `redeem_gifts` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` int(11) NOT NULL,
  `gift_id` int(11) NOT NULL,
  `digiCoin_used` int(11) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1=>pending,2=>send,3=>intransit,4=>deliverd',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redeem_gifts`
--

INSERT INTO `redeem_gifts` (`id`, `schoolUniqueCode`, `login_user_id`, `login_user_type`, `gift_id`, `digiCoin_used`, `status`, `created_at`) VALUES
(1, '683611', 3, 2, 3, 25, '2', '2022-09-05 03:47:37'),
(3, '683611', 6, 2, 3, 25, '1', '2022-09-05 03:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) DEFAULT NULL,
  `exam_id` int(11) NOT NULL,
  `marks` float NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `resultStatus` enum('1','2') NOT NULL COMMENT '1 => Pass || 2 => Fail',
  `student_id` int(11) NOT NULL,
  `login_user_type` varchar(100) NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `result_date` date NOT NULL,
  `status` enum('1','2') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`id`, `schoolUniqueCode`, `exam_id`, `marks`, `remarks`, `resultStatus`, `student_id`, `login_user_type`, `login_user_id`, `result_date`, `status`, `created_at`) VALUES
(1, NULL, 1, 56, 'You have to focus on chapter no 5', '1', 13, 'Teacher', 3, '2022-08-31', '1', '2022-08-26 05:06:37'),
(2, NULL, 1, 25, 'You are fail focus on this subject', '2', 2, 'Teacher', 3, '2022-08-31', '1', '2022-08-26 05:06:37'),
(3, NULL, 1, 89, 'You are good student', '1', 3, 'Teacher', 3, '2022-08-31', '1', '2022-08-26 05:06:37'),
(4, NULL, 2, 92, 'you r good', '1', 13, 'Teacher', 3, '2022-09-01', '1', '2022-08-26 06:59:43'),
(5, '683611', 6, 15, 'poor performance ', '1', 5, 'Teacher', 2, '2022-09-08', '1', '2022-09-01 15:31:43'),
(6, '683611', 6, 50, 'Average ', '1', 8, 'Teacher', 2, '2022-09-08', '1', '2022-09-01 15:31:43'),
(7, '683611', 6, 100, 'Excellent ', '1', 16, 'Teacher', 2, '2022-09-08', '1', '2022-09-01 15:31:43'),
(8, '683611', 2, 92, 'you r good', '', 13, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 03:20:14'),
(9, '683611', 2, 54, 'Improve Yourself', '', 14, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 03:20:14'),
(10, '683611', 6, 92, 'you r good', '1', 13, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 03:22:13'),
(11, '683611', 6, 54, 'Improve Yourself', '1', 14, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 03:22:13'),
(12, '683611', 7, 92, 'you r good', '2', 13, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 09:46:53'),
(13, '683611', 7, 54, 'Improve Yourself', '2', 14, 'Teacher', 6, '2022-09-01', '1', '2022-09-02 09:46:53'),
(14, '683611', 9, 35, 'improve', '1', 5, 'Teacher', 2, '2022-09-05', '1', '2022-09-03 09:57:37'),
(15, '683611', 9, 80, 'best', '1', 8, 'Teacher', 2, '2022-09-05', '1', '2022-09-03 09:57:37'),
(16, '683611', 9, 12, 'fail ', '2', 16, 'Teacher', 2, '2022-09-05', '1', '2022-09-03 09:57:37'),
(17, '683611', 10, 12, 'amazing ', '1', 4, 'Teacher', 2, '2022-09-16', '1', '2022-09-03 13:25:38'),
(18, '683611', 12, 12, '', '2', 5, 'Teacher', 2, '2022-09-06', '1', '2022-09-04 04:48:39'),
(19, '683611', 12, 50, 'Good ', '1', 8, 'Teacher', 2, '2022-09-06', '1', '2022-09-04 04:48:39'),
(20, '683611', 12, 0, '', '2', 16, 'Teacher', 2, '2022-09-06', '1', '2022-09-04 04:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `schoolmaster`
--

CREATE TABLE `schoolmaster` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `u_qr_id` varchar(100) DEFAULT NULL,
  `user_id` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `classes_up_to` varchar(100) DEFAULT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schoolmaster`
--

INSERT INTO `schoolmaster` (`id`, `unique_id`, `u_qr_id`, `user_id`, `school_name`, `mobile`, `email`, `address`, `logo`, `classes_up_to`, `status`, `created_at`) VALUES
(1, '683611', '15', 'sch00001', 'Growell Girls School', '9638521470', 'growell@gmail.com', 'abc building baraut', NULL, '8', '2', '2022-08-28 03:06:55'),
(2, '427695', '16', 'sch00002', 'Baraut Public School', '9876545689', 'brt@gmail.com', 'patti chaudhran baraut', NULL, '12', '2', '2022-08-28 03:09:29'),
(3, '973713', '17', 'sch00003', 'Joker Public Inter College', '9876548565', 'joker@public.com', 'joker wali gali baraut', NULL, '12', '2', '2022-08-28 08:40:33'),
(4, '467886', '18', 'sch00004', 'Test School', '7418526253', 'gs27349gs@gmail.com', 'tst address', NULL, '8', '1', '2022-08-28 10:34:12'),
(5, '219981', '19', 'sch00005', 'Digital', '9876548515', 'ramji27349@gmail.com', 'Ram ke bharoshe', NULL, '5', '1', '2022-08-28 13:44:18'),
(6, '951166', '20', 'sch00006', 'SBPS School', '1234567', 'mihiryadavofficial@gmail.com', '1234', NULL, '7', '1', '2022-08-28 13:50:47'),
(7, '965316', '21', 'sch00007', 'svt public school', '8700671965', 'ni30.dev@gmail.com', 'E165 , 3rd floor', NULL, '0', '1', '2022-08-28 14:18:59'),
(8, '491608', '22', 'sch00008', 'Vidya Sagar School', '9876541232', 'gs27349@gmail.com', '12-190 gali maliyan patti chaudran', NULL, '12', '1', '2022-08-30 03:32:05'),
(9, '724212', '23', 'sch00009', 'Shrione Public School', '9045308073', 'shrione77@gmail.com', 'Bada Jain Mandir', NULL, '12', '1', '2022-09-03 13:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `sectionName` varchar(255) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `schoolUniqueCode`, `sectionName`, `status`, `created_at`) VALUES
(1, '683611', 'A', '1', '2022-08-11 05:54:58'),
(2, '683611', 'B', '1', '2022-08-11 05:54:58'),
(3, '683611', 'C', '1', '2022-08-11 05:54:58'),
(4, '683611', 'D', '1', '2022-08-11 05:54:58'),
(5, '683611', 'E', '1', '2022-08-11 05:55:22'),
(6, '683611', 'F', '1', '2022-08-11 05:55:22'),
(7, '683611', 'G', '1', '2022-08-11 05:55:22'),
(8, '683611', 'H', '1', '2022-08-11 05:55:22'),
(9, '683611', 'I', '1', '2022-08-11 05:55:22'),
(10, '683611', 'Maths', '1', '2022-08-12 03:33:31'),
(11, '683611', 'Com', '1', '2022-08-12 03:33:31'),
(12, '683611', 'Sci', '1', '2022-08-12 03:33:31'),
(13, '683611', 'Arts', '1', '2022-08-12 03:33:31'),
(18, '724212', 'A', '1', '2022-09-03 13:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `set_digi_coin`
--

CREATE TABLE `set_digi_coin` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `digiCoin` int(11) NOT NULL,
  `user_type` enum('1','2','3','4','5') NOT NULL COMMENT '1 = student, 2 = teacher, 3 = staff, 4 = principal, 5 = school',
  `for_what` enum('1','2','3','4') NOT NULL COMMENT '1 = attendence, 2 = departure, 3 = add result',
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `set_digi_coin`
--

INSERT INTO `set_digi_coin` (`id`, `schoolUniqueCode`, `digiCoin`, `user_type`, `for_what`, `status`, `created_at`) VALUES
(2, '963852', 30, '2', '1', '1', '2022-09-01 01:39:23'),
(3, '963852', 10, '1', '1', '1', '2022-09-01 01:44:58'),
(4, '963852', 10, '1', '2', '1', '2022-09-01 01:48:29'),
(5, '963852', 20, '2', '2', '1', '2022-09-01 01:48:38'),
(6, '963852', 10, '1', '3', '1', '2022-09-01 01:48:51'),
(7, '963852', 50, '2', '3', '1', '2022-09-01 01:49:03'),
(8, '683611', 10, '1', '1', '1', '2022-09-01 02:45:48'),
(9, '683611', 30, '2', '1', '1', '2022-09-01 02:45:59'),
(10, '683611', 10, '1', '2', '1', '2022-09-01 03:00:36'),
(11, '683611', 15, '2', '2', '1', '2022-09-01 03:00:50'),
(12, '683611', 10, '1', '3', '1', '2022-09-02 01:58:23'),
(13, '683611', 30, '2', '3', '1', '2022-09-02 01:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `stateName` varchar(255) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `schoolUniqueCode`, `stateName`, `status`, `created_at`) VALUES
(1, '683611', 'Uttar Pradesh', '1', '2022-08-11 05:56:23'),
(2, '683611', 'Delhi', '1', '2022-08-11 05:56:23'),
(3, '683611', 'UttraKhand', '1', '2022-08-11 05:56:23'),
(4, '683611', 'Bihar', '1', '2022-08-11 05:56:23'),
(5, '683611', 'Haryana', '1', '2022-08-11 05:56:23'),
(6, '683611', 'Rajsthan', '1', '2022-08-21 05:10:30'),
(8, '683611', 'Gujrat', '1', '2022-08-27 08:39:29'),
(9, '683611', 'Andhra Pradesh', '1', '2022-08-28 05:22:18'),
(10, '683611', 'Arunachal Pradesh', '1', '2022-08-28 05:22:23'),
(11, '683611', 'Assam', '1', '2022-08-28 05:22:28'),
(12, '683611', 'Chhattisgarh', '1', '2022-08-28 05:22:38'),
(13, '683611', 'Goa', '1', '2022-08-28 05:22:42'),
(14, '683611', 'Gujarat', '1', '2022-08-28 05:22:47'),
(15, '683611', 'Himachal Pradesh', '1', '2022-08-28 05:22:57'),
(16, '683611', 'Jharkhand', '1', '2022-08-28 05:23:01'),
(17, '683611', 'Karnataka', '1', '2022-08-28 05:23:06'),
(18, '683611', 'Kerala', '1', '2022-08-28 05:23:11'),
(19, '683611', 'Madhya Pradesh', '1', '2022-08-28 05:23:16'),
(20, '683611', 'Maharashtra', '1', '2022-08-28 05:23:20'),
(21, '683611', 'Manipur', '1', '2022-08-28 05:23:25'),
(22, '683611', 'Meghalaya', '1', '2022-08-28 05:23:31'),
(23, '683611', 'Mizoram', '1', '2022-08-28 05:23:36'),
(24, '683611', 'Nagaland', '1', '2022-08-28 05:23:41'),
(25, '683611', 'Odisha', '1', '2022-08-28 05:23:46'),
(26, '683611', 'Punjab', '1', '2022-08-28 05:23:51'),
(27, '683611', 'Rajasthan', '1', '2022-08-28 05:23:59'),
(28, '683611', 'Sikkim', '1', '2022-08-28 05:24:04'),
(29, '683611', 'Tamil Nadu', '1', '2022-08-28 05:24:09'),
(30, '683611', 'Telangana', '1', '2022-08-28 05:24:14'),
(31, '683611', 'Tripura', '1', '2022-08-28 05:24:19'),
(32, '683611', 'Uttarakhand', '1', '2022-08-28 05:24:30'),
(33, '683611', 'West Bengal', '1', '2022-08-28 05:24:35'),
(34, '724212', 'Uttar Pradesh', '1', '2022-09-03 14:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `u_qr_id` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `class_id` varchar(100) NOT NULL,
  `section_id` varchar(100) NOT NULL,
  `roll_no` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `state_id` varchar(100) NOT NULL,
  `city_id` varchar(100) NOT NULL,
  `pincode` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `schoolUniqueCode`, `u_qr_id`, `name`, `user_id`, `class_id`, `section_id`, `roll_no`, `gender`, `mother_name`, `father_name`, `mobile`, `email`, `dob`, `address`, `state_id`, `city_id`, `pincode`, `image`, `status`, `created_at`) VALUES
(4, '683611', '', 'Saurya Jain', 'STU-00004', '1', '1', '4', '1', 'Nisha Jain', 'Sanjay Jain', '9045308073', 'sau@gmail.com', '2012-01-12', 'Bada Jain Mandir', '1', '1', NULL, 'IMG-STU-00004-630-01873773en_Masterfile.jpg', '1', '2022-08-11 07:50:06'),
(5, '683611', '', 'Shelly Sharma', 'STU00005', '14', '13', '16', '2', 'Madhu Sharma', 'Manbir Sharma', '7472583698', 'manB@shelly.com', '1999-01-05', 'Patti Chaudhran', '1', '1', 147255, 'IMG-STU-00005-155269816-happy-indian-school-girl-wearing-uniform-holding-laptop-on-lilac-background-.webp', '1', '2022-08-11 14:09:26'),
(8, '683611', '', 'Satyender Chauhan', 'STU00008', '14', '13', '16', '1', 'Bimla Devi', 'Rajiv Chauhan', '9879879854', 'sat@gmail.com', '2005-01-11', 'Nehru Road', '1', '1', 250611, 'IMG-STU00008-cute-little-indian-asian-school-boy-note-book-isolated-over-white-background-150100971.jpg', '1', '2022-08-12 03:09:09'),
(9, '683611', '', 'Preety Mishra', 'STU00009', '10', '2', '11', '2', 'Kajol Mishra', 'Ajay Mishra', '8956236932', 'prretyMih@gmail.com', '2014-03-06', 'Gurana Road Badi Masjid', '1', '2', NULL, 'IMG-STU00009-1-indian-girl-school-student-standing-EFDF0R.jpg', '1', '2022-08-12 03:15:58'),
(10, '683611', '', 'Rakhi Yadav', 'STU000010', '9', '1', '21', '2', 'Sonali Yadav', 'Anurag Yadav', '9632586523', 'arkna@gmail.com', '2015-04-28', 'Baraut Baghpat Road', '1', '3', NULL, 'IMG-STU000010-360_F_410166561_bZLl3qZRt68JCK9JDkgauSDiABqsrK4I.jpg', '1', '2022-08-12 03:24:36'),
(11, '683611', '4', 'Javad Ahmed', 'STU000011', '13', '2', '17', '1', 'Khusida Ahmed', 'Munna Ahmed', '1472583695', 'mun@gmail.com', '2006-01-05', 'House no 02/41 Patti Mirapur', '1', '1', 250677, 'IMG-STU000011-istockphoto-1342191955-612x612.jpg', '1', '2022-08-12 03:28:38'),
(12, '683611', '5', 'Jadu', 'STU000012', '13', '2', '2', '1', 'Tom', 'Kruz', '9632581475', 'abc@gmail.com', '2019-06-05', 'fake gali', '1', '1', 234567, 'IMG-STU000012-cetegory-img-17.png', '1', '2022-08-12 03:55:28'),
(13, '683611', '6', 'Ram Sharma', 'stu00001', '8', '2', '19', '1', 'Urmila Devi', 'Balkishan Sharma', '6325148547', 'rambal582@gmail.com', '2013-06-05', 'ABC Building ', '1', '2', 250609, 'img-stu00001-360_F_410166561_bZLl3qZRt68JCK9JDkgauSDiABqsrK4I.jpg', '1', '2022-08-12 12:10:13'),
(14, '683611', '7', 'Kirti Kaif', 'stu00002', '9', '2', '12', '2', 'Jhenny Kaif', 'Jhonny kaif', '8956232536', 'kriti251@gmail.com', '2012-03-06', 'Bijrol Road', '1', '1', 250611, 'img-stu00002-283-2839124_school-indian-student-png-transparent-png.png', '1', '2022-08-13 02:18:41'),
(16, '683611', '11', 'Priya', 'stu00003', '14', '13', '25', '2', 'Sunita', 'Mannu', '9633696952', 'priyam@yahoo.com', '2001-03-15', 'Delhi Haryana Road', '5', '6', 250623, 'img-stu00003-photo-1630178836733-3d61d8974258.jpg', '1', '2022-08-15 04:04:05'),
(17, '683611', '12', 'Arun', 'stu00004', '9', '3', '25', '1', 'Parul', 'Govind', '8956233625', 'abcarun@email.com', '2010-12-29', 'Gante Wali Gali', '1', '3', 369254, 'img-stu00004-360_F_270188580_YDUEwBmDIxBMvCQxkcunmEkm93VqOgqm.jpg', '1', '2022-08-15 04:14:45'),
(18, '683611', '13', 'Sameer Ahmed', 'stu00005', '3', '2', '18', '1', 'Salma Ahmed', 'Juber Ahmed', '9638521452', 'samAh@email.com', '2014-02-26', 'Near Railway Station', '1', '3', 250622, 'img-stu00005-360_F_410166561_bZLl3qZRt68JCK9JDkgauSDiABqsrK4I.jpg', '1', '2022-08-26 10:39:49'),
(20, '683611', '15', 'New School Student', 'stu00006', '3', '3', '10', '1', 'maa ka name', 'bap ka name', '7418523695', 'mabap@email.com', '2022-08-20', 'maaaaaadress', '1', '1', 123456, 'img-stu00006-630-01491566en_Masterfile.jpg', '1', '2022-08-28 04:04:42'),
(21, '951166', '16', 'Rahul Tiwari', 'stu00007', '8', '3', '34', '1', '', 'Ramesh', '1234567891', 'zuzu@mumu.com', '2022-08-19', 'reredfvergve', '2', '2', 400065, '', '1', '2022-08-28 13:58:18'),
(22, '965316', '17', 'nitish', 'stu00008', '2', '1', '10', '1', 'maharani', 'janardan', '8700671965', 'ni30.dev@gmail.com', '2007-07-28', 'E165 , 3rd floor', '2', '4', 110059, 'img-stu00008-Screenshot from 2022-08-26 20-06-52.png', '1', '2022-08-28 14:25:22'),
(23, '965316', '18', 'nisha', 'stu00009', '5', '4', '11', '2', 'maharani', 'ram', '7836928080', 'nguptani30@gmail.com', '2016-07-12', 'D65 , 3rd floor', '5', '5', 110059, 'img-stu00009-Screenshot from 2022-08-18 14-10-53.png', '1', '2022-08-28 14:27:05'),
(24, '963852', '19', 'Jaddu', 'stu000010', '3', '2', '11', '1', 'mohter name aayaga', 'baap hai', '8794562514', 'maa@baap.com', '2022-08-03', 'abc building', '1', '1', 250611, 'img-stu000010-630-07071784en_Masterfile.jpg', '1', '2022-08-29 12:41:18'),
(25, '724212', '20', 'Ridhi Jain', 'stu000011', '17', '18', '12', '2', 'Gunjan Jain', 'Pankaj Jain', '9876543215', 'ridhi@email.com', '06/01/2004', 'Bada Jain Mandir', '34', '19', 250611, 'img-stu000011-WhatsApp Image 2022-09-03 at 19.21.47.jpeg', '1', '2022-09-03 14:13:45'),
(26, '724212', '21', 'Atisya Jain', 'stu000012', '18', '18', '10', '1', 'Rubi Jain', 'Rajiv Jain', '7899878548', 'atisya@email.com', '05/14/2007', 'Bada Jain Mandir', '34', '19', 250611, 'img-stu000012-WhatsApp Image 2022-09-03 at 19.21.46.jpeg', '1', '2022-09-03 14:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `subjectName` varchar(100) NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `schoolUniqueCode`, `subjectName`, `status`, `created_at`) VALUES
(1, '683611', 'Hindi', '1', '2022-08-21 07:52:34'),
(2, '683611', 'English', '1', '2022-08-21 07:52:34'),
(3, '683611', 'Maths', '1', '2022-08-21 07:52:34'),
(4, '683611', 'Science', '1', '2022-08-21 07:52:34'),
(5, '683611', 'Social Science', '1', '2022-08-21 07:52:34'),
(6, '683611', 'Sanskrit', '1', '2022-08-21 07:52:34'),
(7, '683611', 'Physics', '1', '2022-08-21 07:52:34'),
(8, '683611', 'Chemistry', '1', '2022-08-21 07:52:34'),
(9, '683611', 'Biology', '1', '2022-08-21 07:52:48'),
(10, '683611', 'Accounts', '1', '2022-08-21 07:52:48'),
(11, '683611', 'Business Studies', '1', '2022-09-03 12:25:53'),
(12, '724212', 'Hindi', '1', '2022-09-03 13:57:30'),
(13, '724212', 'English', '1', '2022-09-03 13:57:35'),
(14, '724212', 'Maths', '1', '2022-09-03 13:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `u_qr_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `class_id` varchar(100) NOT NULL,
  `section_id` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `doj` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `state_id` varchar(100) NOT NULL,
  `city_id` varchar(100) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `schoolUniqueCode`, `u_qr_id`, `name`, `user_id`, `class_id`, `section_id`, `gender`, `mother_name`, `father_name`, `mobile`, `password`, `auth_token`, `email`, `dob`, `doj`, `address`, `state_id`, `city_id`, `pincode`, `image`, `education`, `experience`, `status`, `created_at`) VALUES
(1, '683611', '9', 'Shivani Sharma', 'tec00001', '8', '1', '2', 'Sunita Devi', 'Birju Sharma', '3216549875', '', NULL, 'shivani147@gmail.com', '1995-07-06', '2020-03-20', 'Nehru Road', '1', '1', '250611', 'img-tec00001-630-01873594en_Masterfile.jpg', NULL, NULL, '1', '2022-08-13 04:18:44'),
(2, '683611', '10', 'Arjun Kapoor', 'tec00002', '14', '13', '1', 'Bano Kapoor', 'Amrith Kapoor', '9633695214', 'B2gD9t', 'VvQ6WNop5jTXl91uLMBC8y9t1G01d2b64FUc1xa7', 'arjunK01@gmail.com', '1993-06-02', '2022-02-01', 'Near Nagar Palika', '1', '2', '250609', 'img-tec00002-Indian-school-t11791.jpg', NULL, NULL, '1', '2022-08-13 04:24:44'),
(3, '683611', '', 'Jhonny', 'tec00003', '14', '13', '1', 'Kamla ', 'Vimal', '3692582154', 'D7wySb', 'DzdFMv0OrmQqI15oke8l8b37BAytjwfsgG63PiXW', 'jhonnyjid@email.com', '2003-02-15', '2022-08-15', 'Patti Birapur', '2', '4', '110006', 'img-tec00003-portrait-young-male-teacher-background-school-blackboard-teacher-s-day-knowledge-day-back-to-school-study-159722312.jpg', NULL, NULL, '1', '2022-08-15 04:01:33'),
(4, '683611', '15', 'Kavita Sharma', 'tec00004', '1', '1', '2', 'Banno Devi', 'Mamchand Sharma', '7894561230', 'S2YDAO', NULL, 'kav@gmail.com', '1992-05-06', '2021-01-28', 'Gali MiraPur Baru Patti', '1', '1', '250611', 'img-tec00004-istockphoto-517042363-612x612.jpg', NULL, NULL, '1', '2022-08-28 03:17:57'),
(5, '683611', '16', 'new scholl teacher', 'tec00005', '13', '2', '2', 'asdf', 'asdf', '9151446619', '6Q0xT6', 'V1NfxukabrOP7y9tz7FQcisZ9DHE5TGeJnjUh62K', 'asdfasdf@sfg', '2022-08-06', '2022-08-05', 'asdfasdfsdaf', '1', '1', 'asdfasdf', 'img-tec00005-istockphoto-1139495117-612x612.jpg', NULL, NULL, '1', '2022-08-28 04:08:01'),
(6, '683611', '17', 'babita sharama', 'tec00006', '13', '2', '2', 'usha', 'prem', '9898989898', 'r63meF', 'w8P0DnL1yzakQIl4smog6N277pAe095Ub2cGxYT6', 'babita@gmail.com', '06/08/1999', '08/10/2022', 'A165 , 3rd floor', '2', '4', '110076', 'img-tec00006-Screenshot from 2022-08-18 18-35-25.png', '10th', '1', '1', '2022-08-30 14:42:38'),
(7, '965316', '18', 'babita sharama', 'tec00007', '1', '1', '2', 'usha', 'prem', '08700671965', 'Jfkyig', 'bQVR07GX3wfYPO9yjueA778mzSv8NcLnJ2o9BH0U', 'nguptani30@gmail.com', '06/17/2020', '08/06/2022', 'E165 , 3rd floor', '2', '4', '110059', 'img-tec00007-Screenshot from 2022-08-18 14-17-24.png', NULL, NULL, '1', '2022-08-30 14:47:57'),
(8, '724212', '19', 'Gaurav Jain', 'tec00008', '17', '18', '1', 'Krishna Jain', 'Sushil Kumar Jain', '9045308073', 'E60VYG', '7APlIKeyNV48gfBFO1ntCXdrpGQx1SJUmRE5v5ca', 'gaurav77@gmail.com', '07/28/1993', '11/01/2021', 'Bada Jain Mandir', '34', '19', '250611', 'img-tec00008-WhatsApp Image 2022-08-27 at 11.56.35.jpeg', NULL, NULL, '1', '2022-09-03 14:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `teachersubjects`
--

CREATE TABLE `teachersubjects` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `teacher_id` varchar(100) NOT NULL,
  `subject_ids` json NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachersubjects`
--

INSERT INTO `teachersubjects` (`id`, `schoolUniqueCode`, `teacher_id`, `subject_ids`, `status`, `created_at`) VALUES
(2, '', '2', '[\"10\", \"9\", \"8\", \"7\", \"5\", \"2\"]', '1', '2022-08-22 02:09:13'),
(3, '', '3', '[\"8\", \"6\", \"2\"]', '1', '2022-08-22 03:40:05'),
(5, '', '1', '[\"8\", \"3\"]', '1', '2022-08-22 03:42:43'),
(8, '683611', '5', '[\"9\", \"8\", \"6\"]', '4', '2022-08-28 05:39:05'),
(9, '965316', '7', '[\"9\", \"8\", \"7\", \"4\", \"2\"]', '1', '2022-08-30 14:53:15'),
(10, '683611', '6', '[\"11\"]', '1', '2022-09-03 12:26:08'),
(11, '724212', '8', '[\"14\", \"13\", \"12\"]', '1', '2022-09-03 14:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `tthours`
--

CREATE TABLE `tthours` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tthours`
--

INSERT INTO `tthours` (`id`, `schoolUniqueCode`, `start_time`, `end_time`, `status`, `created_at`) VALUES
(1, '', '08:00:00', '08:30:00', '1', '2022-08-21 08:43:59'),
(3, '', '08:30:00', '09:00:00', '1', '2022-08-21 08:50:01'),
(4, '', '09:00:00', '09:30:00', '1', '2022-08-21 08:50:25'),
(5, '', '10:00:00', '10:30:00', '1', '2022-08-21 08:51:13'),
(6, '', '10:30:00', '11:00:00', '1', '2022-08-21 08:51:31'),
(7, '', '11:30:00', '12:00:00', '1', '2022-08-21 08:51:53'),
(8, '', '12:30:00', '13:00:00', '1', '2022-08-21 08:52:11'),
(9, '', '13:00:00', '13:30:00', '1', '2022-08-21 08:52:35'),
(10, '683611', '08:00:00', '08:30:00', '1', '2022-08-28 05:17:06'),
(11, '683611', '08:30:00', '09:00:00', '1', '2022-08-28 05:51:39'),
(12, '965316', '06:00:00', '06:30:00', '1', '2022-08-28 14:36:07'),
(13, '965316', '07:00:00', '07:10:00', '1', '2022-08-28 14:36:57'),
(14, '965316', '06:30:00', '07:00:00', '1', '2022-08-28 14:37:31'),
(15, '683611', '09:00:00', '09:30:00', '1', '2022-08-30 03:24:06'),
(16, '965316', '07:30:00', '08:00:00', '1', '2022-08-30 14:51:50'),
(17, '724212', '08:00:00', '09:00:00', '1', '2022-09-03 13:58:11'),
(18, '724212', '09:00:00', '10:00:00', '1', '2022-09-03 13:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(10) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `auth_token` varchar(100) DEFAULT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `schoolUniqueCode`, `name`, `email`, `password`, `user_type`, `salt`, `auth_token`, `status`, `created_at`) VALUES
(3, '683611', 'Admin', 'admin@email.com', 'b0FsYk1ZMXRMekQreDg1NDlXU1B3UT09', 'Admin', 'UV99RQ4wa6by8TIs5m48Od5EWiMvtGSzZ1kClJuj', NULL, '1', '2022-08-24 11:20:57'),
(4, '683611', 'Staff', 'staff@email.com', 'aWFWdnlQZ3JabW15Q3dGY3dnZnZZdz09', 'Staff', 'btSAMFfLY8wdu0iUXGcyOn3hm65vEIjpWrazDQ2J', NULL, '1', '2022-08-24 12:36:34'),
(5, '683611', 'Principle', 'principle@email.com', 'eDZOelBweFNvU1M0dnkyZjlUMERSQT09', 'Principal', 'nJjA5v1w8194uOP7Cg6sri9SmGW2qRZ0UHMQbVkT', NULL, '1', '2022-08-24 12:38:12'),
(7, '683611', 'Pappu Staff', 'pappu@staff.com', 'SzRFM2hJSEZ2cVMwVnBtWkFLUTNpZz09', 'Staff', 'ANUb7FTJW5f2OiQI9qr30sZzPvktCcn0Djpxl1G6', NULL, '1', '2022-08-24 12:49:40'),
(11, '683611', 'Testing User', 'test@email.com', 'MTFhVEd3K0JnVGpKcnA1OWtSLzFrUT09', 'Principal', 'iQFPs9fILB4ewxWh1grbtmH4XzJuCK5qV38YOo0R', NULL, '1', '2022-08-25 02:42:21'),
(14, '683611', 'Principle Test', 'admin@koel.com', 'WXh1YysyVGZJbDlFM2RkOVo5dERQdz09', 'Principal', 'vmFx3PwLIdTGErn4sKM0lb7qQ12YyXt1HeiZJ5Wp', NULL, '1', '2022-08-25 02:51:10'),
(16, '973713', 'Joker Public Admin', 'joker@public.com', 'ZWN1a0dUOE5IWmlUUlAwaWs5ZFZ1Zz09', 'Admin', '47W6zb8e5Ho35QRnrKCsIG9Z8YwldPcTha9iN1OA', NULL, '1', '2022-08-28 08:46:57'),
(17, '963852', 'Super CEO Account', 'superCEO@digitalfied.in', 'NDd6REtwV3Z3TWgrZG9iSmE2UTk0UT09', 'SuperCEO', 'dLbum9UfeRZxwv27EK1PqGyA5WOQJo46Ih0siX0k', NULL, '1', '2022-08-28 09:47:47'),
(32, '467886', 'Admin', 'Admin@email.com', 'VkJ5elRrY01za05EWS9zZnowdklnZz09', 'Admin', 'egTQftbmXFI354CLB2oGARV2P5y70hsz3d69u4W8', NULL, '1', '2022-08-28 13:26:22'),
(33, '467886', 'Staff', 'Staff@email.com', 'ekRmZGRhS0NCd2lWekNsdERPK2pVQT09', 'Staff', '5XYO2CiD64vmQju5pJK92Elt88VxyLsrhnbWok5q', NULL, '1', '2022-08-28 13:26:22'),
(34, '467886', 'Principal', 'Principal@email.com', 'MTJLb05nQ1BnQ3NucjN1QW5ZSTJhZz09', 'Principal', 'nN29AaHPhLsbcuzolV5F1U0Rk78edS3K9riTpEIW', NULL, '1', '2022-08-28 13:26:22'),
(35, '467886', 'Test School Admin', 'gs27349gs@gmail.com', 'M0JVUzJtaVlBRzVNb2Q5cW4ya3JKQT09', 'Admin', '5wcOG6Q4Il90o1PyeEX6C7Ngb2dx2YUvAFiZpj3R', NULL, '1', '2022-08-28 13:26:22'),
(36, '467886', 'Test Kara', 'testkaraadmin@test.com', 'bk9FVjBydXZkSHlyZ1g3ZlUxclpQdz09', 'Admin', 'IR82YkgBipD3FNLSvx0aeOsjhXA4Pq2r50E6GmnT', NULL, '1', '2022-08-28 13:31:15'),
(37, '219981', 'Admin', 'Admin@email.com', 'MHhET3paRUhuVHFOSVpqNG1HTHNqdz09', 'Admin', 'egP0Q9VnKkO4LRGDlFX3cW31r4yEm0foCNb7wIdB', NULL, '1', '2022-08-28 13:45:57'),
(38, '219981', 'Staff', 'Staff@email.com', 'c1pDK2NVSDVRMlMrcjAySEVFa1ZpUT09', 'Staff', 'k5p5s8KFNR79QDqGTiom0EfSdWvy7jL1OrA12xcI', NULL, '1', '2022-08-28 13:45:57'),
(39, '219981', 'Principal', 'Principal@email.com', 'WTdDYk84TmZXdWZ2NEFZL2dXWVZtZz09', 'Principal', 'tvXNdIFp5mJB6EbqMG3ykDcluKoS9HCAT615UPf4', NULL, '1', '2022-08-28 13:45:57'),
(40, '219981', 'Digital Admin', 'ramji27349@gmail.com', 'dTlYS3FGaml6a2lveThHWEF4OEt1UT09', 'Admin', 'R20T6ishAcav5411YDqOzW47E2Bm3X9IFyLSjxZH', NULL, '1', '2022-08-28 13:45:57'),
(41, '951166', 'Admin', 'Admin@email.com', 'QmlKN3M0T1UzODFRc3JPdE5VbE1EZz09', 'Admin', '295aC8VJMi027nlUKDm1IBkt3r2SgWXo4eEYu50f', NULL, '1', '2022-08-28 13:51:40'),
(42, '951166', 'Staff', 'Staff@email.com', 'Q2x5U08vTUQvUmpPL0M1THZYZE5CUT09', 'Staff', 'c4k5HTe7QEFj68CUd0Yv2g1yOZiVuMpKmnfaSLAr', NULL, '1', '2022-08-28 13:51:40'),
(43, '951166', 'Principal', 'Principal@email.com', 'dFQ1MkdnTXFBVGpmTDFnK3pOY3Uzdz09', 'Principal', 'zUElrSKNYxdOtVQ8ajvi78hgu44C6oHf0W5pyZ9M', NULL, '1', '2022-08-28 13:51:40'),
(44, '951166', 'SBPS School Admin', 'mihiryadavofficial@gmail.com', 'MU9IdzZ0MWd3SVUxa1dIbk92elJjZz09', 'Admin', 'bmF4Ilji1f2hZV0YsvJ8979k1ErpedR6D84TN5OU', NULL, '1', '2022-08-28 13:51:40'),
(45, '965316', 'Admin', 'Admin@email.com', 'MFpORisvUmFnT2E2QzFvNEhGVFpyZz09', 'Admin', '7QXHl02wZaNpFf3dxtniW0z0VOh5RkoyIE5bMG7s', NULL, '1', '2022-08-28 14:21:26'),
(46, '965316', 'Staff', 'Staff@email.com', 'aTVDWFNpTSsrNVBVQXhQWWFMZDduUT09', 'Staff', 'e8U2v5YiySj74qw3AWRnr5NLoBhMVKbgG869kFdp', NULL, '1', '2022-08-28 14:21:26'),
(47, '965316', 'Principal', 'Principal@email.com', 'eklnUHJZTnArRDMzY2dMNm9sVmkyQT09', 'Principal', 'SCrsL03y72X6exYt85wHJ89vqzTQmgW7j6VGbN0A', NULL, '1', '2022-08-28 14:21:26'),
(48, '965316', 'svt public school Admin', 'ni30.dev@gmail.com', 'ak55K2paRy8zczJTdkxwejJMTmt5dz09', 'Admin', '5G22kpaA2xiY8LCo9fd9zb3hjU9seFgw7cX6VItQ', NULL, '1', '2022-08-28 14:21:26'),
(49, '965316', 'rakesh', 'ca@rajesh.com', 'SzhoUmoyazZyT05CbFkzbVRDalZOUT09', 'Staff', 'uR1K5jG5oVUB40E4teAIWvfFP99MTprN73i2g6h1', NULL, '1', '2022-08-28 14:45:58'),
(50, '491608', 'Admin', 'Admin@email.com', 'L0RVV1ZiU2VuLzk5dHl6VE1pd1hSZz09', 'Admin', '4ZQgHYu0XCL9MitF27yWvUPz0Dm3xOVa6815qnoh', NULL, '1', '2022-08-30 03:33:14'),
(51, '491608', 'Staff', 'Staff@email.com', 'bXZ6Q05aTU9OemVrcDhzcyt4K3AxZz09', 'Staff', '5wiKl6L93JWg72VIFDPqcpSvY71k1fTCy8MnAsxe', NULL, '1', '2022-08-30 03:33:14'),
(52, '491608', 'Principal', 'Principal@email.com', 'bzdTTWJockNnMlhIUXp2VFNWRzB6UT09', 'Principal', 'Tht0Og20SDI0pq3xHdPEeBcA718bjG1wY6y9VoaZ', NULL, '1', '2022-08-30 03:33:14'),
(53, '491608', 'Vidya Sagar School Admin', 'gs27349@gmail.com', 'cTBYMlRhVnpkakhJcUNrYWdKZ2E2Zz09', 'Admin', 'QzY65Bt7ic9hMN4y0bDGIlZLrv5qXa767S2CeW8F', NULL, '1', '2022-08-30 03:33:14'),
(54, '724212', 'Admin', 'Admin@email.com', 'YzNWeGtNWFFPK3d3NWNWU2VmdjJFdz09', 'Admin', '5CiDL7hv1JqTQY7jSOcRd1p3t6Uka7fwZ6M0xX4A', NULL, '1', '2022-09-03 13:48:43'),
(55, '724212', 'Staff', 'Staff@email.com', 'R1lCNm42bW5KWmtuVm1RU1pFNjJ3UT09', 'Staff', 'aWRKFP3nxO08Y4JB6254i5MIcVgQyqrpN46Hv61X', NULL, '1', '2022-09-03 13:48:43'),
(56, '724212', 'Principal', 'Principal@email.com', 'N2hMZUFQN29Kd0xBQ3ZtdzFnQVl1dz09', 'Principal', 'Fe5x0XmAb8PZdipRYfK9J73rChOG8wUlB0MS6Lt4', NULL, '1', '2022-09-03 13:48:43'),
(57, '724212', 'Shrione Public School Admin', 'shrione77@gmail.com', 'elZnd3BTR0ljWGhlKzRPOFhtdEJzQT09', 'Admin', 'VXMrUvb0ehBqTY62D45RHGNP2Fk94u4go7J3ifE3', NULL, '1', '2022-09-03 13:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `week`
--

CREATE TABLE `week` (
  `id` int(11) NOT NULL,
  `weekName` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `week`
--

INSERT INTO `week` (`id`, `weekName`, `status`, `created_at`) VALUES
(1, 'Monday', '1', '2022-08-21 08:30:28'),
(2, 'Tuesday', '1', '2022-08-21 08:30:28'),
(3, 'Wednesday', '1', '2022-08-21 08:30:28'),
(4, 'Thursday', '1', '2022-08-21 08:30:28'),
(5, 'Friday', '1', '2022-08-21 08:30:28'),
(6, 'Saturday', '1', '2022-08-21 08:30:28'),
(9, 'Sunday', '1', '2022-08-27 08:47:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_panel_menu`
--
ALTER TABLE `admin_panel_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendence`
--
ALTER TABLE `attendence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classshedule`
--
ALTER TABLE `classshedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departure`
--
ALTER TABLE `departure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feesforstudent`
--
ALTER TABLE `feesforstudent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `get_digi_coin`
--
ALTER TABLE `get_digi_coin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift`
--
ALTER TABLE `gift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_work`
--
ALTER TABLE `home_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `month`
--
ALTER TABLE `month`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_menu_permission`
--
ALTER TABLE `panel_menu_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_notification`
--
ALTER TABLE `push_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qrcode_schools`
--
ALTER TABLE `qrcode_schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcodeUrl` (`qrcodeUrl`);

--
-- Indexes for table `qrcode_students`
--
ALTER TABLE `qrcode_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcodeUrl` (`qrcodeUrl`);

--
-- Indexes for table `qrcode_teachers`
--
ALTER TABLE `qrcode_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcodeUrl` (`qrcodeUrl`);

--
-- Indexes for table `redeem_gifts`
--
ALTER TABLE `redeem_gifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schoolmaster`
--
ALTER TABLE `schoolmaster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `set_digi_coin`
--
ALTER TABLE `set_digi_coin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile` (`mobile`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile` (`mobile`);

--
-- Indexes for table `teachersubjects`
--
ALTER TABLE `teachersubjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tthours`
--
ALTER TABLE `tthours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `week`
--
ALTER TABLE `week`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_panel_menu`
--
ALTER TABLE `admin_panel_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `attendence`
--
ALTER TABLE `attendence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `classshedule`
--
ALTER TABLE `classshedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departure`
--
ALTER TABLE `departure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feesforstudent`
--
ALTER TABLE `feesforstudent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `get_digi_coin`
--
ALTER TABLE `get_digi_coin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `gift`
--
ALTER TABLE `gift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `home_work`
--
ALTER TABLE `home_work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `month`
--
ALTER TABLE `month`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `panel_menu_permission`
--
ALTER TABLE `panel_menu_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `push_notification`
--
ALTER TABLE `push_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qrcode_schools`
--
ALTER TABLE `qrcode_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `qrcode_students`
--
ALTER TABLE `qrcode_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `qrcode_teachers`
--
ALTER TABLE `qrcode_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `redeem_gifts`
--
ALTER TABLE `redeem_gifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `result`
--
ALTER TABLE `result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `schoolmaster`
--
ALTER TABLE `schoolmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `set_digi_coin`
--
ALTER TABLE `set_digi_coin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teachersubjects`
--
ALTER TABLE `teachersubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tthours`
--
ALTER TABLE `tthours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `week`
--
ALTER TABLE `week`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
