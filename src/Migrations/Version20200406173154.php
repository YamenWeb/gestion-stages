<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406173154 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE etudant');
        $this->addSql('ALTER TABLE encadrant CHANGE numero telephone INT NOT NULL');
        $this->addSql('ALTER TABLE etudiant ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD date_naissance DATE NOT NULL, ADD sexe INT NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE societe DROP adresse, CHANGE telephone telephone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369FCF77503');
        $this->addSql('DROP INDEX IDX_C27C9369FCF77503 ON stage');
        $this->addSql('ALTER TABLE stage CHANGE formation_id formation_id INT NOT NULL, CHANGE societe_id societee_id INT NOT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369BF613A9 FOREIGN KEY (societee_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369BF613A9 ON stage (societee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE etudant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_naissance DATE NOT NULL, sexe INT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE encadrant CHANGE telephone numero INT NOT NULL');
        $this->addSql('ALTER TABLE etudiant DROP nom, DROP prenom, DROP date_naissance, DROP sexe, DROP email, DROP ville');
        $this->addSql('ALTER TABLE societe ADD adresse LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone INT NOT NULL');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369BF613A9');
        $this->addSql('DROP INDEX IDX_C27C9369BF613A9 ON stage');
        $this->addSql('ALTER TABLE stage CHANGE formation_id formation_id INT DEFAULT NULL, CHANGE societee_id societe_id INT NOT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369FCF77503 ON stage (societe_id)');
    }
}
