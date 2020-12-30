<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214103959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD l1_c1_value DOUBLE PRECISION DEFAULT NULL, ADD l1_c2_value DOUBLE PRECISION DEFAULT NULL, ADD l1_variation DOUBLE PRECISION DEFAULT NULL, ADD l2_c1_value DOUBLE PRECISION DEFAULT NULL, ADD l2_c2_value DOUBLE PRECISION DEFAULT NULL, ADD l2_variation DOUBLE PRECISION DEFAULT NULL, ADD l3_c1_value DOUBLE PRECISION DEFAULT NULL, ADD l3_c2_value DOUBLE PRECISION DEFAULT NULL, ADD l3_variation DOUBLE PRECISION DEFAULT NULL, ADD l4_c1_value DOUBLE PRECISION DEFAULT NULL, ADD l4_c2_value DOUBLE PRECISION DEFAULT NULL, ADD l4_variation DOUBLE PRECISION DEFAULT NULL, DROP brs_deposit_variation, DROP brs_crop_variation, DROP line_deposit_value, DROP line1_crop_value, DROP line2_deposit_value, DROP line2_crop_value');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD brs_deposit_variation DOUBLE PRECISION DEFAULT NULL, ADD brs_crop_variation DOUBLE PRECISION DEFAULT NULL, ADD line_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD line1_crop_value DOUBLE PRECISION DEFAULT NULL, ADD line2_deposit_value DOUBLE PRECISION DEFAULT NULL, ADD line2_crop_value DOUBLE PRECISION DEFAULT NULL, DROP l1_c1_value, DROP l1_c2_value, DROP l1_variation, DROP l2_c1_value, DROP l2_c2_value, DROP l2_variation, DROP l3_c1_value, DROP l3_c2_value, DROP l3_variation, DROP l4_c1_value, DROP l4_c2_value, DROP l4_variation');
    }
}
