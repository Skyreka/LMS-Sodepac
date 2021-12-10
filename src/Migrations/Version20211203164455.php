<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211203164455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD parent_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A2C7E20A FOREIGN KEY (parent_product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A2C7E20A ON products (parent_product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A2C7E20A');
        $this->addSql('DROP INDEX IDX_B3BA5A5A2C7E20A ON products');
        $this->addSql('ALTER TABLE products DROP parent_product_id');
    }
}
