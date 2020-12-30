<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203095050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, culture VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, brs1_txt VARCHAR(255) DEFAULT NULL, brs2_txt VARCHAR(255) DEFAULT NULL, brs1_value DOUBLE PRECISION DEFAULT NULL, brs2_value DOUBLE PRECISION DEFAULT NULL, brs1_variation DOUBLE PRECISION DEFAULT NULL, brs2_variation DOUBLE PRECISION DEFAULT NULL, added_date DATETIME DEFAULT NULL, update_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sales');
    }
}
