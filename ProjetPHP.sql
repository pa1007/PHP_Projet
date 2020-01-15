-- phpMyAdmin SQL Dump
-- version 4.0.10deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 15, 2020 at 04:47 PM
-- Server version: 5.7.24
-- PHP Version: 5.5.9-1ubuntu4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ProjetPHP`
--

-- --------------------------------------------------------

--
-- Table structure for table `cagnotte`
--

CREATE TABLE IF NOT EXISTS `cagnotte` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_item` int(2) NOT NULL,
  `valeur` decimal(21,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cagnotte`
--

INSERT INTO `cagnotte` (`id`, `id_item`, `valeur`) VALUES
(1, 25, 32.00),
(3, 79, 952.00),
(4, 80, 22.00),
(5, 23, 30.30);

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) NOT NULL,
  `liste_id` int(2) NOT NULL,
  `userID` varchar(72) DEFAULT NULL,
  `nom` varchar(121) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `commentaire`
--

INSERT INTO `commentaire` (`id`, `message`, `liste_id`, `userID`, `nom`) VALUES
(14, 'Bonjour', 2, NULL, 'Paul-Alexandre Fourriere'),
(19, 'ça va être super', 2, NULL, 'Fabien Drommer'),
(29, 'C&#39;est super !', 1, NULL, 'Laury'),
(30, 'Ce sont des beaux cadeaux !', 3, NULL, 'Laury Thiebaux');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id_image` int(6) NOT NULL AUTO_INCREMENT,
  `id_item` int(6) NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id_image`, `id_item`, `img`) VALUES
(1, 1, 'champagne.jpg'),
(2, 2, 'musique.jpg'),
(3, 3, 'poirelregarder.jpg'),
(4, 4, 'gouter.jpg'),
(5, 5, 'film.jpg'),
(6, 6, 'rose.jpg'),
(7, 7, 'bonroi.jpg'),
(8, 8, 'origami.jpg'),
(9, 9, 'bricolage.jpg'),
(10, 10, 'grandrue.jpg'),
(11, 11, 'place.jpg'),
(12, 12, 'bijoux.jpg'),
(13, 24, 'hotel_haussonville_logo.jpg'),
(14, 25, 'boitedenuit.jpg'),
(15, 26, 'laser.jpg'),
(16, 27, 'fort.jpg'),
(17, 51, 'https://images-na.ssl-images-amazon.com/images/I/81Q29n1Q34L._SX385_.jpg'),
(19, 19, 'contact.png'),
(20, 43, 'https://media.auchan.fr/MEDIASTEP65863140_468x468/AFR/05f0103d-b9a5-4c08-b7f5-a644088cb4aa'),
(21, 59, 'https://puu.sh/EWscJ/734e558c5c.png'),
(22, 59, 'https://puu.sh/EWsb3/f00fd843a1.png'),
(24, 60, 'https://www.nationalgeographic.com/content/dam/animals/2019/11/koala-australia-fires/koalas-australia-00523055.jpg'),
(25, 60, 'https://www.pairidaiza.eu/sites/default/files/styles/poi_banner/public/media/image/Otarie-A-Fourrure-d-Afrique-Du-Sud-HEADERR.jpg?h=5bf672e5&itok=VbRYM9Aa'),
(26, 61, 'https://i.kym-cdn.com/photos/images/newsfeed/000/862/065/0e9.jpg'),
(28, 61, 'champagne.jpg'),
(29, 22, 'apparthotel.jpg'),
(31, 62, 'https://www.cdiscount.com/pdt2/c/f/r/1/700x700/81f5012cfr/rw/ordinateur-ultrabook-lenovo-ideapad-330s-15ikb.jpg'),
(32, 63, 'https://www.cdiscount.com/pdt2/3/3/9/1/700x700/0045496452339/rw/console-nintendo-switch-avec-un-joy-con-droit-roug.jpg'),
(33, 63, 'https://static.fnac-static.com/multimedia/Images/FR/NR/88/01/ac/11272584/1540-1/tsp20190924130455/Console-portable-Nintendo-Switch-Lite-Turquoise.jpg'),
(34, 23, 'apparthotel.jpg'),
(35, 75, 'https://www.erenumerique.fr/wp-content/uploads/2018/02/gta-v.jpg'),
(36, 77, 'https://www.spotsound.fr/133221-large_default/mascotte-d-%C3%A9l%C3%A9phant-rose-mignon-et-color%C3%A9.jpg'),
(37, 30, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Lobster_with_E-Fu_Noodle.jpg/1200px-Lobster_with_E-Fu_Noodle.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `liste_id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `descr` text,
  `img` text,
  `url` text,
  `tarif` decimal(21,2) DEFAULT '100.00',
  `modifToken` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `liste_id`, `nom`, `descr`, `img`, `url`, `tarif`, `modifToken`) VALUES
(1, 2, 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', 'champagne.jpg', '', 20.00, ''),
(2, 2, 'Musique', 'Partitions de piano à 4 mains', 'musique.jpg', '', 25.00, ''),
(3, 2, 'Exposition', 'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel', 'poirelregarder.jpg', '', 14.00, ''),
(4, 3, 'Goûter', 'Goûter au FIFNL', 'gouter.jpg', '', 20.00, ''),
(5, 3, 'Projection', 'Projection courts-métrages au FIFNL', 'film.jpg', '', 10.00, ''),
(6, 2, 'Bouquet', 'Bouquet de roses et Mots de Marion Renaud', 'rose.jpg', '', 16.00, ''),
(7, 2, 'Diner Stanislas', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif)', 'bonroi.jpg', '', 60.00, ''),
(8, 3, 'Origami', 'Baguettes magiques en Origami en buvant un thé', 'origami.jpg', '', 12.00, ''),
(9, 3, 'Livres', 'Livre bricolage avec petits-enfants + Roman', 'bricolage.jpg', '', 24.00, ''),
(10, 2, 'Diner  Grand Rue ', 'Diner au Grand’Ru(e) (Apéritif / Entrée / Plat / Vin / Dessert / Café)', 'grandrue.jpg', '', 59.00, ''),
(11, 0, 'Visite guidée', 'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas', 'place.jpg', '', 11.00, ''),
(12, 2, 'Bijoux', 'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil', 'bijoux.jpg', '', 29.00, ''),
(19, 0, 'Jeu contacts', 'Jeu pour échange de contacts', 'contact.png', '', 5.00, ''),
(22, 0, 'Concert', 'Un concert à Nancy', 'concert.jpg', '', 17.00, ''),
(23, 1, 'Appart Hotel', 'Appart’hôtel Coeur de Ville, en plein centre-ville', 'apparthotel.jpg', '', 56.00, ''),
(24, 2, 'Hôtel d''Haussonville', 'Hôtel d''Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas', 'hotel_haussonville_logo.jpg', '', 169.00, ''),
(25, 1, 'Boite de nuit', 'Discothèque, Boîte tendance avec des soirées à thème & DJ invités', 'boitedenuit.jpg', '', 32.00, ''),
(26, 1, 'Planètes Laser', 'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.', 'laser.jpg', '', 15.00, ''),
(27, 1, 'Fort Aventure', 'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l''élastique inversé, Toboggan géant... et bien plus encore.', 'fort.jpg', '', 25.00, ''),
(30, 8, 'KAT', 'Le prince', NULL, '', 500.00, '78db7a1a1fb2d91c0bcf7a7ed98ac9a490037f86577c77583f078e980803a0ce'),
(43, 7, 'macbook pro', 'ordinateur', '', '', 999.00, '109b4a94e31f41f36f915ad12ad3fb3f8f5cb47edb034e0ca3ed137543b80733'),
(51, 7, 'Bière', 'Boisson rafraichissante', 'Imagedebire', '', 10.00, 'ceabf85555d9c7323c039a68aef2000441b4da3c703d27ffbdc0f49e65b14c92'),
(63, 23, 'Nitendo switch', 'Console', NULL, '', 252.00, '4996d57de02f5f891c81f42bf2f38159d2ef9763925481b75cb0e44316ebdf12'),
(75, 0, 'GTA', 'jeux', NULL, '', 35.00, 'ef69b2f0287ffb1575ca958ac10af8a2f4e82a5f7fff1598e7580cb819066bcc'),
(76, 31, 'GTA', 'jeu', NULL, '', 35.00, '578ba3e0b2936ce43fc4597177640a3aad98ead6252f28adf26de546cb95a721'),
(77, 8, 'Eléphant rose', 'Un petit élephant rose en peluche', NULL, '', 65.00, '0a31382d938cdb3fb10c18de8cf806a9b8d89bbe00491c5457b7f99b5ed1ec09'),
(78, 32, 't', 't', NULL, '', 8.00, 'f165d29b049f3e18f02fe2bbb4f927408ef9e6792ec8633776b6d13f911d5bdf'),
(79, 34, 'La boule magique', 'incroyable instrument', NULL, '', 1000.00, 'f4c454279c3b1f4c76a5c0451bb226f21b106189ca9a767dca616b8f19e7a947');

-- --------------------------------------------------------

--
-- Table structure for table `liste`
--

CREATE TABLE IF NOT EXISTS `liste` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expiration` date DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modifToken` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=38 ;

--
-- Dumping data for table `liste`
--

INSERT INTO `liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`, `modifToken`, `visible`) VALUES
(1, 'ac995a750f727be3b97e444e29153b447144770601670e34485fa0f3a44d47d2', 'Pour fêter le bac !', 'Pour un week-end à Nancy qui nous fera oublier les épreuves. ', '2020-06-27', '65e19b109289b9a75fef97d6de6cba32c6ccbfaeec0b9b02fb27075e246b605a', NULL, 1),
(2, '2', 'Liste de mariage d''Alice et Bob', 'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)', '2018-06-30', '6647ba9e95290e2f89bce79625b4d9b9da79451833441fc51f05a4347f48ebea', NULL, 0),
(3, '73e77f3a9706886f61af18cc129425a63d88f24f752a44a8897574cfba54e13d', 'C''est l''anniversaire de Charlie', 'Pour lui préparer une fête dont il se souviendra :)', '2019-12-12', '87f47fc9935ce6ca0f5f12b92669f93c9b733c7dd2ae2f3259772cac74199ff7', NULL, 1),
(7, 'c06bb641d52cbf15347db9164b82d91b9004634819d5a90a4231ac54fe0f2b59', 'Anniversaire Laury', 'des idées de cadeaux pour l&#39;anniversaire de Laury', '2020-01-13', '468b9caae874397925ad607dab8ef8940f6cbc2eb9464a3a833f5201cddd0eb2', '301de8da40e40840dc3a376245cd1c85f9895dcf9ae60f31a9c4b7c53eabced7', 1),
(8, '84c4a57bb1574561183fd9304cdee758994332c2ae7b1c77c8d1521a781b8387', 'Petit papa noel de PA', 'Le noel de PA', '2020-02-05', 'bb6424525db608901d79c2dfce9abbc7bca9e4af87ac193dade3d7eaeec5f372', '9da19dd87808d6bd41e1492249b62e7134092547918cd4bb5588780a7ca6a20b', 1),
(22, '73e77f3a9706886f61af18cc129425a63d88f24f752a44a8897574cfba54e13d', 'La rentrée', 'c&#39;est bientot la rentrée', '2020-01-06', '707ed36ab1657952ce307da2fbdb78addcd0453502ee961bf75746fa9e853962', 'e6a1f47a7ae348011e6abffc0a727af6d80cf7695c95e351d2a97ffd48bcff39', 0),
(23, '84c4a57bb1574561183fd9304cdee758994332c2ae7b1c77c8d1521a781b8387', 'Anniv de Tom', 'C&#39;est l&#39;anniv de tom', '2020-09-08', 'e9fa079f75921faff764688864d1b1ef54ab17c0860f14561ade8218e3867cec', '88857bc7ea776a7ab06c0f3141c5ff40f318209769ec9fd8d06340693a1c905d', 0),
(31, 'ac995a750f727be3b97e444e29153b447144770601670e34485fa0f3a44d47d2', 'Jeux', 'jeux', '2020-01-13', '1c4c91de601e932a4a582c97ffb7761d0ab4b9d8b2fa3eab39d55bc7524e024c', '560b077664427dec8737e219348767cd95d684f5767983a4a2125c80e3dd93ee', 1);

-- --------------------------------------------------------

--
-- Table structure for table `partage`
--

CREATE TABLE IF NOT EXISTS `partage` (
  `idliste` int(11) NOT NULL,
  `tokenpartage` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `partage`
--

INSERT INTO `partage` (`idliste`, `tokenpartage`, `id`) VALUES
(31, '4008814ac1029dd3bb7edd16903c87809649214fea363f8db85433c1f84bea0a', 13);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
  `idItem` int(11) NOT NULL,
  `nomUtilisateur` varchar(30) DEFAULT NULL,
  `message` text,
  `type` enum('NORMAL','CAGNOTTE') NOT NULL DEFAULT 'NORMAL',
  `userID` varchar(72) DEFAULT NULL,
  PRIMARY KEY (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`idItem`, `nomUtilisateur`, `message`, `type`, `userID`) VALUES
(8, 'laury', 'Joyeux anniversaire !', 'NORMAL', 'c06bb641d52cbf15347db9164b82d91b9004634819d5a90a4231ac54fe0f2b59'),
(23, 'prof', '', 'CAGNOTTE', '73e77f3a9706886f61af18cc129425a63d88f24f752a44a8897574cfba54e13d'),
(26, 'pa', NULL, 'NORMAL', '84c4a57bb1574561183fd9304cdee758994332c2ae7b1c77c8d1521a781b8387'),
(27, 'laury', 'Ca va être super', 'NORMAL', 'c06bb641d52cbf15347db9164b82d91b9004634819d5a90a4231ac54fe0f2b59'),
(43, 'Fabien', 'Joyeux anniversaire !', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(67, 'Fabien', 'texte', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(68, 'Fabien', 'texte', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(70, 'Fabien', 'qsdqsdq', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(73, 'Fabien', 'i&#13;&#10;', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd4'),
(74, 'Fabien', 'qsdfsz', 'NORMAL', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(77, 'prof', 'Trop bien !!', 'NORMAL', '73e77f3a9706886f61af18cc129425a63d88f24f752a44a8897574cfba54e13d'),
(79, 'Fabien', '', 'CAGNOTTE', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46'),
(80, 'Fabien', '', 'CAGNOTTE', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `nom` varchar(60) NOT NULL,
  `prenom` varchar(60) NOT NULL,
  `login` varchar(60) NOT NULL,
  `password` varchar(256) NOT NULL,
  `mail` varchar(320) NOT NULL,
  `uid` varchar(72) NOT NULL,
  PRIMARY KEY (`uid`,`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`nom`, `prenom`, `login`, `password`, `mail`, `uid`) VALUES
('Prof', 'Prof', 'prof', '$2y$12$dOwU9C6j90Vg6cVvgqTaDe9UXgoSnMU7TPojWouDObSZvU/xl/Qhq', 'prof@prof.pf', '73e77f3a9706886f61af18cc129425a63d88f24f752a44a8897574cfba54e13d'),
('Fourriere', 'Paul-Alexandre', 'pa1007', '$2y$12$j0FAqmE2L1akHUQ23bwrMeC.gIx8OeJzXsd/UxqLiMTxYTdouXJ2a', 'paul-alexandre.fourriere5@etu.univ-lorraine.fr', '84c4a57bb1574561183fd9304cdee758994332c2ae7b1c77c8d1521a781b8387'),
('Froehlicher', 'Matthias', 'Lasartus', '$2y$12$Xfz0ptIjgBMXPnaAHR.nIeqQb/7CUM05kxw44MarSSYn6doHblmFC', 'ah@gmail.com', 'ac995a750f727be3b97e444e29153b447144770601670e34485fa0f3a44d47d2'),
('Thiebaux', 'Laury', 'laury', '$2y$12$jXRk5SxXsxVnCMfD2nLd2.BvFs5trTY41ByIg.oEiG5kGcl3Pn4F.', 'laury.thiebaux1@etu.univ-lorraine.fr', 'c06bb641d52cbf15347db9164b82d91b9004634819d5a90a4231ac54fe0f2b59'),
('Drommer', 'Fabien', 'Fabien', '$2y$12$1yxnLYXdUkRTsHGGrxlq1.bxMrllfuzmdyKsxeiRAmfEqgJsxPlQC', 'fabien.drommer1@etu.univ-lorraine.fr', 'cd8c82711b38ac682dd68ec23452ab715cd8b79ae46c8fbfb56e1de204b5bd46');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
