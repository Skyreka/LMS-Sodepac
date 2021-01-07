<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104232527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_contract_culture (id INT AUTO_INCREMENT NOT NULL, purchase_contract_id INT NOT NULL, culture VARCHAR(255) DEFAULT NULL, volume DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, transport INT DEFAULT NULL, depot INT DEFAULT NULL, recovery INT DEFAULT NULL, divers VARCHAR(255) DEFAULT NULL, INDEX IDX_6F3C61015ECBF804 (purchase_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE purchase_contract_culture');
    }
}
