<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120130845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP TABLE rememberme_token');
        $this->addSql('ALTER TABLE products ADD security_mention VARCHAR(255) DEFAULT NULL, ADD danger_mention VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE TABLE rememberme_token (series CHAR(88) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, value VARCHAR(88) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, lastUsed DATETIME NOT NULL, class VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, username VARCHAR(200) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, UNIQUE INDEX series (series), PRIMARY KEY(series)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE products DROP security_mention, DROP danger_mention');
    }
}
