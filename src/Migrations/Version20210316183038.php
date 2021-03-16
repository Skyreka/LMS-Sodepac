<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316183038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ppf (id INT AUTO_INCREMENT NOT NULL, culture_id INT NOT NULL, effiency_prev DOUBLE PRECISION DEFAULT NULL, qty_azote_add_prev DOUBLE PRECISION DEFAULT NULL, date_implantation_planned DATE DEFAULT NULL, intermediate_culture TINYINT(1) DEFAULT NULL, push_back TINYINT(1) DEFAULT NULL, date_sow DATE DEFAULT NULL, type_destruction VARCHAR(255) DEFAULT NULL, qty_water_prev DOUBLE PRECISION DEFAULT NULL, type_effluent INT DEFAULT NULL, qty_ependu DOUBLE PRECISION DEFAULT NULL, date_spreading DATE DEFAULT NULL, INDEX IDX_205E0CD9B108249D (culture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ppf ADD CONSTRAINT FK_205E0CD9B108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        //$this->addSql('ALTER TABLE orders_product CHANGE product_id product_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ppf');
        //$this->addSql('ALTER TABLE orders_product CHANGE product_id product_id INT DEFAULT NULL');
    }
}
