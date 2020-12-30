<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201228184428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doses ADD culture_id INT DEFAULT NULL, ADD dar VARCHAR(10) DEFAULT NULL, ADD dre VARCHAR(10) DEFAULT NULL, ADD znt VARCHAR(10) DEFAULT NULL, ADD danger_mention VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A8B108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('CREATE INDEX IDX_4EE4A8A8B108249D ON doses (culture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A8B108249D');
        $this->addSql('DROP INDEX IDX_4EE4A8A8B108249D ON doses');
        $this->addSql('ALTER TABLE doses DROP culture_id, DROP dar, DROP dre, DROP znt, DROP danger_mention');
    }
}
