<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602083746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets_messages DROP FOREIGN KEY FK_3A9962E24632BB48');
        $this->addSql('DROP INDEX IDX_3A9962E24632BB48 ON tickets_messages');
        $this->addSql('ALTER TABLE tickets_messages CHANGE from_id_id from_id INT NOT NULL');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E278CED90B FOREIGN KEY (from_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3A9962E278CED90B ON tickets_messages (from_id)');
        $this->addSql('ALTER TABLE tickets CHANGE status status INT DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE tickets_messages DROP FOREIGN KEY FK_3A9962E278CED90B');
        $this->addSql('DROP INDEX IDX_3A9962E278CED90B ON tickets_messages');
        $this->addSql('ALTER TABLE tickets_messages CHANGE from_id from_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E24632BB48 FOREIGN KEY (from_id_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3A9962E24632BB48 ON tickets_messages (from_id_id)');
    }
}
