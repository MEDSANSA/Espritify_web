<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327221542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_quizz INT DEFAULT NULL, type VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_6C3C6D756B3CA4B (id_user), INDEX IDX_6C3C6D7592F2D60D (id_quizz), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, emailClub VARCHAR(255) NOT NULL, pageFb VARCHAR(255) NOT NULL, pageInsta VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, likes INT DEFAULT NULL, date datetime DEFAULT CURRENT_TIMESTAMP, INDEX IDX_8A8E26E96B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, id_cat INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, contenu VARCHAR(255) NOT NULL, rate INT NOT NULL, INDEX IDX_FDCA8C9CFAABF2 (id_cat), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_stage (id_user INT NOT NULL, id_offre INT NOT NULL, cv VARCHAR(255) DEFAULT NULL, convention VARCHAR(255) NOT NULL, copie_cin VARCHAR(255) NOT NULL, INDEX IDX_BFC892E6B3CA4B (id_user), INDEX IDX_BFC892E4103C75F (id_offre), PRIMARY KEY(id_user, id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entretien (id_user INT NOT NULL, id_stage INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, lieu VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_2B58D6DA6B3CA4B (id_user), INDEX IDX_2B58D6DA2CF9D259 (id_stage), PRIMARY KEY(id_user, id_stage)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, id_club INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, INDEX IDX_B26681E33CE2470 (id_club), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_conv INT DEFAULT NULL, date datetime DEFAULT CURRENT_TIMESTAMP, description VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F6B3CA4B (id_user), INDEX IDX_B6BD307F826485CE (id_conv), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offrestage (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, competance VARCHAR(255) NOT NULL, desc_soc VARCHAR(255) NOT NULL, nom_soc VARCHAR(255) NOT NULL, type ENUM(\'remote\', \'presentiel\') NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_cours (id_cours INT NOT NULL, id_utilisateur INT NOT NULL, INDEX IDX_F2160117134FCDAC (id_cours), INDEX IDX_F216011750EAE44 (id_utilisateur), PRIMARY KEY(id_cours, id_utilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_evenement (id_user INT NOT NULL, id_evenement INT NOT NULL, INDEX IDX_65A146756B3CA4B (id_user), INDEX IDX_65A146758B13D439 (id_evenement), PRIMARY KEY(id_user, id_evenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password_reset_token (token INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, email VARCHAR(255) NOT NULL, INDEX IDX_6B7BA4B6A76ED395 (user_id), PRIMARY KEY(token)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions (id_question INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, rep1 VARCHAR(255) NOT NULL, rep2 VARCHAR(255) NOT NULL, rep3 VARCHAR(255) NOT NULL, bonneReponse VARCHAR(255) NOT NULL, PRIMARY KEY(id_question)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id_quizz INT AUTO_INCREMENT NOT NULL, id_question INT DEFAULT NULL, sujet VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_7C77973DE62CA5DB (id_question), PRIMARY KEY(id_quizz)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date date DEFAULT CURRENT_TIMESTAMP, etat VARCHAR(255) NOT NULL, INDEX IDX_CE6064046B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_rec (id INT AUTO_INCREMENT NOT NULL, id_rec INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date date DEFAULT CURRENT_TIMESTAMP, INDEX IDX_CA85808FAA12276 (id_rec), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, mdp VARCHAR(255) DEFAULT NULL, tel INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, rank VARCHAR(255) DEFAULT NULL, score INT DEFAULT NULL, nom_societe VARCHAR(255) DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, role ENUM(\'admin\', \'etudiant\', \'enseignant\', \'responsable_societe\') DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D756B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D7592F2D60D FOREIGN KEY (id_quizz) REFERENCES quizz (id_quizz)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E96B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CFAABF2 FOREIGN KEY (id_cat) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE dossier_stage ADD CONSTRAINT FK_BFC892E6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE dossier_stage ADD CONSTRAINT FK_BFC892E4103C75F FOREIGN KEY (id_offre) REFERENCES offrestage (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA2CF9D259 FOREIGN KEY (id_stage) REFERENCES offrestage (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E33CE2470 FOREIGN KEY (id_club) REFERENCES club (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F826485CE FOREIGN KEY (id_conv) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE participation_cours ADD CONSTRAINT FK_F2160117134FCDAC FOREIGN KEY (id_cours) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE participation_cours ADD CONSTRAINT FK_F216011750EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A146756B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A146758B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DE62CA5DB FOREIGN KEY (id_question) REFERENCES questions (id_question)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reponse_rec ADD CONSTRAINT FK_CA85808FAA12276 FOREIGN KEY (id_rec) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D756B3CA4B');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D7592F2D60D');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E96B3CA4B');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CFAABF2');
        $this->addSql('ALTER TABLE dossier_stage DROP FOREIGN KEY FK_BFC892E6B3CA4B');
        $this->addSql('ALTER TABLE dossier_stage DROP FOREIGN KEY FK_BFC892E4103C75F');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA6B3CA4B');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA2CF9D259');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E33CE2470');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6B3CA4B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F826485CE');
        $this->addSql('ALTER TABLE participation_cours DROP FOREIGN KEY FK_F2160117134FCDAC');
        $this->addSql('ALTER TABLE participation_cours DROP FOREIGN KEY FK_F216011750EAE44');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A146756B3CA4B');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A146758B13D439');
        $this->addSql('ALTER TABLE password_reset_token DROP FOREIGN KEY FK_6B7BA4B6A76ED395');
        $this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973DE62CA5DB');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reponse_rec DROP FOREIGN KEY FK_CA85808FAA12276');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE dossier_stage');
        $this->addSql('DROP TABLE entretien');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offrestage');
        $this->addSql('DROP TABLE participation_cours');
        $this->addSql('DROP TABLE participation_evenement');
        $this->addSql('DROP TABLE password_reset_token');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse_rec');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
