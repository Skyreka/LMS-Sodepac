<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411141845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_products (id INT AUTO_INCREMENT NOT NULL, catalogue_id INT NOT NULL, product_id INT DEFAULT NULL, INDEX IDX_7E2E895E4A7843DC (catalogue_id), INDEX IDX_7E2E895E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogue_products ADD CONSTRAINT FK_7E2E895E4A7843DC FOREIGN KEY (catalogue_id) REFERENCES catalogue (id)');
        $this->addSql('ALTER TABLE catalogue_products ADD CONSTRAINT FK_7E2E895E4584665A FOREIGN KEY (product_id) REFERENCES canevas_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogue_products DROP FOREIGN KEY FK_7E2E895E4A7843DC');
        $this->addSql('ALTER TABLE catalogue_products DROP FOREIGN KEY FK_7E2E895E4584665A');
        $this->addSql('DROP TABLE catalogue_products');
    }
}
