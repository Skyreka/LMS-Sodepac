<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324174235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F58D4635');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F58D4635');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id)');
    }
}
