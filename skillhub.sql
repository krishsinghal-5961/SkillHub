-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Dec 13, 2024 at 04:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recruitment_id` int(11) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `user_id`, `recruitment_id`, `applied_at`) VALUES
(15, 1, 10, '2024-11-10 08:23:13'),
(16, 1, 11, '2024-11-11 06:40:16'),
(17, 1, 13, '2024-11-11 14:07:08'),
(18, 1, 12, '2024-11-11 14:07:14'),
(19, 1, 14, '2024-12-13 14:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `connection_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `connection_user_id` int(11) NOT NULL,
  `connected_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`connection_id`, `user_id`, `connection_user_id`, `connected_at`) VALUES
(42, 14, 11, '2024-11-10 08:11:17'),
(43, 14, 5, '2024-11-10 08:11:18'),
(44, 14, 3, '2024-11-10 08:11:20'),
(45, 14, 8, '2024-11-10 08:11:21'),
(46, 14, 1, '2024-11-10 08:11:24'),
(48, 14, 10, '2024-11-10 08:11:31'),
(49, 14, 6, '2024-11-10 08:11:33'),
(50, 1, 8, '2024-11-10 08:14:37'),
(51, 1, 5, '2024-11-10 08:14:39'),
(52, 1, 3, '2024-11-10 08:14:40'),
(53, 1, 14, '2024-11-10 08:14:42'),
(54, 1, 10, '2024-11-10 08:14:45'),
(55, 1, 11, '2024-11-10 08:16:20'),
(56, 10, 1, '2024-11-10 08:20:53'),
(57, 10, 5, '2024-11-10 08:21:02'),
(58, 10, 3, '2024-11-10 08:21:05'),
(59, 10, 4, '2024-11-10 08:21:07'),
(60, 10, 14, '2024-11-10 08:21:09'),
(61, 8, 1, '2024-11-10 08:26:08'),
(62, 8, 14, '2024-11-10 08:26:11'),
(63, 8, 10, '2024-11-10 08:26:13'),
(64, 1, 16, '2024-11-11 06:43:24'),
(65, 11, 1, '2024-11-12 08:20:49'),
(66, 1, 4, '2024-12-13 14:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `coursematerials`
--

CREATE TABLE `coursematerials` (
  `material_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursematerials`
--

INSERT INTO `coursematerials` (`material_id`, `course_id`, `title`, `file_path`, `uploaded_at`) VALUES
(4, 1, 'Notes', 'notes.pdf', '2024-11-10 16:48:26'),
(5, 1, 'Assignment', 'assignment.pdf', '2024-11-10 16:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_name` varchar(255) DEFAULT NULL,
  `duration_weeks` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `title`, `description`, `instructor_name`, `duration_weeks`, `created_at`, `progress`) VALUES
(1, 'Advanced Javascript', 'This course covers advanced concepts in JavaScript, including ES6 features, asynchronous programming, and modern frameworks.\r\n\r\n', 'Sunil Sharma', 5, '2024-11-02 18:52:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `progress_percentage` decimal(5,2) DEFAULT 0.00,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `user_id`, `course_id`, `progress_percentage`, `enrolled_at`) VALUES
(23103370, 10, 1, 0.00, '2024-11-11 00:20:07'),
(23103373, 1, 1, 0.00, '2024-11-09 14:40:26');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `liked_at`) VALUES
(14, 15, 1, '2024-11-10 16:20:24'),
(15, 16, 1, '2024-11-10 16:20:26'),
(16, 15, 10, '2024-11-11 04:49:31'),
(17, 19, 10, '2024-11-11 04:49:41'),
(18, 18, 10, '2024-11-11 04:49:43'),
(19, 17, 10, '2024-11-11 04:49:47'),
(20, 19, 1, '2024-11-11 06:41:31'),
(21, 17, 1, '2024-11-11 14:08:22'),
(22, 18, 1, '2024-11-11 14:08:36'),
(23, 20, 1, '2024-12-13 14:49:33'),
(24, 21, 1, '2024-12-13 14:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `content`, `sent_at`) VALUES
(25, 14, 1, 'Hi Krish! I am Pritish. I recently joined on SkillHub.', '2024-11-10 08:12:08'),
(26, 1, 14, 'Hey Pritish! Great to know.', '2024-11-10 08:17:06'),
(27, 10, 1, 'Hi Krish!', '2024-11-11 04:53:27'),
(28, 10, 1, 'Excited to join the SkillHub team', '2024-11-11 04:53:47'),
(29, 1, 10, 'Hello Vaibhav! Looking forward to working with you.', '2024-11-11 05:06:27'),
(30, 1, 8, 'Hi Jatin Yadav! I would like to invite you to work on SkillHub as a Backend Developer.', '2024-11-11 05:07:39'),
(31, 1, 14, 'hi', '2024-11-11 06:45:43'),
(32, 1, 11, 'Hello!', '2024-11-12 08:20:12'),
(33, 1, 11, 'Kaisa h?\r\n', '2024-11-12 08:20:27'),
(34, 11, 1, 'THEEK HU', '2024-11-12 08:21:09'),
(35, 1, 8, 'Great to meet you!\r\nLooking forward working with you.', '2024-12-13 14:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` enum('system','message','course','connection') DEFAULT 'system',
  `read_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `type`, `read_status`, `created_at`) VALUES
(18, 11, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:17'),
(19, 5, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:18'),
(20, 3, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:20'),
(21, 8, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:21'),
(22, 1, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:24'),
(24, 10, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:31'),
(25, 6, 'Pritish Negi added you as a connection.', '', 0, '2024-11-10 08:11:33'),
(26, 8, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:14:37'),
(27, 5, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:14:39'),
(28, 3, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:14:40'),
(29, 14, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:14:42'),
(30, 10, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:14:45'),
(31, 11, 'Krish Singhal added you as a connection.', '', 0, '2024-11-10 08:16:20'),
(32, 1, 'Vaibhav added you as a connection.', '', 0, '2024-11-10 08:20:53'),
(33, 5, 'Vaibhav added you as a connection.', '', 0, '2024-11-10 08:21:02'),
(34, 3, 'Vaibhav added you as a connection.', '', 0, '2024-11-10 08:21:05'),
(35, 4, 'Vaibhav added you as a connection.', '', 0, '2024-11-10 08:21:07'),
(36, 14, 'Vaibhav added you as a connection.', '', 0, '2024-11-10 08:21:09'),
(37, 1, 'Jatin Yadav added you as a connection.', '', 0, '2024-11-10 08:26:08'),
(38, 14, 'Jatin Yadav added you as a connection.', '', 0, '2024-11-10 08:26:11'),
(39, 10, 'Jatin Yadav added you as a connection.', '', 0, '2024-11-10 08:26:13'),
(40, 16, 'Krish Singhal added you as a connection.', '', 0, '2024-11-11 06:43:24'),
(41, 1, 'ANSH TYAGI added you as a connection.', '', 0, '2024-11-12 08:20:49'),
(42, 4, 'Krish Singhal added you as a connection.', '', 0, '2024-12-13 14:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_content`, `created_at`, `post_title`) VALUES
(15, 1, 'We\'re building SkillHub, a dynamic platform for skill-sharing and collaboration. If you\'re passionate about making a real impact, we’re looking for dedicated individuals to help bring this vision to life!\r\n\r\nOpen Positions:\r\nTeam Lead 🌟\r\n\r\nRole: Lead, guide, and inspire our team to achieve project milestones and drive the platform’s growth.\r\nSkills: Strong leadership, strategic planning, and communication abilities.\r\nFrontend Developer 🎨\r\n\r\nRole: Design intuitive and responsive interfaces, ensuring a seamless user experience.\r\nSkills: Proficiency in HTML, CSS, JavaScript, and frameworks like React or Angular.\r\nBackend Developer 🔧\r\n\r\nRole: Develop and maintain robust backend systems, handling data management and server logic.\r\nSkills: Expertise in server-side languages (Node.js, Python, etc.), database management, and API integration.\r\nCode Debugging and Enhancement Specialist 🛠️\r\n\r\nRole: Ensure quality code, troubleshoot issues, and enhance platform features.\r\nSkills: Strong debugging and optimization skills, familiarity with CI/CD, and a detail-oriented approach.\r\nWhy Join SkillHub?\r\nCollaborative Environment: Work with talented individuals who are committed to innovation.\r\nSkill Growth: Expand your knowledge and sharpen your expertise in a real-world project.\r\nImpactful Work: Be part of a platform that empowers communities through skill-sharing.\r\nIf you\'re ready to contribute to an exciting project and grow with us, let\'s connect! Drop a message or apply in the comments below.', '2024-11-10 08:19:27', '🚀 Join the SkillHub Team! 🚀'),
(16, 1, 'Thrilled to step into the role of Team Lead for SkillHub, a game-changing platform for skill-sharing and collaboration! Leading such an amazing team of talented professionals is a dream. We’re gearing up to create something impactful and innovative. Stay tuned as we make SkillHub a go-to place for connecting and growing skills in our community! #SkillHub #Leadership #SkillSharing', '2024-11-10 08:19:48', '🌟  🚀 Excited to Lead the SkillHub Project! 🚀'),
(17, 10, 'Proud to announce that I’ve joined SkillHub as a Frontend Developer! I\'m diving into designing a smooth, engaging, and accessible user interface to make the platform easy and enjoyable for everyone. Excited to work with such a dedicated team and bring this platform to life! Let’s create something special together. #FrontendDevelopment #UIUX #SkillHub', '2024-11-10 08:20:47', '🎉 New Beginnings with SkillHub! 🎉'),
(18, 8, 'Happy to share that I’ve joined the SkillHub team as a Backend Developer! I’ll be working behind the scenes to build a robust and scalable platform, ensuring seamless data flow and security. Looking forward to collaborating with this fantastic team and delivering a powerful platform for skill-sharing. #BackendDevelopment #Coding #SkillHub', '2024-11-10 08:25:51', '💻 Pumped to Join SkillHub! 💻'),
(19, 14, 'Excited to announce my role as Code Debugging and Enhancement Specialist at SkillHub! My focus will be on optimizing code quality, resolving issues, and ensuring everything runs smoothly for our users. Thrilled to be part of a team dedicated to making a difference through technology and collaboration. Let\'s make SkillHub the best it can be! #CodeOptimization #Debugging #SkillHub', '2024-11-10 08:27:23', '🔍 Joining SkillHub as Code Debugging and Enhancement Specialist! 🔍'),
(20, 1, 'xss', '2024-11-11 06:44:47', 'ke'),
(21, 1, 'Post for my video shoot', '2024-12-13 14:49:51', 'Hello there!');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `user_id`, `project_title`, `project_description`, `created_at`) VALUES
(18, 1, 'StreetSense', 'Traffic Management System on Data Structure and C++', '2024-11-07 07:08:20'),
(19, 1, 'Smart Retail: Utilizing Machine Learning for Demand Forecasting, Price Prediction and Inventory Management', 'Research Paper in CICN 2024 Indore', '2024-11-10 17:05:49'),
(20, 1, 'SkillHub', 'Website for JIIT collaboration', '2024-12-13 14:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `recruitments`
--

CREATE TABLE `recruitments` (
  `recruitment_id` int(11) NOT NULL,
  `skill_needed` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `duration` int(11) NOT NULL,
  `people_required` int(11) NOT NULL,
  `applications` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp GENERATED ALWAYS AS (`created_at` + interval `duration` day) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitments`
--

INSERT INTO `recruitments` (`recruitment_id`, `skill_needed`, `description`, `duration`, `people_required`, `applications`, `created_at`) VALUES
(10, 'Frontend Development (HTML, CSS, JavaScript)', 'We need a specialist to debug, optimize, and enhance the SkillHub platform\'s codebase. The role involves troubleshooting issues, improving performance, and ensuring code quality and reliability across the app.\r\n\r\n', 44, 1, 1, '2024-11-10 08:23:02'),
(11, 'Frontend Development (HTML, CSS, JavaScript)', 'We are looking for a frontend developer to design and implement user-friendly interfaces for SkillHub, our skill-sharing platform. This role involves creating responsive layouts, developing interactive elements, and optimizing for both light and dark modes to enhance the user experience.\r\n\r\n', 60, 1, 1, '2024-11-10 08:23:38'),
(12, 'Backend Development (Node.js, MongoDB)', 'Seeking a backend developer to build and maintain server-side functionality for SkillHub. Responsibilities include setting up databases, API integration, and managing data flow to ensure a secure and scalable platform.\r\n\r\n', 75, 1, 1, '2024-11-10 08:23:59'),
(13, 'Code Debugging and Enhancement', 'We need a specialist to debug, optimize, and enhance the SkillHub platform\'s codebase. The role involves troubleshooting issues, improving performance, and ensuring code quality and reliability across the app.\r\n\r\n', 45, 1, 1, '2024-11-10 08:24:31'),
(14, 'AI', 'SkillHub AI', 45, 5, 1, '2024-12-13 14:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `skill_name` varchar(255) NOT NULL,
  `skill_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `user_id`, `skill_name`, `skill_level`) VALUES
(4, 10, 'c', NULL),
(9, 1, 'Cpp', NULL),
(10, 1, 'Java', NULL),
(11, 1, 'Machine Learning', NULL),
(12, 1, 'AI', NULL),
(14, 1, 'Writing', NULL),
(15, 14, 'Java', NULL),
(16, 14, 'Python', NULL),
(17, 8, 'PHP', NULL),
(18, 8, 'C++', NULL),
(19, 8, 'C', NULL),
(20, 8, 'DSA', NULL),
(21, 10, 'Writing', NULL),
(22, 10, 'C++', NULL),
(23, 10, 'DSA', NULL),
(25, 16, 'aa', NULL),
(26, 1, 'C', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Enrolment` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Course` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`Enrolment`, `Name`, `Age`, `Course`) VALUES
(101, 'Alice', 23, 'CS'),
(103, 'Charlie', 22, 'Physics'),
(104, 'David', 24, 'CS'),
(105, 'Eve', 21, 'Chemistry'),
(106, 'Frank', 20, 'Biology'),
(107, 'Grace', 23, 'Math'),
(109, 'Isaac', 24, 'Engineering'),
(110, 'Jack', 22, 'DBW');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `enrollment` varchar(8) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `enrollment`, `email`, `password_hash`, `full_name`, `profile_image`, `last_activity`, `created_at`) VALUES
(1, '23103373', '23103373@mail.jiit.ac.in', '$2y$10$t7EIRCj7LvSjpB3qj41G8eL3qeC.UTtosUO1Qve1WobswoRGRJqgG', 'Krish Singhal', 'krish.jpg', '2024-11-10 17:05:01', '2024-11-02 09:27:41'),
(2, '24010001', 'john.doe@mail.jiit.ac.in', 'hashed_password_1', 'John Doe', '4b16a1ca82e4831d7c478f3ddfe136ab5f94f6c2.jpg', '2024-11-04 06:27:10', '2024-11-02 09:32:32'),
(3, '24010002', 'jane.smith@mail.jiit.ac.in', 'hashed_password_2', 'Jane Smith', '4a3558d835e1354d984311ca279271d5e853ef97.jpg', '2024-11-04 06:29:39', '2024-11-02 09:32:32'),
(4, '24010003', 'michael.johnson@mail.jiit.ac.in', 'hashed_password_3', 'Michael Johnson', '7c2b56f7a072fa54b4f529abe5190f3c9e87b739.jpg', '2024-11-04 06:30:33', '2024-11-02 09:32:32'),
(5, '24010004', 'emily.davis@mail.jiit.ac.in', 'hashed_password_4', 'Emily Davis', '627f44d9410dca0c9d229bd0b7d1d8fb1240619e.jpg', '2024-11-04 06:31:20', '2024-11-02 09:32:32'),
(6, '24010005', 'william.brown@mail.jiit.ac.in', 'hashed_password_5', 'William Brown', '408457de145d04d40a830792ee904db1a1f29858.jpg', '2024-11-04 06:31:42', '2024-11-02 09:32:32'),
(8, '23103215', '23103215@mail.jiit.ac.in', '$2y$10$Hl/PXXlXfE/pYX5LdIESJuP.iGHgqFaNmX96q56UmLA.38vERTA/m', 'Jatin Yadav', 'jatin.jpg', '2024-11-10 17:05:11', '2024-11-02 14:13:12'),
(10, '23103370', '23103370@mail.jiit.ac.in', '$2y$10$6y37HUh/x92rIaTDesurA.807yrUFZI0PFY/FCVuDxMkt9oYN1yTi', 'Vaibhav Singh', 'vaibhav.jpg', '2024-11-10 16:56:18', '2024-11-03 07:29:01'),
(11, '23802005', '23802005@mail.jiit.ac.in', '$2y$10$UrI8CeePzGZx0vZRium0mesrbvI7F4pKrxhKBuoRb2pp1g7UKdDt.', 'ANSH TYAGI', 'de8e5678325769be5f85d310513a1d42082c16ac.jpg', '2024-11-04 06:38:55', '2024-11-04 06:37:29'),
(14, '23103244', '23103244@mail.jiit.ac.in', '$2y$10$hUCCMfG4htP9pdXIAWf4wO6TgEA5OQ1XNzc5jLCylgFwMd/UmTYW6', 'Pritish Negi', 'pritish.jpg', '2024-11-10 17:22:33', '2024-11-10 08:10:48'),
(16, '23103214', '23103214@mail.jiit.ac.in', '$2y$10$gwxFNsnliZRdyJiHsZEyu.fXQs9YFaGWtCcx.dn3FEvjxPbJwIBpS', 'Aayansh Jain', '4a3558d835e1354d984311ca279271d5e853ef97.jpg', '2024-11-11 06:38:36', '2024-11-11 06:38:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`recruitment_id`),
  ADD KEY `recruitment_id` (`recruitment_id`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`connection_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `connection_user_id` (`connection_user_id`);

--
-- Indexes for table `coursematerials`
--
ALTER TABLE `coursematerials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recruitments`
--
ALTER TABLE `recruitments`
  ADD PRIMARY KEY (`recruitment_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`Enrolment`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `enrollment` (`enrollment`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
  MODIFY `connection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `coursematerials`
--
ALTER TABLE `coursematerials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23103374;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `recruitments`
--
ALTER TABLE `recruitments`
  MODIFY `recruitment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`recruitment_id`) REFERENCES `recruitments` (`recruitment_id`) ON DELETE CASCADE;

--
-- Constraints for table `connections`
--
ALTER TABLE `connections`
  ADD CONSTRAINT `connections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `connections_ibfk_2` FOREIGN KEY (`connection_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `coursematerials`
--
ALTER TABLE `coursematerials`
  ADD CONSTRAINT `coursematerials_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
