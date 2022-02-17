<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229145659 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recommendations DROP FOREIGN KEY FK_73904ED7B108249D');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7B108249D FOREIGN KEY (culture_id) REFERENCES index_canevas (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recommendations DROP FOREIGN KEY FK_73904ED7B108249D');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7B108249D FOREIGN KEY (culture_id) REFERENCES index_cultures (id)');
    }
}
