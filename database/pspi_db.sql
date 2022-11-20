-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2022 at 06:51 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pspi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `storeid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `persons` int(11) NOT NULL DEFAULT 1,
  `message` varchar(300) NOT NULL,
  `done` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `uid`, `storeid`, `name`, `email`, `phone`, `date`, `time`, `persons`, `message`, `done`) VALUES
(1, 1, 3, 'James Tuesday', 'admin@admin.com', '6900000001', '2022-05-19', '09:48:00', 4, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'Καφετέρια', 'img/cafe.jpeg'),
(2, 'Club', 'img/club.jpg'),
(4, 'Εστιατόριο', 'img/restaurant.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `storeid` int(11) NOT NULL,
  `identification` mediumblob NOT NULL,
  `license` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `storeid`, `identification`, `license`) VALUES
(4, 3, 0x255044462d312e330d0a25e2e3cfd30d0a0d0a312030206f626a0d0a3c3c0d0a2f54797065202f436174616c6f670d0a2f4f75746c696e65732032203020520d0a2f50616765732033203020520d0a3e3e0d0a656e646f626a0d0a0d0a322030206f626a0d0a3c3c0d0a2f54797065202f4f75746c696e65730d0a2f436f756e7420300d0a3e3e0d0a656e646f626a0d0a0d0a332030206f626a0d0a3c3c0d0a2f54797065202f50616765730d0a2f436f756e7420320d0a2f4b696473205b203420302052203620302052205d200d0a3e3e0d0a656e646f626a0d0a0d0a342030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732035203020520d0a3e3e0d0a656e646f626a0d0a0d0a352030206f626a0d0a3c3c202f4c656e6774682031303734203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a2820412053696d706c65205044462046696c65202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a282054686973206973206120736d616c6c2064656d6f6e7374726174696f6e202e7064662066696c65202d202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a28206a75737420666f722075736520696e20746865205669727475616c204d656368616e696373207475746f7269616c732e204d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203632382e383438302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203631362e383936302054640d0a2820746578742e20416e64206d6f726520746578742e20426f72696e672c207a7a7a7a7a2e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203630342e393434302054640d0a28206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203539322e393932302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203536392e303838302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203535372e313336302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e204576656e206d6f72652e20436f6e74696e756564206f6e20706167652032202e2e2e2920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a362030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732037203020520d0a3e3e0d0a656e646f626a0d0a0d0a372030206f626a0d0a3c3c202f4c656e67746820363736203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a282053696d706c65205044462046696c652032202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a28202e2e2e636f6e74696e7565642066726f6d207061676520312e20596574206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203637362e363536302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a2820746578742e204f682c20686f7720626f72696e6720747970696e6720746869732073747566662e20427574206e6f7420617320626f72696e67206173207761746368696e67202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a28207061696e74206472792e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203634302e383030302054640d0a2820426f72696e672e20204d6f72652c2061206c6974746c65206d6f726520746578742e2054686520656e642c20616e64206a7573742061732077656c6c2e202920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a382030206f626a0d0a5b2f504446202f546578745d0d0a656e646f626a0d0a0d0a392030206f626a0d0a3c3c0d0a2f54797065202f466f6e740d0a2f53756274797065202f54797065310d0a2f4e616d65202f46310d0a2f42617365466f6e74202f48656c7665746963610d0a2f456e636f64696e67202f57696e416e7369456e636f64696e670d0a3e3e0d0a656e646f626a0d0a0d0a31302030206f626a0d0a3c3c0d0a2f43726561746f72202852617665205c28687474703a2f2f7777772e6e6576726f6e612e636f6d2f726176655c29290d0a2f50726f647563657220284e6576726f6e612044657369676e73290d0a2f4372656174696f6e446174652028443a3230303630333031303732383236290d0a3e3e0d0a656e646f626a0d0a0d0a787265660d0a302031310d0a3030303030303030303020363535333520660d0a30303030303030303139203030303030206e0d0a30303030303030303933203030303030206e0d0a30303030303030313437203030303030206e0d0a30303030303030323232203030303030206e0d0a30303030303030333930203030303030206e0d0a30303030303031353232203030303030206e0d0a30303030303031363930203030303030206e0d0a30303030303032343233203030303030206e0d0a30303030303032343536203030303030206e0d0a30303030303032353734203030303030206e0d0a0d0a747261696c65720d0a3c3c0d0a2f53697a652031310d0a2f526f6f742031203020520d0a2f496e666f203130203020520d0a3e3e0d0a0d0a7374617274787265660d0a323731340d0a2525454f460d0a, 0x255044462d312e330d0a25e2e3cfd30d0a0d0a312030206f626a0d0a3c3c0d0a2f54797065202f436174616c6f670d0a2f4f75746c696e65732032203020520d0a2f50616765732033203020520d0a3e3e0d0a656e646f626a0d0a0d0a322030206f626a0d0a3c3c0d0a2f54797065202f4f75746c696e65730d0a2f436f756e7420300d0a3e3e0d0a656e646f626a0d0a0d0a332030206f626a0d0a3c3c0d0a2f54797065202f50616765730d0a2f436f756e7420320d0a2f4b696473205b203420302052203620302052205d200d0a3e3e0d0a656e646f626a0d0a0d0a342030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732035203020520d0a3e3e0d0a656e646f626a0d0a0d0a352030206f626a0d0a3c3c202f4c656e6774682031303734203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a2820412053696d706c65205044462046696c65202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a282054686973206973206120736d616c6c2064656d6f6e7374726174696f6e202e7064662066696c65202d202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a28206a75737420666f722075736520696e20746865205669727475616c204d656368616e696373207475746f7269616c732e204d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203632382e383438302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203631362e383936302054640d0a2820746578742e20416e64206d6f726520746578742e20426f72696e672c207a7a7a7a7a2e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203630342e393434302054640d0a28206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203539322e393932302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203536392e303838302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203535372e313336302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e204576656e206d6f72652e20436f6e74696e756564206f6e20706167652032202e2e2e2920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a362030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732037203020520d0a3e3e0d0a656e646f626a0d0a0d0a372030206f626a0d0a3c3c202f4c656e67746820363736203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a282053696d706c65205044462046696c652032202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a28202e2e2e636f6e74696e7565642066726f6d207061676520312e20596574206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203637362e363536302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a2820746578742e204f682c20686f7720626f72696e6720747970696e6720746869732073747566662e20427574206e6f7420617320626f72696e67206173207761746368696e67202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a28207061696e74206472792e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203634302e383030302054640d0a2820426f72696e672e20204d6f72652c2061206c6974746c65206d6f726520746578742e2054686520656e642c20616e64206a7573742061732077656c6c2e202920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a382030206f626a0d0a5b2f504446202f546578745d0d0a656e646f626a0d0a0d0a392030206f626a0d0a3c3c0d0a2f54797065202f466f6e740d0a2f53756274797065202f54797065310d0a2f4e616d65202f46310d0a2f42617365466f6e74202f48656c7665746963610d0a2f456e636f64696e67202f57696e416e7369456e636f64696e670d0a3e3e0d0a656e646f626a0d0a0d0a31302030206f626a0d0a3c3c0d0a2f43726561746f72202852617665205c28687474703a2f2f7777772e6e6576726f6e612e636f6d2f726176655c29290d0a2f50726f647563657220284e6576726f6e612044657369676e73290d0a2f4372656174696f6e446174652028443a3230303630333031303732383236290d0a3e3e0d0a656e646f626a0d0a0d0a787265660d0a302031310d0a3030303030303030303020363535333520660d0a30303030303030303139203030303030206e0d0a30303030303030303933203030303030206e0d0a30303030303030313437203030303030206e0d0a30303030303030323232203030303030206e0d0a30303030303030333930203030303030206e0d0a30303030303031353232203030303030206e0d0a30303030303031363930203030303030206e0d0a30303030303032343233203030303030206e0d0a30303030303032343536203030303030206e0d0a30303030303032353734203030303030206e0d0a0d0a747261696c65720d0a3c3c0d0a2f53697a652031310d0a2f526f6f742031203020520d0a2f496e666f203130203020520d0a3e3e0d0a0d0a7374617274787265660d0a323731340d0a2525454f460d0a),
(8, 1, 0x255044462d312e330d0a25e2e3cfd30d0a0d0a312030206f626a0d0a3c3c0d0a2f54797065202f436174616c6f670d0a2f4f75746c696e65732032203020520d0a2f50616765732033203020520d0a3e3e0d0a656e646f626a0d0a0d0a322030206f626a0d0a3c3c0d0a2f54797065202f4f75746c696e65730d0a2f436f756e7420300d0a3e3e0d0a656e646f626a0d0a0d0a332030206f626a0d0a3c3c0d0a2f54797065202f50616765730d0a2f436f756e7420320d0a2f4b696473205b203420302052203620302052205d200d0a3e3e0d0a656e646f626a0d0a0d0a342030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732035203020520d0a3e3e0d0a656e646f626a0d0a0d0a352030206f626a0d0a3c3c202f4c656e6774682031303734203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a2820412053696d706c65205044462046696c65202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a282054686973206973206120736d616c6c2064656d6f6e7374726174696f6e202e7064662066696c65202d202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a28206a75737420666f722075736520696e20746865205669727475616c204d656368616e696373207475746f7269616c732e204d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203632382e383438302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203631362e383936302054640d0a2820746578742e20416e64206d6f726520746578742e20426f72696e672c207a7a7a7a7a2e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203630342e393434302054640d0a28206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203539322e393932302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203536392e303838302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203535372e313336302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e204576656e206d6f72652e20436f6e74696e756564206f6e20706167652032202e2e2e2920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a362030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732037203020520d0a3e3e0d0a656e646f626a0d0a0d0a372030206f626a0d0a3c3c202f4c656e67746820363736203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a282053696d706c65205044462046696c652032202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a28202e2e2e636f6e74696e7565642066726f6d207061676520312e20596574206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203637362e363536302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a2820746578742e204f682c20686f7720626f72696e6720747970696e6720746869732073747566662e20427574206e6f7420617320626f72696e67206173207761746368696e67202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a28207061696e74206472792e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203634302e383030302054640d0a2820426f72696e672e20204d6f72652c2061206c6974746c65206d6f726520746578742e2054686520656e642c20616e64206a7573742061732077656c6c2e202920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a382030206f626a0d0a5b2f504446202f546578745d0d0a656e646f626a0d0a0d0a392030206f626a0d0a3c3c0d0a2f54797065202f466f6e740d0a2f53756274797065202f54797065310d0a2f4e616d65202f46310d0a2f42617365466f6e74202f48656c7665746963610d0a2f456e636f64696e67202f57696e416e7369456e636f64696e670d0a3e3e0d0a656e646f626a0d0a0d0a31302030206f626a0d0a3c3c0d0a2f43726561746f72202852617665205c28687474703a2f2f7777772e6e6576726f6e612e636f6d2f726176655c29290d0a2f50726f647563657220284e6576726f6e612044657369676e73290d0a2f4372656174696f6e446174652028443a3230303630333031303732383236290d0a3e3e0d0a656e646f626a0d0a0d0a787265660d0a302031310d0a3030303030303030303020363535333520660d0a30303030303030303139203030303030206e0d0a30303030303030303933203030303030206e0d0a30303030303030313437203030303030206e0d0a30303030303030323232203030303030206e0d0a30303030303030333930203030303030206e0d0a30303030303031353232203030303030206e0d0a30303030303031363930203030303030206e0d0a30303030303032343233203030303030206e0d0a30303030303032343536203030303030206e0d0a30303030303032353734203030303030206e0d0a0d0a747261696c65720d0a3c3c0d0a2f53697a652031310d0a2f526f6f742031203020520d0a2f496e666f203130203020520d0a3e3e0d0a0d0a7374617274787265660d0a323731340d0a2525454f460d0a, 0x255044462d312e330d0a25e2e3cfd30d0a0d0a312030206f626a0d0a3c3c0d0a2f54797065202f436174616c6f670d0a2f4f75746c696e65732032203020520d0a2f50616765732033203020520d0a3e3e0d0a656e646f626a0d0a0d0a322030206f626a0d0a3c3c0d0a2f54797065202f4f75746c696e65730d0a2f436f756e7420300d0a3e3e0d0a656e646f626a0d0a0d0a332030206f626a0d0a3c3c0d0a2f54797065202f50616765730d0a2f436f756e7420320d0a2f4b696473205b203420302052203620302052205d200d0a3e3e0d0a656e646f626a0d0a0d0a342030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732035203020520d0a3e3e0d0a656e646f626a0d0a0d0a352030206f626a0d0a3c3c202f4c656e6774682031303734203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a2820412053696d706c65205044462046696c65202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a282054686973206973206120736d616c6c2064656d6f6e7374726174696f6e202e7064662066696c65202d202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a28206a75737420666f722075736520696e20746865205669727475616c204d656368616e696373207475746f7269616c732e204d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203632382e383438302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203631362e383936302054640d0a2820746578742e20416e64206d6f726520746578742e20426f72696e672c207a7a7a7a7a2e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203630342e393434302054640d0a28206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203539322e393932302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203536392e303838302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203535372e313336302054640d0a2820746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e204576656e206d6f72652e20436f6e74696e756564206f6e20706167652032202e2e2e2920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a362030206f626a0d0a3c3c0d0a2f54797065202f506167650d0a2f506172656e742033203020520d0a2f5265736f7572636573203c3c0d0a2f466f6e74203c3c0d0a2f4631203920302052200d0a3e3e0d0a2f50726f635365742038203020520d0a3e3e0d0a2f4d65646961426f78205b302030203631322e30303030203739322e303030305d0d0a2f436f6e74656e74732037203020520d0a3e3e0d0a656e646f626a0d0a0d0a372030206f626a0d0a3c3c202f4c656e67746820363736203e3e0d0a73747265616d0d0a32204a0d0a42540d0a30203020302072670d0a2f463120303032372054660d0a35372e33373530203732322e323830302054640d0a282053696d706c65205044462046696c652032202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203638382e363038302054640d0a28202e2e2e636f6e74696e7565642066726f6d207061676520312e20596574206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203637362e363536302054640d0a2820416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f7265202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203636342e373034302054640d0a2820746578742e204f682c20686f7720626f72696e6720747970696e6720746869732073747566662e20427574206e6f7420617320626f72696e67206173207761746368696e67202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203635322e373532302054640d0a28207061696e74206472792e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e20416e64206d6f726520746578742e202920546a0d0a45540d0a42540d0a2f463120303031302054660d0a36392e32353030203634302e383030302054640d0a2820426f72696e672e20204d6f72652c2061206c6974746c65206d6f726520746578742e2054686520656e642c20616e64206a7573742061732077656c6c2e202920546a0d0a45540d0a656e6473747265616d0d0a656e646f626a0d0a0d0a382030206f626a0d0a5b2f504446202f546578745d0d0a656e646f626a0d0a0d0a392030206f626a0d0a3c3c0d0a2f54797065202f466f6e740d0a2f53756274797065202f54797065310d0a2f4e616d65202f46310d0a2f42617365466f6e74202f48656c7665746963610d0a2f456e636f64696e67202f57696e416e7369456e636f64696e670d0a3e3e0d0a656e646f626a0d0a0d0a31302030206f626a0d0a3c3c0d0a2f43726561746f72202852617665205c28687474703a2f2f7777772e6e6576726f6e612e636f6d2f726176655c29290d0a2f50726f647563657220284e6576726f6e612044657369676e73290d0a2f4372656174696f6e446174652028443a3230303630333031303732383236290d0a3e3e0d0a656e646f626a0d0a0d0a787265660d0a302031310d0a3030303030303030303020363535333520660d0a30303030303030303139203030303030206e0d0a30303030303030303933203030303030206e0d0a30303030303030313437203030303030206e0d0a30303030303030323232203030303030206e0d0a30303030303030333930203030303030206e0d0a30303030303031353232203030303030206e0d0a30303030303031363930203030303030206e0d0a30303030303032343233203030303030206e0d0a30303030303032343536203030303030206e0d0a30303030303032353734203030303030206e0d0a0d0a747261696c65720d0a3c3c0d0a2f53697a652031310d0a2f526f6f742031203020520d0a2f496e666f203130203020520d0a3e3e0d0a0d0a7374617274787265660d0a323731340d0a2525454f460d0a);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `city` text NOT NULL,
  `address` text NOT NULL,
  `zipcode` varchar(5) NOT NULL,
  `categories` text NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `image` text NOT NULL,
  `reserved` int(11) NOT NULL DEFAULT 0,
  `capacity` int(11) NOT NULL DEFAULT 0,
  `maxpersonpertable` int(11) NOT NULL DEFAULT 6,
  `approved` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `added_by`, `city`, `address`, `zipcode`, `categories`, `title`, `description`, `image`, `reserved`, `capacity`, `maxpersonpertable`, `approved`) VALUES
(1, 1, 'Θεσσαλονίκη', 'Μακεδονίας 101', '54248', '1,2', 'Store 1', 'This is some description text.\n\n[b]bold[/b]\n\n&lt;b&gt;lol&lt;/b&gt;\n', 'img/cafe.jpeg', 0, 10, 6, 0),
(2, 3, 'Αθήνα', 'Ισμήνης 90', '12345', '2,4', 'Store 2', 'Test description text :D', 'img/club.jpg', 0, 20, 6, 1),
(3, 1, 'Θεσσαλονίκη', 'Πέτρου Συνδίκα 14', '54645', '2', 'Store 3', 'nigdisjgaksflasdmgsgGKMSALDKGALSKDGNALJSDNGALSDNGALSDJGNASLDGNLJANDSJLNALJNlkjngdkdsjngkjsdngjksdngkjsndgkjsndgjnskjgdnskjdnggkklgaklskgalkglakglkdslgkslkdglsdlgksldkgsdjgkj4i3jg834g8348gh349gh934hg934h9gh394', 'img/club.jpg', 0, 15, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_operational_hours`
--

CREATE TABLE `store_operational_hours` (
  `id` int(11) NOT NULL,
  `storeid` int(11) NOT NULL,
  `week_day` int(11) NOT NULL,
  `opens_at` time NOT NULL,
  `closes_at` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `store_operational_hours`
--

INSERT INTO `store_operational_hours` (`id`, `storeid`, `week_day`, `opens_at`, `closes_at`) VALUES
(6, 3, 2, '08:42:00', '22:42:00'),
(7, 3, 3, '07:43:00', '21:42:00'),
(29, 1, 1, '10:42:00', '22:42:00'),
(30, 1, 5, '07:42:00', '12:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp(),
  `rank` int(11) NOT NULL DEFAULT 0,
  `favorites` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `email`, `phone`, `password`, `salt`, `creation_date`, `rank`, `favorites`) VALUES
(1, 'admin', 'James', 'Tuesday', 'admin@admin.com', '6900000001', '405fb985ed9ba881118a00b68413adcd', 'B7dV81GWVRWtDnEBRXx4lHzkQVj9Cg6f', '2022-02-25', 2, '3,1'),
(2, 'user', 'Hugh', 'Jackman', 'user@user.com', '6900000002', '53ab11d2fb99ddf4bcd2cb6f841e9679', 'MygXs8FeeapBcfbtfxO7Zg0bKLz3IWJ5', '2022-02-26', 0, '1'),
(3, 'prof', 'Brand', 'Smith', 'prof@prof.com', '6900000003', '4bcf956dea84b9013b78c0eadfaba697', 'Sk81ATNB3TP33GtXYCJGPTh41jK5caDb', '2022-02-26', 1, '2'),
(18, 'test', 'Test', 'Test', 'test@test.com', '1234546890', '6cb69736832fb1fc5ebcc1e7cfe8c7eb', 'n1JlJAVvJmYxnAcsQBnpBOJiSpIrtNKh', '2022-04-13', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_operational_hours`
--
ALTER TABLE `store_operational_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_operational_hours`
--
ALTER TABLE `store_operational_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;