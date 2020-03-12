<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312101325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE panoramas (id INT AUTO_INCREMENT NOT NULL, technician_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(255) DEFAULT NULL, second_file VARCHAR(255) DEFAULT NULL, third_file VARCHAR(255) DEFAULT NULL, validate TINYINT(1) NOT NULL, sent TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, INDEX IDX_DDADFCB3E6C5D496 (technician_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panoramas_users (panoramas_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_B571F249B137F440 (panoramas_id), INDEX IDX_B571F24967B3B43D (users_id), PRIMARY KEY(panoramas_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panoramas ADD CONSTRAINT FK_DDADFCB3E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panoramas_users ADD CONSTRAINT FK_B571F249B137F440 FOREIGN KEY (panoramas_id) REFERENCES panoramas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panoramas_users ADD CONSTRAINT FK_B571F24967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE panoramas_users DROP FOREIGN KEY FK_B571F249B137F440');
        $this->addSql('DROP TABLE panoramas');
        $this->addSql('DROP TABLE panoramas_users');
    }
}
