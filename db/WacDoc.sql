--
-- Database: `WacDoc`
--

DROP DATABASE IF EXISTS WacDoc;
CREATE DATABASE WacDoc;
USE WacDoc;

-- --------------------------------------------------------

DROP TABLE IF EXISTS users;

CREATE TABLE users (
   id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   login varchar(50) NOT NULL,
   password varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

DROP TABLE IF EXISTS users_docs;

CREATE TABLE users_docs (
   id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   user_id INT NOT NULL,
   name varchar(50) NOT NULL,
   datas text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
