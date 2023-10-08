-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 6, 2023 at 05:03 PM
-- Server version: 5.6.21
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `project_info` (
`project_id` int(50) NOT NULL,
  `p_title` varchar(120) NOT NULL,
  `p_description` text,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = Fejlesztésre vár, 1 = Folyamatban, 2 = Kész',
  `contact_name` varchar(120) NOT NULL,
  `contact_email` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;


ALTER TABLE `project_info`
 ADD PRIMARY KEY (`project_id`);

ALTER TABLE `project_info`
MODIFY `project_id` int(50) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
