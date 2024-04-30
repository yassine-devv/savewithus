-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 30, 2024 alle 09:45
-- Versione del server: 10.1.36-MariaDB
-- Versione PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `savewithus`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratori`
--

CREATE TABLE `amministratori` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `id_blog` int(11) NOT NULL,
  `titolo` varchar(50) NOT NULL,
  `testo` varchar(700) NOT NULL,
  `created` date NOT NULL,
  `stato` int(11) NOT NULL,
  `autore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `campagne`
--

CREATE TABLE `campagne` (
  `id_campagna` int(11) NOT NULL,
  `nome_campagna` varchar(30) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `giorno_ritrovo` date NOT NULL,
  `foto` varchar(100) NOT NULL,
  `stato` int(11) NOT NULL,
  `autore` int(11) NOT NULL,
  `luogo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `cod_tokens`
--

CREATE TABLE `cod_tokens` (
  `token` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `cod_tokens`
--

INSERT INTO `cod_tokens` (`token`, `id_user`) VALUES
('$2y$10$WO6Em/7BcNdyVhI7ZDshDudFBZRhhjn0mlDA3AUu8SPxZZjVoMOJ.', 3),
('$2y$10$F3Fd7cg/9K6oOfqxvtfAJuCrA.ksjwF/cmwgUAJ9SwGgrJPDgPQ6a', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `eventi`
--

CREATE TABLE `eventi` (
  `id_evento` int(11) NOT NULL,
  `nome_evento` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `luogo` varchar(20) NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipanti_camapgne`
--

CREATE TABLE `partecipanti_camapgne` (
  `id_user` int(11) NOT NULL,
  `id_campagna` int(11) NOT NULL,
  `commento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipanti_eventi`
--

CREATE TABLE `partecipanti_eventi` (
  `id_evento` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `presente_online` char(8) NOT NULL,
  `recensione` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_user` int(11) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `cognome` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `num_tel` char(13) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` char(60) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_user`, `nome`, `cognome`, `email`, `num_tel`, `username`, `password`, `created`) VALUES
(1, 'Mario', 'Rossi', '123456789', 'mariorossi@gm', 'mario79', '$2y$10$xuhFGheXxOe7mwfklOPDEudnyWWbyTnQjMovn8OLINWTDY8xHIh7y', '0000-00-00 00:00:00'),
(2, 'Mario', 'Rossi', 'mariorossi@gmail.com', '123456789', 'mario79', '$2y$10$13HpRrhAqh7K/gSUbOul6urTNclHJrYIFcv5vJboqQfn74f.Qpgr.', '2024-04-28 19:35:05'),
(3, 'Anna', 'Verdi', 'annaverdi@gmail.com', '1234567890', 'anna70', '$2y$10$SweKG.kILPtNBVST5LAoXO8P9iQ19GRJOw1/E3MaQ1LNjsIYrF8qW', '2024-04-30 09:13:57'),
(4, 'Federico', 'Rossi', 'federossi80@gmail.com', '1234567889', 'red80', '$2y$10$hSlouCFi4U7NWfJ2dO.GjuNo2c3qzOlJJFXiiG3BM58n7zS5UwXcm', '2024-04-30 09:27:41');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `amministratori`
--
ALTER TABLE `amministratori`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `vincolo_blog_user` (`autore`);

--
-- Indici per le tabelle `campagne`
--
ALTER TABLE `campagne`
  ADD PRIMARY KEY (`id_campagna`),
  ADD KEY `vincolo_campagna_autore` (`autore`);

--
-- Indici per le tabelle `cod_tokens`
--
ALTER TABLE `cod_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `id_user` (`id_user`);

--
-- Indici per le tabelle `eventi`
--
ALTER TABLE `eventi`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indici per le tabelle `partecipanti_camapgne`
--
ALTER TABLE `partecipanti_camapgne`
  ADD PRIMARY KEY (`id_user`,`id_campagna`),
  ADD KEY `vincolo_partecipanti_campagne_camp` (`id_campagna`);

--
-- Indici per le tabelle `partecipanti_eventi`
--
ALTER TABLE `partecipanti_eventi`
  ADD PRIMARY KEY (`id_evento`,`id_user`),
  ADD KEY `vincolo_partecipanti_eventi_user` (`id_user`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `amministratori`
--
ALTER TABLE `amministratori`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `campagne`
--
ALTER TABLE `campagne`
  MODIFY `id_campagna` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `eventi`
--
ALTER TABLE `eventi`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `vincolo_blog_user` FOREIGN KEY (`autore`) REFERENCES `utenti` (`id_user`);

--
-- Limiti per la tabella `campagne`
--
ALTER TABLE `campagne`
  ADD CONSTRAINT `vincolo_campagna_autore` FOREIGN KEY (`autore`) REFERENCES `utenti` (`id_user`);

--
-- Limiti per la tabella `cod_tokens`
--
ALTER TABLE `cod_tokens`
  ADD CONSTRAINT `cod_tokens_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id_user`);

--
-- Limiti per la tabella `partecipanti_camapgne`
--
ALTER TABLE `partecipanti_camapgne`
  ADD CONSTRAINT `vincolo_partecipanti_campagne_camp` FOREIGN KEY (`id_campagna`) REFERENCES `campagne` (`id_campagna`),
  ADD CONSTRAINT `vincolo_partecipanti_campagne_user` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id_user`);

--
-- Limiti per la tabella `partecipanti_eventi`
--
ALTER TABLE `partecipanti_eventi`
  ADD CONSTRAINT `vincolo_partecipanti_eventi_evento` FOREIGN KEY (`id_evento`) REFERENCES `eventi` (`id_evento`),
  ADD CONSTRAINT `vincolo_partecipanti_eventi_user` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
