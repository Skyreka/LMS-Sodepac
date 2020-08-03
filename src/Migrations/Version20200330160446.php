<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200330160446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ilots ADD type_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE ilots ADD CONSTRAINT FK_E224BAF3C54C8C93 FOREIGN KEY (type_id) REFERENCES index_grounds (id)');
        $this->addSql('CREATE INDEX IDX_E224BAF3C54C8C93 ON ilots (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ilots DROP FOREIGN KEY FK_E224BAF3C54C8C93');
        $this->addSql('DROP INDEX IDX_E224BAF3C54C8C93 ON ilots');
        $this->addSql('ALTER TABLE ilots ADD type VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_id');
    }
}
