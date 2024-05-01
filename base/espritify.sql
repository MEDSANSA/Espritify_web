-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 01 mai 2024 à 12:30
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `espritify`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `type`) VALUES
(1, 'fgel'),
(2, 'java');

-- --------------------------------------------------------

--
-- Structure de la table `certification`
--

CREATE TABLE `certification` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_quizz` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

CREATE TABLE `club` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `emailClub` varchar(255) NOT NULL,
  `pageFb` varchar(255) NOT NULL,
  `pageInsta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `club`
--

INSERT INTO `club` (`id`, `intitule`, `logo`, `emailClub`, `pageFb`, `pageInsta`) VALUES
(1, 'ds', 'aaaa', 'ds', 'aaa', 'aa'),
(2, 'club', 'C:/xampp/htdocs/icons8_Meanpath_64px_2.png', 'club@gmail.com', 'pagefb', 'pageinsta');

-- --------------------------------------------------------

--
-- Structure de la table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `likes` int(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversation`
--

INSERT INTO `conversation` (`id`, `id_user`, `titre`, `description`, `likes`, `date`) VALUES
(23, 56, 'ariana soghra', '7ofraaaaa', 7, '2024-02-26 22:22:25'),
(26, 56, 'azerty', 'azerty', NULL, '2024-04-01 14:24:21');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `title`, `etat`, `contenu`, `id_cat`, `rate`) VALUES
(1, 'math', 0, 'icons8_Meanpath_64px_2.png', 1, 0),
(3, 'sasasa', 0, 'Capture d\'écran 2024-01-29 204336.png', 1, 0),
(4, 'zruytfdsq', 0, 'Capture d’écran 2022-03-26 191109.png', 1, 0),
(5, 'ons', 1, '8b97be946dbb4e9be5f9e42b5f87909e.jpg', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dossier_stage`
--

CREATE TABLE `dossier_stage` (
  `id_user` int(11) NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `convention` varchar(255) NOT NULL,
  `copie_cin` varchar(255) NOT NULL,
  `id_offre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entretien`
--

CREATE TABLE `entretien` (
  `id_user` int(11) NOT NULL,
  `id_stage` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `id_club` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `id_club`, `titre`, `description`, `lieu`, `dateDebut`, `dateFin`) VALUES
(3, 1, 'qsqs', 'sq', 'Equinor, Corridor Mohammed V, Lafayat, Al-Hadayek, délégation Bab Bahr, Tunisie, Gouvernorat de Tunis, 1017, Tunisie', '2024-03-09', '2024-03-16'),
(4, 2, 'EVENEMENGT', 'EVENMENT', 'Jordan Road, Lafayat, Al-Hadayek, Délégation Bab Bahr, Tunisie, Gouvernorat de Tunis, 1017, Tunisie', '2024-03-01', '2024-03-10');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_conv` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `id_user`, `id_conv`, `date`, `description`) VALUES
(32, 56, 23, '2024-03-04 02:00:28', 'jawed'),
(33, 56, 23, '2024-03-04 15:51:13', 'aziz sansa'),
(34, 56, 23, '2024-03-04 15:51:15', 'aziz sansa'),
(35, 56, 23, '2024-03-05 10:18:56', 'azer');

-- --------------------------------------------------------

--
-- Structure de la table `offrestage`
--

CREATE TABLE `offrestage` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `competance` varchar(255) NOT NULL,
  `desc_soc` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `nom_soc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offrestage`
--

INSERT INTO `offrestage` (`id`, `titre`, `description`, `competance`, `desc_soc`, `type`, `nom_soc`) VALUES
(8, 'aaa', 'aa', 'aa', 'aa', 'remote', 'aa');

-- --------------------------------------------------------

--
-- Structure de la table `participation_cours`
--

CREATE TABLE `participation_cours` (
  `id_cours` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participation_evenement`
--

CREATE TABLE `participation_evenement` (
  `id_user` int(11) NOT NULL,
  `id_evenement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participation_evenement`
--

INSERT INTO `participation_evenement` (`id_user`, `id_evenement`) VALUES
(59, 3);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_token`
--

CREATE TABLE `password_reset_token` (
  `token` varchar(255) NOT NULL,
  `user_id` int(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `password_reset_token`
--

INSERT INTO `password_reset_token` (`token`, `user_id`, `timestamp`, `email`) VALUES
('17096', 59, '2024-03-05 07:54:05', 'azizsansa@hmed.tn'),
('23815', 55, '2024-03-04 11:24:21', ''),
('35126', 59, '2024-03-05 07:54:31', 'azizsansa@hmed.tn'),
('39960', 65, '2024-03-05 08:01:09', 'abdeljawedchlibi@gmail.com'),
('41673', 66, '2024-03-05 08:45:43', 'sarahhammami15@gmail.com'),
('74346', 56, '2024-03-04 11:26:38', 'ahmed.hmid@gmail.tn'),
('88008', 61, '2024-03-05 08:02:17', 'sarahhammami15@gmail.com'),
('94982', 61, '2024-03-05 08:11:04', 'sarahhammami15@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id_question` int(11) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `rep1` varchar(255) NOT NULL,
  `rep2` varchar(255) NOT NULL,
  `rep3` varchar(255) NOT NULL,
  `bon_rep` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id_question`, `contenu`, `rep1`, `rep2`, `rep3`, `bon_rep`) VALUES
(1, 'dsddd', 'aziz', 'ons', 'sarrah', 'aziz');

-- --------------------------------------------------------

--
-- Structure de la table `quizz`
--

CREATE TABLE `quizz` (
  `id_quizz` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quizz`
--

INSERT INTO `quizz` (`id_quizz`, `id_question`, `sujet`, `description`) VALUES
(1, 1, 'azaz', 'azaz');

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `id` int(8) NOT NULL,
  `id_user` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `etat` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`id`, `id_user`, `titre`, `description`, `date`, `etat`) VALUES
(100, 56, 'reclamation', 'finished', '2024-02-24', 'traité'),
(101, 56, 'spider', 'el 3ardh b 500', '2024-02-24', 'traité'),
(103, 55, 'aaaa', 'bbbbb', '2024-02-28', 'non traité');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_rec`
--

CREATE TABLE `reponse_rec` (
  `id` int(8) NOT NULL,
  `id_rec` int(8) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponse_rec`
--

INSERT INTO `reponse_rec` (`id`, `id_rec`, `titre`, `description`, `date`) VALUES
(88, 100, 'w l3ardh b 500', 'w maeha chanta hdeya', '2024-02-24'),
(94, 101, 'aaaa', 'aaaaaa', '2024-02-26');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `tel` int(8) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rank` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `nom_societe` varchar(255) DEFAULT NULL,
  `niveau` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `mdp`, `tel`, `image`, `rank`, `score`, `nom_societe`, `niveau`, `role`) VALUES
(55, 'sarrah', 'hammai', '', '', 0, '', '', 0, 'actia', '3a', 'admin'),
(56, 'ahmed', 'hmid', 'ahmed.hmid@gmail.tn', 'ahmed', 89999, 'ahmed', 'ahmed', 9999, 'actia', '3a', 'etudiant'),
(57, 'hmid', 'ahmed', 'hmidahmed049@gmail.com', '$2a$10$swhKbFs1yXqdprFhYft66eylRrrxG.pVWeCWYCjpsREk2nAUrJcom', 0, 'img.jpg', NULL, NULL, NULL, '3a', 'admin'),
(58, 'aziz', 'sansa', 'azizsansa4@gmail.com', 'azizsansa123', 56701366, 'gdfgfdg', '1', 0, 'jkfjdsfd', '0', 'etudiant'),
(59, 'aziz', 'sansa', 'azizsansa@hmed.tn', '$2a$10$iE1TDPtHyNPI8KwSRX8OP.oY2qTJWFDC5glw7jRudwdJNgLhI2Mg.', 0, NULL, NULL, NULL, NULL, '1', 'etudiant'),
(60, 'ahmed', 'ons', 'ons.ahmed@gmail.com', '$2a$10$lPaEGyMZWEopOsb5LmJts.QSwnR1V32kIjh/UzhZbF0v8tWvaSWIm', 0, NULL, NULL, NULL, NULL, '0', 'enseignant'),
(63, 'imen', 'imen', 'imen@gmail.com', '$2a$10$9WAtnei.fxInyaXAh/3Bj.CGhhJu0qwBRtY8vP7V/8Wq5OtbEw.hG', 4566, 'C:/xampp/htdocs/image.png', NULL, NULL, NULL, 'null', 'etudiant'),
(64, 'merdes', 'arij', 'arijmer@gmail.com', '$2a$10$rVQ8w8sahNXXyjJvtIdhTuPT/1J3/PcvR/7OVeGkToye0jV6ogmlC', 43554545, 'null', NULL, NULL, NULL, 'null', 'admin'),
(65, 'chlibi', 'chlibi', 'abdeljawedchlibi@gmail.com', '$2a$10$VM7KHAAGXBv1IJsODN21M.ILO2B3DhUsRU13u3qZzWhk0QktMY5B2', 1212321, 'C:/xampp/htdocs/Capture d\'écran 2024-03-03 182549.png', NULL, NULL, NULL, NULL, 'etudiant'),
(66, 'abdeljawed', 'chlibi', 'sarahhammami15@gmail.com', '$2a$10$PP.4xWfEY9NUJOSO61aLd.WMvwKG4/5tbglykx8Zwpu6CpoQZO6LS', 52481259, 'C:/xampp/htdocs/espritify-removebg-preview.png', NULL, NULL, NULL, NULL, 'etudiant'),
(67, '3saaaaaaa', '3saaaaaa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'bbbb', 'aaa', 'jawed10@gmail.com', '$2y$13$Ihs5BmG6FRBoZApQCHNjnu4ND28rfUQiyeTE22Pr7rF1VrzmY/LF2', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cer_user` (`id_user`),
  ADD KEY `fk_cert_quiz` (`id_quizz`);

--
-- Index pour la table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_conv` (`id_user`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cat` (`id_cat`);

--
-- Index pour la table `dossier_stage`
--
ALTER TABLE `dossier_stage`
  ADD PRIMARY KEY (`id_user`,`id_offre`),
  ADD KEY `fk_offre` (`id_offre`);

--
-- Index pour la table `entretien`
--
ALTER TABLE `entretien`
  ADD PRIMARY KEY (`id_user`,`id_stage`),
  ADD KEY `fk_stage` (`id_stage`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_club` (`id_club`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conv_user1` (`id_conv`),
  ADD KEY `fk_msg_user` (`id_user`);

--
-- Index pour la table `offrestage`
--
ALTER TABLE `offrestage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `participation_cours`
--
ALTER TABLE `participation_cours`
  ADD PRIMARY KEY (`id_cours`,`id_utilisateur`),
  ADD KEY `fk_user_p` (`id_utilisateur`);

--
-- Index pour la table `participation_evenement`
--
ALTER TABLE `participation_evenement`
  ADD PRIMARY KEY (`id_user`,`id_evenement`),
  ADD KEY `fk_parti_club` (`id_evenement`);

--
-- Index pour la table `password_reset_token`
--
ALTER TABLE `password_reset_token`
  ADD PRIMARY KEY (`token`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id_question`);

--
-- Index pour la table `quizz`
--
ALTER TABLE `quizz`
  ADD PRIMARY KEY (`id_quizz`),
  ADD KEY `fk_id_question` (`id_question`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_uti` (`id_user`);

--
-- Index pour la table `reponse_rec`
--
ALTER TABLE `reponse_rec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rep_rec` (`id_rec`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `certification`
--
ALTER TABLE `certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `club`
--
ALTER TABLE `club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `offrestage`
--
ALTER TABLE `offrestage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `quizz`
--
ALTER TABLE `quizz`
  MODIFY `id_quizz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT pour la table `reponse_rec`
--
ALTER TABLE `reponse_rec`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `fk_cer_user` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_id_quizz` FOREIGN KEY (`id_quizz`) REFERENCES `quizz` (`id_quizz`);

--
-- Contraintes pour la table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `fk_user_conv` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `fk_cat` FOREIGN KEY (`id_cat`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `dossier_stage`
--
ALTER TABLE `dossier_stage`
  ADD CONSTRAINT `fk_dossier_stage` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_offre` FOREIGN KEY (`id_offre`) REFERENCES `offrestage` (`id`);

--
-- Contraintes pour la table `entretien`
--
ALTER TABLE `entretien`
  ADD CONSTRAINT `fk_stage` FOREIGN KEY (`id_stage`) REFERENCES `offrestage` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `fk_club` FOREIGN KEY (`id_club`) REFERENCES `club` (`id`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_conv_user` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_conv_user1` FOREIGN KEY (`id_conv`) REFERENCES `conversation` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `participation_cours`
--
ALTER TABLE `participation_cours`
  ADD CONSTRAINT `fk_part_cours` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `fk_user_p` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `participation_evenement`
--
ALTER TABLE `participation_evenement`
  ADD CONSTRAINT `fk_part_eve` FOREIGN KEY (`id_evenement`) REFERENCES `evenement` (`id`),
  ADD CONSTRAINT `fk_part_user` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `quizz`
--
ALTER TABLE `quizz`
  ADD CONSTRAINT `fk_id_question` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id_question`);

--
-- Contraintes pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `fk_id_uti` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `reponse_rec`
--
ALTER TABLE `reponse_rec`
  ADD CONSTRAINT `fk_rep_rec` FOREIGN KEY (`id_rec`) REFERENCES `reclamation` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
