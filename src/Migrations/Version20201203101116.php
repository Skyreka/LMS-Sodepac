<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203101116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD brs_deposit_variation DOUBLE PRECISION DEFAULT NULL, ADD brs_crop_variation DOUBLE PRECISION DEFAULT NULL, DROP brs1_deposit_variation, DROP brs1_crop_variation, DROP brs2_deposit_variation, DROP brs2_crop_variation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD brs1_deposit_variation DOUBLE PRECISION DEFAULT NULL, ADD brs1_crop_variation DOUBLE PRECISION DEFAULT NULL, ADD brs2_deposit_variation DOUBLE PRECISION DEFAULT NULL, ADD brs2_crop_variation DOUBLE PRECISION DEFAULT NULL, DROP brs_deposit_variation, DROP brs_crop_variation');
    }
}
