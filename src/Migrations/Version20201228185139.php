<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201228185139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A8B108249D');
        $this->addSql('DROP INDEX IDX_4EE4A8A8B108249D ON doses');
        $this->addSql('ALTER TABLE doses CHANGE culture_id index_culture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A8BAE36EE6 FOREIGN KEY (index_culture_id) REFERENCES index_cultures (id)');
        $this->addSql('CREATE INDEX IDX_4EE4A8A8BAE36EE6 ON doses (index_culture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A8BAE36EE6');
        $this->addSql('DROP INDEX IDX_4EE4A8A8BAE36EE6 ON doses');
        $this->addSql('ALTER TABLE doses CHANGE index_culture_id culture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A8B108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('CREATE INDEX IDX_4EE4A8A8B108249D ON doses (culture_id)');
    }
}
