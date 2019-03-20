-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2019 at 05:24 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_survey`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Username` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Username`, `Email`, `Password`) VALUES
('vrizawahyu22', 'vrizawahyu22@gmail.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `jawaban`
--

CREATE TABLE `jawaban` (
  `IdSurvey` int(255) NOT NULL,
  `IdPertanyaan` int(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Jawaban` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `Username` varchar(100) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Alamat` varchar(150) NOT NULL,
  `Provinsi` varchar(50) NOT NULL,
  `Kabupaten` varchar(50) NOT NULL,
  `Kecamatan` varchar(50) NOT NULL,
  `NoTelepon` varchar(20) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Profesi` varchar(30) NOT NULL,
  `Poin` int(30) NOT NULL,
  `Foto` varchar(50) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_reward`
--

CREATE TABLE `member_reward` (
  `Username` varchar(100) NOT NULL,
  `IdReward` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `IdPertanyaan` int(255) NOT NULL,
  `IdSurvey` int(100) NOT NULL,
  `IsiPertanyaan` varchar(255) NOT NULL,
  `Tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `IdReward` int(100) NOT NULL,
  `NamaReward` varchar(100) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Jumlah` int(100) NOT NULL,
  `Foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `IdSurvey` int(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Judul` varchar(100) NOT NULL,
  `Kategori` varchar(50) NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`IdSurvey`),
  ADD KEY `IdPertanyaan` (`IdPertanyaan`),
  ADD KEY `Username` (`Username`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `member_reward`
--
ALTER TABLE `member_reward`
  ADD KEY `Username` (`Username`),
  ADD KEY `IdReward` (`IdReward`);

--
-- Indexes for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`IdPertanyaan`),
  ADD KEY `IdSurvey` (`IdSurvey`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`IdReward`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`IdSurvey`),
  ADD KEY `survey_ibfk_2` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `IdSurvey` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `IdPertanyaan` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `IdReward` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `IdSurvey` int(100) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `jawaban`
--
ALTER TABLE `jawaban`
  ADD CONSTRAINT `jawaban_ibfk_1` FOREIGN KEY (`IdPertanyaan`) REFERENCES `pertanyaan` (`IdPertanyaan`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `jawaban_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `member` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `member_reward`
--
ALTER TABLE `member_reward`
  ADD CONSTRAINT `member_reward_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `member` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `member_reward_ibfk_2` FOREIGN KEY (`IdReward`) REFERENCES `reward` (`IdReward`);

--
-- Constraints for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`IdSurvey`) REFERENCES `survey` (`IdSurvey`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `survey`
--
ALTER TABLE `survey`
  ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `admin` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `survey_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `member` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
