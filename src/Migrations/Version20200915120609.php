<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915120609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mix_products ADD mix_id INT NOT NULL');
        $this->addSql('ALTER TABLE mix_products ADD CONSTRAINT FK_785CCDCCA6013C4A FOREIGN KEY (mix_id) REFERENCES mix (id)');
        $this->addSql('CREATE INDEX IDX_785CCDCCA6013C4A ON mix_products (mix_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mix_products DROP FOREIGN KEY FK_785CCDCCA6013C4A');
        $this->addSql('DROP INDEX IDX_785CCDCCA6013C4A ON mix_products');
        $this->addSql('ALTER TABLE mix_products DROP mix_id');
    }
}
