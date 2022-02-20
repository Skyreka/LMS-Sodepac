<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207141406 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signature (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, identity VARCHAR(255) NOT NULL, sign_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signature_otp (id INT AUTO_INCREMENT NOT NULL, signature_id INT NOT NULL, code VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_AA948F8ED61183A (signature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signature_otp ADD CONSTRAINT FK_AA948F8ED61183A FOREIGN KEY (signature_id) REFERENCES signature (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signature_otp DROP FOREIGN KEY FK_AA948F8ED61183A');
        $this->addSql('DROP TABLE signature');
        $this->addSql('DROP TABLE signature_otp');
    }
}
