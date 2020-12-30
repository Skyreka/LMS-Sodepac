<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229133853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE index_cultures CHANGE slug slug VARCHAR(50) NOT NULL, CHANGE name name VARCHAR(50) NOT NULL, CHANGE is_display is_display TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE orders_product ADD quantity DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE index_cultures CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE is_display is_display TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE orders_product DROP quantity');
    }
}
