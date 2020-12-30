<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214103149 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD column1_txt VARCHAR(255) DEFAULT NULL, ADD column2_txt VARCHAR(255) DEFAULT NULL, ADD line_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD line1_crop_value DOUBLE PRECISION DEFAULT NULL, ADD line2_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD line2_crop_value DOUBLE PRECISION DEFAULT NULL, DROP brs1_txt, DROP brs2_txt, DROP brs1_deposit_value, DROP brs1_crop_value, DROP brs2_deposit_value, DROP brs2_crop_value');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD brs1_txt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD brs2_txt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD brs1_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD brs1_crop_value DOUBLE PRECISION DEFAULT NULL, ADD brs2_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD brs2_crop_value DOUBLE PRECISION DEFAULT NULL, DROP column1_txt, DROP column2_txt, DROP line_deposit_value, DROP line1_crop_value, DROP line2_deposit_value, DROP line2_crop_value');
    }
}
