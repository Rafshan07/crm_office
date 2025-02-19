-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 12:32 PM
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
-- Database: `crm_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `ActivityID` int(11) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `Duration` int(11) NOT NULL,
  `Note` text DEFAULT NULL,
  `RelatedCustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `Industry` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `Name`, `Email`, `Phone`, `Address`, `Company`, `Industry`, `password`) VALUES
(10, 'Nayem', 'cus@mail.com', '123456789098', 'wertyu', 'qwert', 'werty', '$2y$10$0JhfA0/It6JdFf58kMWx2uXnIstXlzoJt10D4jVBFagi1SMjQWVLy');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `bill_to` varchar(255) NOT NULL,
  `ship_to` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `po_number` varchar(50) DEFAULT NULL,
  `payment_term` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `due_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `from_name`, `bill_to`, `ship_to`, `invoice_date`, `due_date`, `po_number`, `payment_term`, `notes`, `tax`, `total_amount`, `amount_paid`, `due_amount`, `created_at`, `company_name`, `company_address`, `phone`) VALUES
(12, 'Rafsan', 'Saber', 'Office', '2025-02-04', '2025-02-28', '1020', 'Cash', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 1.00, 0.00, 100.00, 23900.00, '2025-02-04 11:49:00', 'APcorn', 'Eastern View (12th Floor), 50, Nayapaltan, Dhaka-1000', '1581195132'),
(14, 'rafshan', 'sisir', '12', '2025-02-20', '2025-02-27', '10', '####', '###############', 1.00, 0.00, 400.00, 80.00, '2025-02-17 06:18:55', 'apcorn', '12345', '01424269701');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `rate`, `tax`, `amount`) VALUES
(8, 12, 'CRM', 1, 20000.00, 20.00, 24000.00),
(9, 13, 'CRM', 1, 30000.00, 27.00, 38100.00),
(10, 14, 'asd', 10, 20.00, 15.00, 230.00),
(11, 14, 'sdf', 20, 10.00, 25.00, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `lead`
--

CREATE TABLE `lead` (
  `LeadID` int(11) NOT NULL,
  `Source` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `AssignedTo` int(11) NOT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lead`
--

INSERT INTO `lead` (`LeadID`, `Source`, `Status`, `AssignedTo`, `CreateDate`, `CustomerID`) VALUES
(1, 'B', 'b', 1, '1969-11-12 00:00:00', 1),
(5, 'abc', 'active', 1, '2025-11-11 00:00:00', 1),
(6, 'acc', 'acc', 3, '2025-11-11 00:00:00', 2),
(7, 'sd', 'sd', 2, '1111-11-11 00:00:00', 2),
(8, 'zx', 'zc', 3, '1111-11-11 00:00:00', 3),
(9, 'zx', 'zc', 3, '1111-11-11 00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `opportunity`
--

CREATE TABLE `opportunity` (
  `OpportunityID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Stage` varchar(255) NOT NULL,
  `ExpectedRevenue` decimal(10,2) NOT NULL,
  `CloseDate` datetime NOT NULL,
  `Probability` float NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opportunity`
--

INSERT INTO `opportunity` (`OpportunityID`, `Title`, `Stage`, `ExpectedRevenue`, `CloseDate`, `Probability`, `CustomerID`) VALUES
(1, 'CRM Software Implementation', 'Prospecting', 80000.00, '2025-02-09 00:00:00', 25, 420),
(2, 'Cloud Migration Project', 'Negotiation', 150000.00, '2025-04-15 00:00:00', 60, 202),
(3, 'AI Chatbot Development', 'Proposal', 50000.00, '2025-02-28 00:00:00', 40, 203),
(4, 'Enterprise SaaS Subscription', 'Closed Won', 250000.00, '2025-01-20 00:00:00', 100, 204),
(5, 'Cybersecurity Solution Upgrade', 'Closed Lost', 60000.00, '2025-02-05 00:00:00', 0, 205),
(6, 'E-commerce Platform Enhancement', 'Proposal', 90000.00, '2025-05-01 00:00:00', 55, 206),
(7, 'DevOps Automation Tool', 'Prospecting', 120000.00, '2025-06-10 00:00:00', 30, 207),
(8, 'Blockchain Integration Service', 'Negotiation', 200000.00, '2025-02-09 00:00:00', 70, 10);

-- --------------------------------------------------------

--
-- Table structure for table `opportunitydetails`
--

CREATE TABLE `opportunitydetails` (
  `OpportunityDetailID` int(11) NOT NULL,
  `OpportunityID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL,
  `OrderDate` datetime NOT NULL,
  `Status` varchar(255) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `OrderDate`, `Status`, `TotalAmount`, `CustomerID`) VALUES
(13, '2025-02-09 16:28:43', 'completed', 4500.00, 10),
(14, '2025-02-09 16:29:08', 'completed', 3000.00, 10),
(15, '2025-02-09 16:29:15', 'Completed', 6000.00, 10),
(16, '2025-02-09 18:10:25', 'Cancelled', 4500.00, 10),
(17, '2025-02-10 18:43:24', 'Completed', 4500.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `Price`) VALUES
(23, 13, 44, 1, 4500.00),
(24, 14, 45, 1, 3000.00),
(25, 15, 46, 1, 6000.00),
(26, 16, 44, 1, 4500.00),
(27, 17, 44, 1, 4500.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Name`, `Category`, `Price`, `StockLevel`) VALUES
(44, 'Global Payment Software', 'Payment gateway solution', 4500.00, 87),
(45, 'Payment Integration API', 'Payment API for businesses', 3000.00, 95),
(46, 'Multi-Currency Wallet', 'international payments', 6000.00, 95),
(48, 'CRM', 'Management System', 20000.00, 96),
(51, 'ERP', 'Management System', 200000.00, 96);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `TaskID` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `DueDate` datetime DEFAULT NULL,
  `AssignedTo` int(11) NOT NULL,
  `RelatedCustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`TaskID`, `Description`, `DueDate`, `AssignedTo`, `RelatedCustomerID`) VALUES
(291, 'Task 37', '2025-02-09 00:00:00', 6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `role` enum('admin','sales','staff') NOT NULL,
  `Department` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Name`, `Email`, `role`, `Department`, `Password`) VALUES
(11, 'Admin1', 'admin@mail.com', 'admin', 'Management', '$2y$10$SpL/KdNVT0QZY34b2cqU6uAVfG5h5PliQWDygOleU1PXpaOc3N2km'),
(12, 'Staff1', 'staff@mail.com', 'staff', 'Support', '$2y$10$gc4tFzKD5a/EtI/rF9dVyuyT4Mi9FEGRqAzMim.z7OlzYmnvlmAAK'),
(13, 'Sales1', 'sales@mail.com', 'sales', 'Sales', '$2y$10$8Avs0akOXscD7z.bWQyMkeir4ZfScjbU9r2qgGWfE8r/JbcKXxK3a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`ActivityID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`,`Phone`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead`
--
ALTER TABLE `lead`
  ADD PRIMARY KEY (`LeadID`);

--
-- Indexes for table `opportunity`
--
ALTER TABLE `opportunity`
  ADD PRIMARY KEY (`OpportunityID`);

--
-- Indexes for table `opportunitydetails`
--
ALTER TABLE `opportunitydetails`
  ADD PRIMARY KEY (`OpportunityDetailID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`TaskID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `ActivityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lead`
--
ALTER TABLE `lead`
  MODIFY `LeadID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `opportunity`
--
ALTER TABLE `opportunity`
  MODIFY `OpportunityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `opportunitydetails`
--
ALTER TABLE `opportunitydetails`
  MODIFY `OpportunityDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=777;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
