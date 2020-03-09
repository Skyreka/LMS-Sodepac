<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309111812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bsv (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(50) DEFAULT NULL, second_file VARCHAR(50) DEFAULT NULL, sent SMALLINT DEFAULT 0 NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, customers LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', third_file VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exploitation (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, size INT DEFAULT NULL, UNIQUE INDEX UNIQ_BEBCFB167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exploitation ADD CONSTRAINT FK_BEBCFB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bsv');
        $this->addSql('DROP TABLE exploitation');
    }
}
