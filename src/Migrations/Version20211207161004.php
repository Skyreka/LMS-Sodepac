<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207161004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signature ADD code_otp_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE signature ADD CONSTRAINT FK_AE880141C98BBE3F FOREIGN KEY (code_otp_id) REFERENCES signature_otp (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AE880141C98BBE3F ON signature (code_otp_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signature DROP FOREIGN KEY FK_AE880141C98BBE3F');
        $this->addSql('DROP INDEX UNIQ_AE880141C98BBE3F ON signature');
        $this->addSql('ALTER TABLE signature DROP code_otp_id');
    }
}
