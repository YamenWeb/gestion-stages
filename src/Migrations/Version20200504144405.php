<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200504144405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stage_etudiant DROP FOREIGN KEY FK_7999E68EDDEAB1A3');
        $this->addSql('DROP TABLE etudiant_old');
        $this->addSql('DROP TABLE user_old');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES encadrant (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE stage_etudiant DROP FOREIGN KEY FK_7999E68EDDEAB1A3');
        $this->addSql('ALTER TABLE stage_etudiant ADD CONSTRAINT FK_7999E68EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE etudiant_old (id INT AUTO_INCREMENT NOT NULL, encadrant_id INT DEFAULT NULL, formation_id INT NOT NULL, date_naissance DATE NOT NULL, sexe INT NOT NULL, ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_717E22E35200282E (formation_id), INDEX IDX_717E22E3FEF1BA4 (encadrant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_old (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, discr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE etudiant_old ADD CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE etudiant_old ADD CONSTRAINT FK_717E22E3FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES encadrant (id)');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3FEF1BA4');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E35200282E');
        $this->addSql('ALTER TABLE stage_etudiant DROP FOREIGN KEY FK_7999E68EDDEAB1A3');
        $this->addSql('ALTER TABLE stage_etudiant ADD CONSTRAINT FK_7999E68EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant_old (id) ON DELETE CASCADE');
    }
}
