<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201228163749 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD id_lex INT DEFAULT NULL, ADD substance VARCHAR(255) DEFAULT NULL, ADD dar VARCHAR(255) DEFAULT NULL, ADD znt VARCHAR(255) DEFAULT NULL, ADD dre VARCHAR(255) DEFAULT NULL, ADD tox VARCHAR(255) DEFAULT NULL, ADD risk_phase VARCHAR(255) DEFAULT NULL, ADD bio TINYINT(1) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP id_lex, DROP substance, DROP dar, DROP znt, DROP dre, DROP tox, DROP risk_phase, DROP bio, DROP type');
    }
}
