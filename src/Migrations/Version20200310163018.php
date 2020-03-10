<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200310163018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE irrigation (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, exploitation_id INT NOT NULL, type VARCHAR(30) NOT NULL, quantity INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, intervention_at DATETIME DEFAULT NULL, INDEX IDX_BAE1AE089A4BD21C (ilot_id), INDEX IDX_BAE1AE08D967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE089A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE08D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE irrigation');
    }
}
