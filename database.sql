-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.7.11 - MySQL Community Server (GPL)
-- SE du serveur:                Win32
-- HeidiSQL Version:             9.5.0.5282
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour tutorial
CREATE DATABASE IF NOT EXISTS `tutorial` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tutorial`;

-- Listage de la structure de la table tutorial. accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `accountID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isBanned` tinyint(4) NOT NULL DEFAULT '0',
  `isPremium` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.
-- Listage de la structure de la table tutorial. registrationkeys
CREATE TABLE IF NOT EXISTS `registrationkeys` (
  `registerKey` varchar(10) NOT NULL,
  `userName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`registerKey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
