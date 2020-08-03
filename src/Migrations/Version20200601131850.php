<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601131850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tickets_messages (id INT AUTO_INCREMENT NOT NULL, from_id_id INT NOT NULL, ticket_id INT NOT NULL, content LONGTEXT DEFAULT NULL, send_at DATETIME NOT NULL, INDEX IDX_3A9962E24632BB48 (from_id_id), INDEX IDX_3A9962E2700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, technician_id INT NOT NULL, user_id INT NOT NULL, status INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_54469DF4E6C5D496 (technician_id), INDEX IDX_54469DF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E24632BB48 FOREIGN KEY (from_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E2700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets_messages DROP FOREIGN KEY FK_3A9962E2700047D2');
        $this->addSql('DROP TABLE tickets_messages');
        $this->addSql('DROP TABLE tickets');
    }
}
