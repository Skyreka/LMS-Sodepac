<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200318121126 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interventions ADD product_id INT DEFAULT NULL, ADD reliquat INT DEFAULT NULL');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_5ADBAD7F4584665A ON interventions (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F4584665A');
        $this->addSql('DROP INDEX IDX_5ADBAD7F4584665A ON interventions');
        $this->addSql('ALTER TABLE interventions DROP product_id, DROP reliquat');
    }
}
