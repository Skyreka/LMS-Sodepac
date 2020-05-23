<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523093740 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F186313FB');
        $this->addSql('DROP INDEX IDX_5ADBAD7F186313FB ON interventions');
        $this->addSql('ALTER TABLE interventions DROP adjuvant_id, DROP adjuvant_quantity');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interventions ADD adjuvant_id INT DEFAULT NULL, ADD adjuvant_quantity DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F186313FB FOREIGN KEY (adjuvant_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_5ADBAD7F186313FB ON interventions (adjuvant_id)');
    }
}
