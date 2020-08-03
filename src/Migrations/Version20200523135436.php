<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523135436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE interventions_products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, intervention_id INT NOT NULL, dose DOUBLE PRECISION NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_87F99D4D4584665A (product_id), INDEX IDX_87F99D4D8EAE3863 (intervention_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D8EAE3863 FOREIGN KEY (intervention_id) REFERENCES interventions (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE interventions_products');
    }
}
