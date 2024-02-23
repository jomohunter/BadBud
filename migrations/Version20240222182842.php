<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222182842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft ADD projets_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id)');
        $this->addSql('CREATE INDEX IDX_D9C7463C597A6CB7 ON nft (projets_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C597A6CB7');
        $this->addSql('DROP INDEX IDX_D9C7463C597A6CB7 ON nft');
        $this->addSql('ALTER TABLE nft DROP projets_id');
    }
}
