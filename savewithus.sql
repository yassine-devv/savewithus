-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 04, 2024 alle 00:56
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `password` char(60) NOT NULL,
  `azione_utenti` char(5) DEFAULT NULL,
  `azione_campagne` char(5) DEFAULT NULL,
  `azione_blog` char(5) DEFAULT NULL,
  `azione_eventi` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `amministratori`
--

INSERT INTO `amministratori` (`id_admin`, `username`, `password`, `azione_utenti`, `azione_campagne`, `azione_blog`, `azione_eventi`) VALUES
(1, 'admin', '$2y$10$nBR97QjHJoTGCL2Hnapmoe4KTy31LFMm39VdrXoLUfDNMm1DzxiPy', 'true', 'true', 'true', 'true');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`id_blog`, `titolo`, `testo`, `created`, `stato`, `autore`) VALUES
(1, 'Centri radioattivi', 'questo blog parla dei centri radioattivi in italia', '2024-05-30', 2, 2),
(3, 'Blog di Anna', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2024-05-30', 2, 5),
(4, 'Incremento rifiuti', 'Questo blog affronta il discorso inerente al incremento dei rifiuti sparsi in Italia, dove non dovrebbero esserci', '2024-05-30', 1, 7),
(5, 'Titolo', 'ciao mamma', '2024-06-02', 0, 2),
(6, 'bloggy', 'contenuto bello eco modificato', '2024-06-03', 2, 9);

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
  `luogo` varchar(50) NOT NULL,
  `latitudine` varchar(255) NOT NULL,
  `longitudine` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `campagne`
--

INSERT INTO `campagne` (`id_campagna`, `nome_campagna`, `descrizione`, `giorno_ritrovo`, `foto`, `stato`, `autore`, `luogo`, `latitudine`, `longitudine`) VALUES
(6, 'Segnalazione a Roma', 'Area di periferia di Roma messa male, piena di rifiuti lasciati dai cittadini', '2024-05-23', 'img-cite-log.jpg,img-cite-reg.jpeg,img-segnala-home.jpg, ', 2, 2, 'Roma, Lazio, Italia', '41.89332030', '12.48293210'),
(7, 'Area Napoli', 'area in periferia di napoli molto sporca a causa dei cittadini, e brutta da guardare passando.', '2024-05-31', 'img-cite-reg.jpeg,img-segnala-home.jpg, ', 1, 2, 'Napoli, Campania, Italia', '40.83588460', '14.24876790'),
(8, 'Nuova campanga', 'descrizione piccola', '2024-05-25', 'img_banner_camp.jpg,img-cite-log.jpg,img-cite-reg.jpeg, ', 2, 2, 'Milano, Lombardia, Italia', '45.45421190', '9.11135096'),
(11, 'Area Milano', 'Segnalo un\'area in periferia di Milano, molto sporca a causa di vandalismo.\r\nMi servono dei volontari per l\'aiuto.', '2024-06-21', 'borraccia6.png,borraccia5.png,borraccia4.png,', 2, 5, 'Milano Quarto Oggiaro, Via Carlo Amoretti, Bovisas', '45.51919560', '9.14563850'),
(12, 'zona sporca', 'dobbiamo raccogliere la spazzatura', '2024-06-03', '', 2, 9, 'Viterbo, Lazio, Italia', '42.49295220', '11.94881355'),
(13, 'Campagna2', 'segnalazione campagna con nuovo design', '2024-08-21', 'borraccia1.png,borraccia2.png,', 1, 5, 'Renate, Monza and Brianza, Lombardia, 20838, Itali', '45.72437800', '9.27994400'),
(14, 'fsd', 'fds', '2024-01-01', 'borraccia1.png,borraccia2.png,', 1, 5, 'Renate, Monza and Brianza, Lombardia, 20838, Itali', '45.72437800', '9.27994400');

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipanti_camapgne`
--

CREATE TABLE `partecipanti_camapgne` (
  `id_user` int(11) NOT NULL,
  `id_campagna` int(11) NOT NULL,
  `commento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `partecipanti_camapgne`
--

INSERT INTO `partecipanti_camapgne` (`id_user`, `id_campagna`, `commento`) VALUES
(2, 6, NULL),
(2, 7, NULL),
(2, 11, 'sarei interessato a essere volontario per questa campagne'),
(5, 6, 'Ciao d\'adda'),
(6, 11, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tokens_utenti`
--

CREATE TABLE `tokens_utenti` (
  `id_token` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `scandenza` datetime NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tokens_utenti`
--

INSERT INTO `tokens_utenti` (`id_token`, `id_user`, `token`, `scandenza`, `created`) VALUES
(9, 5, '5da4ced2f6e1cf0197fbdba3098f5dfb', '2024-06-18 15:00:22', '2024-06-03 13:00:22'),
(10, 2, '0e2fff01b9fd154906d245566072d343', '2024-06-18 15:21:09', '2024-06-03 13:21:09'),
(11, 6, '8e40006e98cb8e4626e2d6aab2b919b4', '2024-06-18 15:24:51', '2024-06-03 13:24:51');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_user`, `nome`, `cognome`, `email`, `num_tel`, `username`, `password`, `created`) VALUES
(2, 'Mario', 'Rossi', 'mariorossi@gmail.com', '123456789', 'mario79', '$2y$10$13HpRrhAqh7K/gSUbOul6urTNclHJrYIFcv5vJboqQfn74f.Qpgr.', '2024-04-28 19:35:05'),
(5, 'Anna', 'Verdi', 'annaverdi@gmail.com', '2234567890', 'anna90', '$2y$10$2GcSkCvM6rxBjzNMJAKpIu6IZK5wqGfzRpeQMqCi5MuDytahcp3f.', '2024-04-30 18:42:14'),
(6, 'Federico', 'Sala', 'federicosala@gmail.com', '1234567890', 'fede80', '$2y$10$K6dZ86ruM5v1UGznfb948.wLluBT1L7LsPZ2wJgNCOry4gUB90t2W', '2024-05-02 21:58:38'),
(7, 'Federico', 'Rossi', 'federicorossi@gmail.com', '1234567890', 'federed80', '$2y$10$/oStji.cVGvVo37xF1SI0uMTLe6CMOZ10XWVZK.9dREuJsrU79mBy', '2024-05-30 21:18:02'),
(9, 'yusef', 'eff', 'yuse@test.com', '3492388772', 'yuse', '$2y$10$YYhhd/ZAHl04oZX6jHDC9eoa8he82rTnAiwtkpLfrGKO1rpFwf/8u', '2024-06-03 19:39:49');

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
-- Indici per le tabelle `partecipanti_camapgne`
--
ALTER TABLE `partecipanti_camapgne`
  ADD PRIMARY KEY (`id_user`,`id_campagna`),
  ADD KEY `vincolo_partecipanti_campagne_camp` (`id_campagna`);

--
-- Indici per le tabelle `tokens_utenti`
--
ALTER TABLE `tokens_utenti`
  ADD PRIMARY KEY (`id_token`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_user` (`id_user`);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `campagne`
--
ALTER TABLE `campagne`
  MODIFY `id_campagna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `tokens_utenti`
--
ALTER TABLE `tokens_utenti`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Limiti per la tabella `partecipanti_camapgne`
--
ALTER TABLE `partecipanti_camapgne`
  ADD CONSTRAINT `vincolo_partecipanti_campagne_camp` FOREIGN KEY (`id_campagna`) REFERENCES `campagne` (`id_campagna`),
  ADD CONSTRAINT `vincolo_partecipanti_campagne_user` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id_user`);

--
-- Limiti per la tabella `tokens_utenti`
--
ALTER TABLE `tokens_utenti`
  ADD CONSTRAINT `tokens_utenti_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
