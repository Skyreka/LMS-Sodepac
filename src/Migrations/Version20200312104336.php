<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312104336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE interventions (id INT AUTO_INCREMENT NOT NULL, culture_id INT NOT NULL, effluent_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, intervention_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_5ADBAD7FB108249D (culture_id), INDEX IDX_5ADBAD7F83F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7FB108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F83F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE interventions');
    }
}
