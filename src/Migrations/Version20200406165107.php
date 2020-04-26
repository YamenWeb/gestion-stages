<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406165107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE encadrant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, numero INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, sexe INT NOT NULL, email VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, encadrant_id INT DEFAULT NULL, formation_id INT NOT NULL, INDEX IDX_717E22E3FEF1BA4 (encadrant_id), INDEX IDX_717E22E35200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, nb_annees INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nom_responsable VARCHAR(255) NOT NULL, telephone INT NOT NULL, email VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, societe_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, duree INT NOT NULL, date_debut DATE NOT NULL, disponibilitee TINYINT(1) NOT NULL, INDEX IDX_C27C93695200282E (formation_id), INDEX IDX_C27C9369FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_etudiant (stage_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_7999E68E2298D193 (stage_id), INDEX IDX_7999E68EDDEAB1A3 (etudiant_id), PRIMARY KEY(stage_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES encadrant (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C93695200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE stage_etudiant ADD CONSTRAINT FK_7999E68E2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_etudiant ADD CONSTRAINT FK_7999E68EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3FEF1BA4');
        $this->addSql('ALTER TABLE stage_etudiant DROP FOREIGN KEY FK_7999E68EDDEAB1A3');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E35200282E');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C93695200282E');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369FCF77503');
        $this->addSql('ALTER TABLE stage_etudiant DROP FOREIGN KEY FK_7999E68E2298D193');
        $this->addSql('DROP TABLE encadrant');
        $this->addSql('DROP TABLE etudant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE societe');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_etudiant');
    }
}
