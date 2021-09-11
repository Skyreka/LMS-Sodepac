<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830120227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ppf_input RENAME INDEX idx_e5bdb75fc47ff850 TO IDX_B14E40DCC47FF850');
        $this->addSql('ALTER TABLE ppf_input RENAME INDEX idx_e5bdb75f4584665a TO IDX_B14E40DC4584665A');
        $this->addSql('ALTER TABLE products CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE recommendation_products ADD c_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ppf_input RENAME INDEX idx_b14e40dc4584665a TO IDX_E5BDB75F4584665A');
        $this->addSql('ALTER TABLE ppf_input RENAME INDEX idx_b14e40dcc47ff850 TO IDX_E5BDB75FC47FF850');
        $this->addSql('ALTER TABLE products CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE recommendation_products DROP c_id');
    }
}
