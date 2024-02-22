<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219134440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE cart_nft (cart_id INT NOT NULL, nft_id INT NOT NULL, INDEX IDX_E9D7557F1AD5CDBF (cart_id), INDEX IDX_E9D7557FE813668D (nft_id), PRIMARY KEY(cart_id, nft_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557F1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557FE813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557F1AD5CDBF');
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557FE813668D');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_nft');
    }
}
