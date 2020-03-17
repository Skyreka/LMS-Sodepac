<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317133302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE panorama_user (id INT AUTO_INCREMENT NOT NULL, customers_id INT NOT NULL, panorama_id INT NOT NULL, INDEX IDX_D9701341C3568B40 (customers_id), INDEX IDX_D9701341F58D4635 (panorama_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341C3568B40 FOREIGN KEY (customers_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id)');
        $this->addSql('DROP TABLE panoramas_users');
        $this->addSql('ALTER TABLE panoramas DROP FOREIGN KEY FK_DDADFCB3E6C5D496');
        $this->addSql('DROP INDEX IDX_DDADFCB3E6C5D496 ON panoramas');
        $this->addSql('ALTER TABLE panoramas DROP technician_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE panoramas_users (panoramas_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_B571F249B137F440 (panoramas_id), INDEX IDX_B571F24967B3B43D (users_id), PRIMARY KEY(panoramas_id, users_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE panoramas_users ADD CONSTRAINT FK_B571F24967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panoramas_users ADD CONSTRAINT FK_B571F249B137F440 FOREIGN KEY (panoramas_id) REFERENCES panoramas (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE panorama_user');
        $this->addSql('ALTER TABLE panoramas ADD technician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panoramas ADD CONSTRAINT FK_DDADFCB3E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DDADFCB3E6C5D496 ON panoramas (technician_id)');
    }
}
