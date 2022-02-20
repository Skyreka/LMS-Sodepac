<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200402225303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD technician_id INT DEFAULT NULL, DROP technician');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9E6C5D496 ON users (technician_id)');
        $this->addSql('ALTER TABLE recommendations CHANGE create_at create_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recommendations CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9E6C5D496');
        $this->addSql('DROP INDEX IDX_1483A5E9E6C5D496 ON users');
        $this->addSql('ALTER TABLE users ADD technician VARCHAR(11) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP technician_id');
    }
}
