<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108180433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exploitation ADD CONSTRAINT FK_BEBCFB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ilots ADD CONSTRAINT FK_E224BAF3D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE recommendations ADD pdf VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exploitation DROP FOREIGN KEY FK_BEBCFB167B3B43D');
        $this->addSql('ALTER TABLE ilots DROP FOREIGN KEY FK_E224BAF3D967A16D');
        $this->addSql('ALTER TABLE recommendations DROP pdf');
    }
}
