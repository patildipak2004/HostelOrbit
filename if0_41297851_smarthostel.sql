-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql206.infinityfree.com
-- Generation Time: Mar 06, 2026 at 07:26 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41297851_smarthostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission_applications`
--

CREATE TABLE `admission_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `last_passout` varchar(20) NOT NULL,
  `last_passout_percentage` decimal(5,2) NOT NULL,
  `college_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `percentage_12th` decimal(5,2) NOT NULL,
  `percentage_10th` decimal(5,2) NOT NULL,
  `parent_name` varchar(100) NOT NULL,
  `parent_mobile` varchar(15) NOT NULL,
  `parent_occupation` varchar(100) DEFAULT NULL,
  `parent_address` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admission_applications`
--

INSERT INTO `admission_applications` (`id`, `user_id`, `full_name`, `dob`, `blood_group`, `mobile`, `address`, `last_passout`, `last_passout_percentage`, `college_id`, `course_name`, `percentage_12th`, `percentage_10th`, `parent_name`, `parent_mobile`, `parent_occupation`, `parent_address`, `status`, `created_at`) VALUES
(1, 2, 'Dipak Patil', '2004-10-16', 'O+', '8379037877', 'At: kitwad , tal: chandgad, dist: kolhapur ', 'UG', '90.00', 0, 'MCA', '90.00', '90.00', 'Kalpana ', '7507014860', 'Farmer', 'At: kitwad , tal: chandgad, dist: kolhapur', 'approved', '2026-03-03 19:59:44'),
(2, 4, 'Harshad Naik', '2005-01-26', 'A+', '8075708362', 'Kolhapur', 'UG', '80.00', 0, 'BCA', '67.00', '83.00', 'Dilip Naik ', '9807987645', 'farmer', 'chandgad', 'approved', '2026-03-04 03:50:41'),
(3, 5, 'dipak patil', '2004-10-16', 'O+', '8379037877', 'dasara chowk , kolhapur', 'UG', '90.00', 0, 'bca', '89.00', '89.00', 'kalpana patil', '8379037877', 'farmer', 'dasara chowk , kolhapur', 'approved', '2026-03-04 09:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `check_in_time`, `ip_address`, `created_at`) VALUES
(1, 2, '2026-03-03', '12:01:18', '152.58.7.3', '2026-03-03 20:01:18'),
(2, 3, '2026-03-03', '12:09:54', '152.58.7.3', '2026-03-03 20:09:54'),
(3, 4, '2026-03-03', '20:03:07', '152.58.7.97', '2026-03-04 04:03:07'),
(4, 5, '2026-03-04', '01:26:43', '152.58.7.133', '2026-03-04 09:26:43'),
(5, 3, '2026-03-04', '01:29:11', '152.58.7.133', '2026-03-04 09:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `cleaning_checklist`
--

CREATE TABLE `cleaning_checklist` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `bathroom_clean` tinyint(1) DEFAULT 0,
  `toilet_clean` tinyint(1) DEFAULT 0,
  `washbasin_clean` tinyint(1) DEFAULT 0,
  `porch_clean` tinyint(1) DEFAULT 0,
  `office_clean` tinyint(1) DEFAULT 0,
  `garbage_clean` tinyint(1) DEFAULT 0,
  `corridor_staircase_clean` tinyint(1) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cleaning_checklist`
--

INSERT INTO `cleaning_checklist` (`id`, `staff_id`, `date`, `bathroom_clean`, `toilet_clean`, `washbasin_clean`, `porch_clean`, `office_clean`, `garbage_clean`, `corridor_staircase_clean`, `remarks`, `created_at`) VALUES
(1, 3, '2026-03-03', 1, 1, 1, 1, 1, 1, 1, '', '2026-03-03 20:10:12'),
(2, 3, '2026-03-04', 1, 1, 1, 1, 0, 1, 1, 'itvtytytyuktvyty', '2026-03-04 09:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `complaint_type` varchar(50) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `complaint_type`, `subject`, `description`, `status`, `created_at`, `resolved_at`) VALUES
(1, 4, 'Security', 'Main Gate Security Concern', 'The main gate was left open during late night hours without supervision. This may cause security risks. Please ensure proper monitoring', 'resolved', '2026-03-04 04:04:49', '2026-03-04 04:15:32'),
(2, 5, 'Cleaning', 'Regarding to Feedback of Website', 'fcytytryyycuycuctyutcyuctuct', 'resolved', '2026-03-04 09:27:08', '2026-03-04 09:27:40'),
(3, 5, 'Cleaning', 'Regarding to Feedback of Website', 'fcytytryyycuycuctyutcyuctuct', 'pending', '2026-03-04 09:27:45', NULL),
(4, 2, 'Water', 'Water Issue', 'Water supply problem in bathroom.', 'pending', '2026-03-05 12:13:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `application_id`, `document_type`, `file_path`, `uploaded_at`) VALUES
(1, 1, 'last_passout_marksheet', 'uploads/documents/1772567983_result3-4.pdf', '2026-03-03 19:59:44'),
(2, 1, '12th_marksheet', 'uploads/documents/1772567983_12th.pdf', '2026-03-03 19:59:44'),
(3, 1, '10th_marksheet', 'uploads/documents/1772567983_10th.pdf', '2026-03-03 19:59:44'),
(4, 1, 'college_admission_receipt', 'uploads/documents/1772567983_FeeReceiptReport .pdf', '2026-03-03 19:59:44'),
(5, 1, 'aadhaar_card', 'uploads/documents/1772567983_aadharcard.pdf', '2026-03-03 19:59:44'),
(6, 2, 'last_passout_marksheet', 'uploads/documents/1772596242_Screenshot 2026-03-03 003811.png', '2026-03-04 03:50:41'),
(7, 2, '12th_marksheet', 'uploads/documents/1772596242_Screenshot 2026-03-03 004202.png', '2026-03-04 03:50:41'),
(8, 2, '10th_marksheet', 'uploads/documents/1772596242_Screenshot 2026-03-03 002130.png', '2026-03-04 03:50:41'),
(9, 2, 'college_admission_receipt', 'uploads/documents/1772596242_231.png', '2026-03-04 03:50:41'),
(10, 2, 'aadhaar_card', 'uploads/documents/1772596242_Screenshot 2025-09-02 181757.png', '2026-03-04 03:50:41'),
(11, 3, 'last_passout_marksheet', 'uploads/documents/1772616189_231.png', '2026-03-04 09:23:09'),
(12, 3, '12th_marksheet', 'uploads/documents/1772616189_Screenshot (10).png', '2026-03-04 09:23:09'),
(13, 3, '10th_marksheet', 'uploads/documents/1772616189_Screenshot 2025-03-01 142843.jpg', '2026-03-04 09:23:09'),
(14, 3, 'college_admission_receipt', 'uploads/documents/1772616189_Screenshot (12).png', '2026-03-04 09:23:09'),
(15, 3, 'aadhaar_card', 'uploads/documents/1772616189_Screenshot (13).png', '2026-03-04 09:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `created_at`) VALUES
(1, 4, 'Medical', '2026-03-04', '2026-03-09', 'I was suffering from fever and weakness.', 'approved', '2026-03-04 04:06:45'),
(2, 2, 'Home', '2026-03-04', '2026-03-06', 'xyzfgjhgjkhjk', 'rejected', '2026-03-04 07:54:44'),
(3, 2, 'Home', '2026-03-04', '2026-03-06', 'xyzfgjhgjkhjk', 'rejected', '2026-03-04 07:55:04'),
(4, 2, 'Home', '2026-03-04', '2026-03-06', 'xyzfgjhgjkhjk', 'approved', '2026-03-04 07:55:29'),
(5, 5, 'Medical', '2026-03-04', '2026-03-14', 'dhdttrtj5tyytctu7', 'rejected', '2026-03-04 09:28:08'),
(6, 5, 'Medical', '2026-03-04', '2026-03-14', 'dhdttrtj5tyytctu7', 'pending', '2026-03-04 09:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'hostel_ip', '152.58.7.133', '2026-03-03 19:42:48', '2026-03-04 09:26:37'),
(2, 'hostel_name', '', '2026-03-03 19:42:48', '2026-03-03 20:01:11'),
(3, 'checkin_radius', '', '2026-03-03 19:42:48', '2026-03-03 20:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `staff_details`
--

CREATE TABLE `staff_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_details`
--

INSERT INTO `staff_details` (`id`, `user_id`, `name`, `mobile`, `status`, `created_at`) VALUES
(1, 3, 'vinod Patil', '9988776655', 'active', '2026-03-03 20:03:21'),
(2, 6, 'dipak patil', '8379037877', 'active', '2026-03-04 09:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`id`, `user_id`, `application_id`, `room_number`, `admission_date`, `status`) VALUES
(1, 2, 1, 'R-1', '2026-03-03', 'active'),
(2, 4, 2, 'R-2', '2026-03-03', 'active'),
(3, 5, 3, 'R-4', '2026-03-04', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','staff') NOT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'DIPAK', 'admin@smarthostel.com', 'dips2004', 'admin', 'active', '2026-03-03 19:42:48'),
(2, 'Patil', 'patildipak.vck@gmail.com', 'dips2004', 'student', 'active', '2026-03-03 19:59:44'),
(3, 'vinod', 'vinod@gmail.com', 'vinod2004', 'staff', 'active', '2026-03-03 20:03:21'),
(4, 'Harsh', 'naikharshad@gmail.com', 'harsh2004', 'student', 'active', '2026-03-04 03:50:41'),
(5, 'dips', 'dips@gmail.com', '123', 'student', 'active', '2026-03-04 09:23:09'),
(6, 'dip', 'dip@gmail.com', '123', 'staff', 'active', '2026-03-04 09:30:22'),
(7, 'DIPAK2004', 'patildipak.dkp@gmail.com', 'dips2004', 'student', 'pending', '2026-03-05 12:00:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission_applications`
--
ALTER TABLE `admission_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`user_id`,`date`);

--
-- Indexes for table `cleaning_checklist`
--
ALTER TABLE `cleaning_checklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cleaning` (`staff_id`,`date`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `staff_details`
--
ALTER TABLE `staff_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission_applications`
--
ALTER TABLE `admission_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cleaning_checklist`
--
ALTER TABLE `cleaning_checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_details`
--
ALTER TABLE `staff_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_details`
--
ALTER TABLE `student_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
