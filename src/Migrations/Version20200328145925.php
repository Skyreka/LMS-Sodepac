<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328145925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recommendations (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, culture_id INT NOT NULL, INDEX IDX_73904ED7D967A16D (exploitation_id), INDEX IDX_73904ED7B108249D (culture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7B108249D FOREIGN KEY (culture_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE doses CHANGE unit unit VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recommendations');
        $this->addSql('ALTER TABLE doses CHANGE unit unit VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
