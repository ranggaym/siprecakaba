-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 05 Jan 2016 pada 03.46
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `siprecakaba`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_uji`
--

DROP TABLE IF EXISTS `data_uji`;
CREATE TABLE IF NOT EXISTS `data_uji` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `ipk` varchar(10) NOT NULL,
  `psi` varchar(10) NOT NULL,
  `ww` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data untuk tabel `data_uji`
--

INSERT INTO `data_uji` (`no`, `ipk`, `psi`, `ww`, `class`) VALUES
(1, 'bagus', 'tinggi', 'buruk', 'tidak'),
(2, 'bagus', 'rendah', 'baik', 'ya'),
(3, 'cukup', 'tinggi', 'buruk', 'tidak'),
(4, 'cukup', 'rendah', 'baik', 'ya'),
(5, 'kurang', 'tinggi', 'buruk', 'tidak'),
(6, 'kurang', 'sedang', 'baik', 'ya'),
(7, 'kurang', 'rendah', 'buruk', 'tidak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelamar`
--

DROP TABLE IF EXISTS `pelamar`;
CREATE TABLE IF NOT EXISTS `pelamar` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `ipk` varchar(10) NOT NULL,
  `psi` varchar(10) NOT NULL,
  `ww` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data untuk tabel `pelamar`
--

INSERT INTO `pelamar` (`no`, `ipk`, `psi`, `ww`, `class`) VALUES
(1, 'bagus', 'tinggi', 'sbaik', 'ya'),
(2, 'bagus', 'tinggi', 'sburuk', 'ya'),
(3, 'bagus', 'sedang', 'buruk', 'ya'),
(4, 'bagus', 'rendah', 'buruk', 'tidak'),
(5, 'cukup', 'tinggi', 'sbaik', 'ya'),
(6, 'cukup', 'sedang', 'buruk', 'ya'),
(7, 'cukup', 'sedang', 'sburuk', 'ya'),
(8, 'cukup', 'rendah', 'baik', 'ya'),
(9, 'cukup', 'rendah', 'buruk', 'tidak'),
(10, 'kurang', 'tinggi', 'sbaik', 'ya'),
(11, 'kurang', 'tinggi', 'sburuk', 'tidak'),
(12, 'kurang', 'sedang', 'sbaik', 'tidak'),
(13, 'kurang', 'sedang', 'baik', 'tidak'),
(14, 'kurang', 'rendah', 'sburuk', 'tidak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prediksi`
--

DROP TABLE IF EXISTS `prediksi`;
CREATE TABLE IF NOT EXISTS `prediksi` (
  `inst` int(11) NOT NULL,
  `actual` varchar(10) NOT NULL,
  `predicted` varchar(11) NOT NULL,
  `error` varchar(11) NOT NULL,
  `prediction` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `prediksi`
--

INSERT INTO `prediksi` (`inst`, `actual`, `predicted`, `error`, `prediction`) VALUES
(1, '2:tidak', '1:ya', '+', '1'),
(2, '1:ya', '1:ya', '', '1'),
(3, '2:tidak', '1:ya', '+', '1'),
(4, '1:ya', '1:ya', '', '1'),
(5, '2:tidak', '2:tidak', '', '1'),
(6, '1:ya', '2:tidak', '+', '1'),
(7, '2:tidak', '2:tidak', '', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
