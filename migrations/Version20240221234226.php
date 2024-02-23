<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221234226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id ON actualite');
        $this->addSql('ALTER TABLE actualite CHANGE date_publication date_publication DATETIME NOT NULL');
        $this->addSql('ALTER TABLE actualite ADD image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualite CHANGE date_publication date_publication DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX id ON actualite (id)');
        $this->addSql('ALTER TABLE actualite DROP image');
    }
}
