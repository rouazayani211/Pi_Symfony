<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418131420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY articles_ibfk_1');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY relation');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY fk_equipe');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE journalists');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reserevation');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE evenement MODIFY ID_event INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON evenement');
        $this->addSql('ALTER TABLE evenement CHANGE ID_event id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE terrain MODIFY ID_terrain INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON terrain');
        $this->addSql('ALTER TABLE terrain CHANGE ID_terrain id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE terrain ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, journalist_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, content TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, creation_date DATE DEFAULT NULL, INDEX journalist_id (journalist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (id_commande INT AUTO_INCREMENT NOT NULL, id_produit INT NOT NULL, date_commande DATE NOT NULL, montant_total INT NOT NULL, mode_paiement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse_livraison VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_produit (id_produit), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE equipe (id_equipe INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, region VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ligue VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, classement INT NOT NULL, PRIMARY KEY(id_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, id_equipe INT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, numero INT NOT NULL, position VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_equipe (id_equipe), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE journalists (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, education VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, independent TINYINT(1) DEFAULT NULL, current_company VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, stock_disponible INT NOT NULL, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reserevation (IdReservation INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, dateDeReservation VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nombreTicket INT NOT NULL, statutReservation VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(IdReservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket (IdTicket INT AUTO_INCREMENT NOT NULL, IdReservation INT NOT NULL, NumSiege INT NOT NULL, prix DOUBLE PRECISION NOT NULL, DateEvenement VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, StatutTicket VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(IdTicket)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_naiss VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, role VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT articles_ibfk_1 FOREIGN KEY (journalist_id) REFERENCES journalists (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT relation FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT fk_equipe FOREIGN KEY (id_equipe) REFERENCES equipe (id_equipe) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE evenement MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON evenement');
        $this->addSql('ALTER TABLE evenement CHANGE id ID_event INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD PRIMARY KEY (ID_event)');
        $this->addSql('ALTER TABLE terrain MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON terrain');
        $this->addSql('ALTER TABLE terrain CHANGE id ID_terrain INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE terrain ADD PRIMARY KEY (ID_terrain)');
    }
}
