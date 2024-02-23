<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216102250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE traits (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, rarete VARCHAR(255) NOT NULL, type_de_trait VARCHAR(255) NOT NULL, date_de_creation DATE NOT NULL, couleur VARCHAR(255) NOT NULL, projets_id INT DEFAULT NULL, INDEX IDX_E4A0A166597A6CB7 (projets_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE traits ADD CONSTRAINT FK_E4A0A166597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE traits DROP FOREIGN KEY FK_E4A0A166597A6CB7');
        $this->addSql('DROP TABLE traits');
    }
}
