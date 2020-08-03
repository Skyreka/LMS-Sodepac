<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406130320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE panorama_user ADD sender_id INT NOT NULL');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D9701341F624B39D ON panorama_user (sender_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F624B39D');
        $this->addSql('ALTER TABLE panorama_user DROP sender_id');
    }
}
